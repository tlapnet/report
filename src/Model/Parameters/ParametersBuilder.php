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
	 * @param Parameter $parameter
	 * @param array $values
	 */
	protected function decorate(Parameter $parameter, array $values)
	{
		// Title
		if (isset($values['title'])) {
			$parameter->setTitle($values['title']);
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
