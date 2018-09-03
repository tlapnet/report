<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Form;

use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextInput;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Impl\Parameter;
use Tlapnet\Report\Parameters\Impl\SelectParameter;
use Tlapnet\Report\Parameters\Impl\TextParameter;
use Tlapnet\Report\Parameters\Parameters;

class FormFactory
{

	/** @var Parameters */
	private $parameters;

	public function __construct(Parameters $parameters)
	{
		$this->parameters = $parameters;
	}

	protected function addText(TextParameter $parameter): TextInput
	{
		// Create input
		$input = new TextInput($parameter->getTitle());

		// Setup common attributes / options
		$this->decorate($input, $parameter);

		return $input;
	}

	protected function addSelect(SelectParameter $parameter): SelectBox
	{
		// Create input
		$input = new SelectBox($parameter->getTitle());

		// Select -> items
		$input->setItems($parameter->getItems(), $parameter->isUseKeys());

		// Select -> prompt
		if ($parameter->getPrompt() !== null) {
			$input->setPrompt($parameter->getPrompt());
		}

		// Setup common attributes / options
		$this->decorate($input, $parameter);

		return $input;
	}

	protected function decorate(BaseControl $input, Parameter $parameter): void
	{
		// Default value
		if ($parameter->getDefaultValue() !== null) {
			$input->setDefaultValue($parameter->getDefaultValue());
		}

		// Placeholder
		if ($parameter->hasOption('placeholder')) {
			$input->setAttribute('placeholder', $parameter->getOption('placeholder'));
		}
	}

	public function create(): Form
	{
		$form = new Form();

		// Build form by given parameters
		foreach ($this->parameters->getAll() as $parameter) {

			switch ($parameter->getType()) {

				case Parameter::TYPE_TEXT:
					/** @var TextParameter $parameter */
					$input = $this->addText($parameter);
					break;

				case Parameter::TYPE_SELECT:
					/** @var SelectParameter $parameter */
					$input = $this->addSelect($parameter);
					break;

				default:
					throw new InvalidStateException(sprintf('Unsupported parameter type %s', $parameter->getType()));

			}

			// Add input to form
			$form[$parameter->getName()] = $input;
		}

		return $form;
	}

}
