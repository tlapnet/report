<?php

namespace Tlapnet\Report\Model\Parameters;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Parameters\Parameter\Parameter;
use Tlapnet\Report\Model\Subreport\Attachable;
use Tlapnet\Report\Utils\Expander;
use Tlapnet\Report\Utils\Suggestions;
use Tlapnet\Report\Utils\Switcher;

class Parameters implements Attachable
{

	// States
	const STATE_EMPTY = 1;
	const STATE_ATTACHED = 2;

	/** @var Parameter[] */
	private $parameters = [];

	/** @var int */
	private $state;

	public function __construct()
	{
		$this->state = self::STATE_EMPTY;
	}

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
		$attached = [];
		foreach ($data as $key => $value) {
			// Fetch parameter
			$p = $this->get($key);

			// Set value to parameter
			$p->setValue($value);

			// Controle check - if we really have data
			if ($p->hasValue()) {
				$attached[] = $value;
			}
		}

		// Change state only if data was changed
		if ($attached) {
			$this->state = self::STATE_ATTACHED;
		}
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		$array = [];
		foreach ($this->parameters as $parameter) {
			// Fill only if parameter has a right value
			if ($parameter->hasValue()) {
				$array[$parameter->getName()] = $parameter->getValue();
			}
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
	 * @return bool
	 */
	public function isAttached()
	{
		return $this->state == self::STATE_ATTACHED;
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

	/**
	 * @return Switcher
	 */
	public function createSwitcher()
	{
		return new Switcher($this->toArray());
	}

}
