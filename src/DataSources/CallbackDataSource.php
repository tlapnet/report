<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Model\Subreport\Parameters;

final class CallbackDataSource implements DataSource
{

	/** @var callable */
	private $callback;

	/**
	 * @param callable $callback
	 */
	public function __construct($callback)
	{
		$this->callback = $callback;
	}

	/**
	 * @param Parameters $parameters
	 * @return mixed
	 */
	public function compile(Parameters $parameters)
	{
		return call_user_func($this->callback, $parameters);
	}

}
