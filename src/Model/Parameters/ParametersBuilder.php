<?php

namespace Tlapnet\Report\Model\Parameters;

use Tlapnet\Report\Model\Parameters\Parameter\Parameter;
use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;

class ParametersBuilder
{

	/** @var Parameter[] */
	private $parameters = [];

	/**
	 * @param string $name
	 * @param string $title
	 */
	public function addText($name, $title)
	{
		$parameter = new TextParameter($name);
		$parameter->setTitle($title);

		$this->addParameter($parameter);
	}

	/**
	 * @param Parameter $parameter
	 */
	protected function addParameter(Parameter $parameter)
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * @return Parameters
	 */
	public function build()
	{
		$p = new Parameters();

		foreach ($this->parameters as $parameter) {
			$p->add($parameter);
		}

		return $p;
	}

}
