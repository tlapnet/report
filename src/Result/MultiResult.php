<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

use AppendIterator;
use Iterator;

class MultiResult extends Result
{

	/** @var Result[] */
	private $results = [];

	public function add(Result $result): void
	{
		$this->results[] = $result;
	}

	/**
	 * @return Result[]
	 */
	public function getData(): array
	{
		return $this->results;
	}

	/**
	 * @return EditableResult
	 */
	public function toEditable(): Mutable
	{
		$data = [];
		$iterator = $this->getIterator();

		while ($iterator->valid()) {
			$data[$iterator->key()] = $iterator->current();
			$iterator->next();
		}

		return new EditableResult($data);
	}

	/**
	 * @return AppendIterator
	 */
	public function getIterator(): Iterator
	{
		$iterator = new AppendIterator();

		foreach ($this->results as $result) {
			$iterator->append($result->getIterator());
		}

		return $iterator;
	}

}
