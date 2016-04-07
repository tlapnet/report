<?php

namespace Tlapnet\Report\Model\Box;

use Tlapnet\Report\Utils\Expander;

class ParameterList implements Attachable
{

	/** @var Parameter[] */
	private $parameters = [];

	/**
	 * @param Parameter $parameter
	 */
	public function add(Parameter $parameter)
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * @return Parameter[]
	 */
	public function getAll()
	{
		return $this->parameters;
	}

	/**
	 * @param array $data
	 */
	public function attach(array $data)
	{
		$stop();
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$array = [];
		foreach ($this->parameters as $parameter) {
			$array[$parameter->getName()] = $parameter->getValue();
		}

		return $array;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @return Expander
	 */
	public function createExpander()
	{
		return new Expander($this->toArray());
	}

}
