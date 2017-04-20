<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;

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
	 * @param Parameters $parameters
	 * @return Resultable
	 */
	public function compile(Parameters $parameters)
	{
		$result = new Result($this->data);

		return $result;
	}

}
