<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Parameters\Parameters;

final class DummyDataSource implements DataSource
{

	/** @var mixed */
	private $return;

	/**
	 * @param mixed $return
	 */
	public function __construct($return = NULL)
	{
		$this->return = $return;
	}

	/**
	 * @param Parameters $parameters
	 * @return mixed
	 */
	public function compile(Parameters $parameters)
	{
		return $this->return;
	}

}
