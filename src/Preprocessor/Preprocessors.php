<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor;

use Tlapnet\Report\Result\Mutable;

class Preprocessors
{

	// Vertical-datasets
	public const DATASET_KEY = 'key';
	public const DATASET_VALUE = 'value';

	/** @var Preprocessor[][] */
	private $preprocessors = [];

	public function add(string $column, Preprocessor $preprocessor): void
	{
		if (!isset($this->preprocessors[$column])) {
			$this->preprocessors[$column] = [];
		}
		$this->preprocessors[$column][] = $preprocessor;
	}

	public function isEmpty(): bool
	{
		return $this->preprocessors === [];
	}

	public function preprocess(Mutable $result): void
	{
		// Fetch data
		$data = $result->getData();
		$newdata = [];
		$pointer = 0;

		// Iterate over all rows
		foreach ($data as $key => $row) {

			// If row is scalar value, we're assuming it as key-value dataset.
			// Otherwise, it's classic array/structure dataset.
			if (is_scalar($row)) {
				// Get preprocessors for generic key
				$preprocessors1a = $this->preprocessors[self::DATASET_KEY] ?? [];
				foreach ($preprocessors1a as $p1) {
					// chain-preprocessing...
					$key = $p1->preprocess($key);
				}

				// Get preprocessors for specific key0-N
				$preprocessors1b = $this->preprocessors[self::DATASET_KEY . $pointer] ?? [];
				foreach ($preprocessors1b as $p1) {
					// chain-preprocessing...
					$key = $p1->preprocess($key);
				}

				// Get preprocessors for generic value
				$preprocessors2a = $this->preprocessors[self::DATASET_VALUE] ?? [];
				foreach ($preprocessors2a as $p2) {
					// chain-preprocessing...
					$row = $p2->preprocess($row);
				}

				// Get preprocessors for specific value0-N
				$preprocessors2b = $this->preprocessors[self::DATASET_VALUE . $pointer] ?? [];
				foreach ($preprocessors2b as $p2) {
					// chain-preprocessing...
					$row = $p2->preprocess($row);
				}

			} else {
				// Iterate over all columns in a single row
				foreach ((array) $row as $column => $columnData) {

					// Get preprocessors for this column
					$preprocessors = $this->preprocessors[$column] ?? [];
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

			// Update pointer
			$pointer++;
		}

		// Set a preprocessed data
		$result->setData($newdata);
	}

}
