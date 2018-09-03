<?php declare(strict_types = 1);

namespace Tlapnet\Report\Parameters;

use Tlapnet\Report\Parameters\Impl\Parameter;
use Tlapnet\Report\Parameters\Impl\SelectParameter;
use Tlapnet\Report\Parameters\Impl\TextParameter;

class ParametersBuilder
{

	/** @var Parameter[] */
	private $parameters = [];

	/**
	 * @param mixed[] $values
	 */
	public function addText(array $values): void
	{
		// Create parameter
		$parameter = new TextParameter($values['name']);

		// Setup common attributes
		$this->decorate($parameter, $values);

		$this->addParameter($parameter);
	}

	/**
	 * @param mixed[] $values
	 */
	public function addSelect(array $values): void
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
		} elseif (!$parameter->hasDefaultValue() && !$parameter->hasPrompt()) {
			// Allow autopick of first item in select box,
			// in case of no default value od prompt is set
			$parameter->setAutoPick(true);
		}

		$this->addParameter($parameter);
	}

	protected function addParameter(Parameter $parameter): void
	{
		$this->parameters[] = $parameter;
	}

	/**
	 * @param mixed[] $values
	 */
	protected function decorate(Parameter $parameter, array $values): void
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

	public function build(): Parameters
	{
		$p = new Parameters();

		foreach ($this->parameters as $parameter) {
			$p->add($parameter);
		}

		return $p;
	}

}
