<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Heap\Heap;
use Tlapnet\Report\HeapBox\ParameterList;

class ArrayDataSource implements DataSource
{

	/** @var array */
	private $data = [];

	/**
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @param ParameterList $parameters
	 * @return Heap
	 */
	public function compile(ParameterList $parameters)
	{
		$heap = new Heap($this->data);

		return $heap;
	}

}
