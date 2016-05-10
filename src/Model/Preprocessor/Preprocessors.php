<?php

namespace Tlapnet\Report\Model\Preprocessor;

use Tlapnet\Report\Model\Data\EditableResult;

class Preprocessors
{

	/** @var Preprocessor[] */
	private $preprocessors = [];

	/**
	 * @param string $column
	 * @param Preprocessor $preprocessor
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
	 * @param EditableResult $result
	 * @return void
	 */
	public function preprocess(EditableResult $result)
	{
		// Fetch data
		$data = $result->getData();

		// Iterate over all rows
		foreach ($data as $rn => $row) {

			// Iterate over all columns in a single row
			foreach ((array)$row as $column => $columnData) {

				// Get preprocessors for this column
				$preprocessors = isset($this->preprocessors[$column]) ? $this->preprocessors[$column] : [];
				/** @var Preprocessor $p */
				foreach ($preprocessors as $p) {
					// chain-preprocessing...
					$columnData = $p->preprocess($columnData);
				}

				// Update column data
				$row[$column] = $columnData;
			}

			// Update row data
			$data[$rn] = $row;
		}

		// Set a preprocessed data
		$result->setData($data);
	}

}
