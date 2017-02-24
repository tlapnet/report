<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Model\Result\Resultable;

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
