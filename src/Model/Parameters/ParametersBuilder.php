<?php

namespace Tlapnet\Report\Model\Parameters;

use Tlapnet\Report\Model\Parameters\Parameter\Parameter;
use Tlapnet\Report\Model\Parameters\Parameter\SelectParameter;
use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;

class ParametersBuilder
{

	/** @var Parameter[] */
	private $parameters = [];

	/**
	 * PARTS *******************************************************************
	 */

	/**
	 * @param array $values
	 * @return void
	 */
	public function addText(array $values)
	{
		// Create parameter
		$parameter = new TextParameter($values['name']);

		// Setup common attributes
		$this->decorate($parameter, $values);

		$this->addParameter($parameter);
	}

	/**
	 * @param array $values
	 * @return void
	 */
	public function addSelect(array $values)
	{
		// Create parameter
		$parameter = new SelectParameter($values['name']);

		// Setup common attributes
		$this->decorate($parameter, $values);

		// Select > items
		if (isset($values['items'])) {
			$parameter->setItems($values['items']);
		}

		// Select > prompt
		if (isset($values['prompt'])) {
			$parameter->setPrompt($values['prompt']);
		}

		// Select > useKeys
		if (isset($values['useKeys'])) {
			$parameter->setUseKeys($values['useKeys']);
		}

		// Select > useKeys
		if (isset($values['use_keys'])) {
			trigger_error('Please use useKeys: <...>', E_USER_WARNING);
			$parameter->setUseKeys($values['use_keys']);
		}

		// Select > autopick
		if (isset($values['autopick'])) {
			$parameter->setAutoPick($values['useKeys']);
		} else if (!$parameter->hasDefaultValue() && !$parameter->hasPrompt()) {
			// Allow autopick of first item in select box,
			// in case of no default value od prompt is set
			$parameter->setAutoPick(TRUE);
		}

		$this->addParameter($parameter);
	}

	/**
	 * @param Parameter $parameter
	 * @return void
	 */
	protected function addParameter(Parameter $parameter)
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * @param Parameter $parameter
	 * @param array $values
	 * @return void
	 */
	protected function decorate(Parameter $parameter, array $values)
	{
		// Title
		if (isset($values['title'])) {
			$parameter->setTitle($values['title']);
		}

		// Default Value
		if (isset($values['defaultValue'])) {
			$parameter->setDefaultValue($values['defaultValue']);
		}

		// Default Value
		if (isset($values['default_value'])) {
			trigger_error('Please use defaultValue: <...>', E_USER_WARNING);
			$parameter->setDefaultValue($values['default_value']);
		}

		// Options
		if (isset($values['options'])) {
			$parameter->setOptions($values['options']);
		}
	}

	/**
	 * API *********************************************************************
	 */

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
