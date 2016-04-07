<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Box\ParameterList;
use Tlapnet\Report\Model\Data\Report;

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
	 * @return Report
	 */
	public function compile(ParameterList $parameters)
	{
		$heap = new Report($this->data);

		return $heap;
	}

}
