<?php

namespace Tlapnet\Report\Model\Result;

use AppendIterator;
use Traversable;

class MultiResult extends Result
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
