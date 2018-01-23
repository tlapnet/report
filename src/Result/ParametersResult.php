<?php

namespace Tlapnet\Report\Result;

use Tlapnet\Report\Parameters\Parameters;

class ParametersResult extends Result
{

	/** @var  Parameters */
	protected $parameters;

	/**
	 * @param array $data
	 * @param Parameters $parameters
	 */
	public function __construct($data = [], Parameters $parameters)
	{
		$this->parameters = $parameters;

		parent::__construct($data);
	}

	/**
	 * @return Parameters
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @param Parameters $parameters
	 */
	public function setParameters(Parameters $parameters)
	{
		$this->parameters = $parameters;
	}

}
