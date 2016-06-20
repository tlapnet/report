<?php

namespace Tlapnet\Report\Model\Parameters;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Model\Parameters\Parameter\Parameter;
use Tlapnet\Report\Model\Subreport\Attachable;
use Tlapnet\Report\Utils\Expander;
use Tlapnet\Report\Utils\Suggestions;

class Parameters implements Attachable
{

	/** @var Parameter[] */
	private $parameters = [];

	/**
	 * @param Parameter $parameter
	 */
	public function add(Parameter $parameter)
	{
		$this->parameters[$parameter->getName()] = $parameter;
	}

	/**
	 * @param string $name
	 * @return Parameter
	 */
	public function get($name)
	{
		if (isset($this->parameters[$name])) {
			return $this->parameters[$name];
		}

		$hint = Suggestions::getSuggestion(array_keys($this->parameters), $name);
		throw new InvalidArgumentException("Unknown parameter '$name'" . ($hint ? ", did you mean '$hint'?" : '.'));
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
		throw new NotImplementedException();
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
	 * @return bool
	 */
	public function isEmpty()
	{
		return count($this->parameters) <= 0;
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
