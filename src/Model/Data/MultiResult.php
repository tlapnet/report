<?php

namespace Tlapnet\Report\Model\Data;

use AppendIterator;
use Traversable;

class MultiResult extends Result
{

	/** @var Result[] */
	private $results = [];

	/**
	 * @param Result $result
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
	 * @return Traversable
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
