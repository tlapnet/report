<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Data\Resultable;
use Tlapnet\Report\Model\Subreport\Parameters;

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
		$heap = new Result($this->data);

		return $heap;
	}

}
