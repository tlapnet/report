<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Model\Data\Result;

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
	 * @return Result
	 */
	public function compile(Parameters $parameters)
	{
		$heap = new Result($this->data);

		return $heap;
	}

}
