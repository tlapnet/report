<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use DibiResult;
use Tlapnet\Report\Result\Result;
use Traversable;

class LazyDibiResult extends Result
{

	/** @var DibiResult */
	private $result;

	/**
	 * @param DibiResult $result
	 */
	public function __construct(DibiResult $result)
	{
		parent::__construct();
		$this->result = $result;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->result->fetchAll();
	}

	/**
	 * @return int
	 */
	public function count()
	{
		return $this->result->count();
	}

	/**
	 * @return Traversable
	 */
	public function getIterator()
	{
		return $this->result->getIterator();
	}

}
