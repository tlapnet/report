<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

use Tlapnet\Report\Parameters\Parameters;

class ParametersResult extends Result
{

	/** @var Parameters */
	protected $parameters;

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data = [], Parameters $parameters)
	{
		$this->parameters = $parameters;

		parent::__construct($data);
	}

	public function getParameters(): Parameters
	{
		return $this->parameters;
	}

	public function setParameters(Parameters $parameters): void
	{
		$this->parameters = $parameters;
	}

}
