<?php

namespace Tlapnet\Report\Model\Preprocessor;

use Tlapnet\Report\Model\Result\EditableResult;
use Tlapnet\Report\Model\Result\Mutable;

class Preprocessors
{

	// Vertical-datasets
	const DATASET_KEY = 'key';
	const DATASET_VALUE = 'value';

	/** @var array[Preprocessor[]] */
	private $preprocessors = [];

	/**
	 * @param string $column
	 * @param Preprocessor $preprocessor
	 * @return void
	 */
	public function add($column, Preprocessor $preprocessor)
	{
		if (!isset($this->preprocessors[$column])) {
			$this->preprocessors[$column] = [];
		}
		$this->preprocessors[$column][] = $preprocessor;
	}

	/**
	 * @return bool
	 */
	public function isEmpty()
	{
		return count($this->preprocessors) == 0;
	}

	/**
	 * PREPROCESSING ***********************************************************
	 */

	/**
	 * @param Mutable $result
	 * @return void
	 */
	public function preprocess(Mutable $result)
	{
		// Fetch data
		$data = $result->getData();
		$newdata = [];

		// Iterate over all rows
		foreach ($data as $key => $row) {

			// If row is scalar value, we're assuming it as key-value dataset.
			// Otherwise, it's classic array/structure dataset.
			if (is_scalar($row)) {
				// Get preprocessors for key
				$preprocessors1 = isset($this->preprocessors[self::DATASET_KEY]) ? $this->preprocessors[self::DATASET_KEY] : [];
				foreach ($preprocessors1 as $p1) {
					// chain-preprocessing...
					$key = $p1->preprocess($key);
				}

				// Get preprocessors for value
				$preprocessors2 = isset($this->preprocessors[self::DATASET_VALUE]) ? $this->preprocessors[self::DATASET_VALUE] : [];
				foreach ($preprocessors2 as $p2) {
					// chain-preprocessing...
					$row = $p2->preprocess($row);
				}
			} else {
				// Iterate over all columns in a single row
				foreach ((array) $row as $column => $columnData) {

					// Get preprocessors for this column
					$preprocessors = isset($this->preprocessors[$column]) ? $this->preprocessors[$column] : [];
					foreach ($preprocessors as $p) {
						// chain-preprocessing...
						$columnData = $p->preprocess($columnData);
					}

					// Update column data
					$row[$column] = $columnData;
				}
			}

			// Update row data
			$newdata[$key] = $row;
		}

		// Set a preprocessed data
		$result->setData($newdata);
	}

}
