<?php

namespace Tlapnet\Report\Result;

use AppendIterator;

class MultiResult extends Result implements Editable
{

	/** @var Result[] */
	private $results = [];

	/**
	 * @param Result $result
	 * @return void
	 */
	public function add(Result $result)
	{
		$this->results[] = $result;
	}

	/**
	 * @return Result[]
	 */
	public function getData()
	{
		return $this->results;
	}

	/**
	 * EDITABLE ****************************************************************
	 */

	/**
	 * @return Mutable|EditableResult
	 */
	public function toEditable()
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
	 * ITERATOR ****************************************************************
	 */

	/**
	 * @return AppendIterator
	 */
	public function getIterator()
	{
		$iterator = new AppendIterator();

		foreach ($this->results as $result) {
			$iterator->append($result->getIterator());
		}

		return $iterator;
	}

}
