<?php

namespace Tlapnet\Report\Bridges\Nette\Form;

use Nette\Application\UI\Form;
use Nette\Forms\Controls\SelectBox;
use Nette\Forms\Controls\TextInput;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Parameters\Parameter\Parameter;
use Tlapnet\Report\Model\Parameters\Parameter\SelectParameter;
use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;
use Tlapnet\Report\Model\Parameters\Parameters;

class FormFactory
{

	/** @var Parameters */
	private $parameters;

	/**
	 * @param Parameters $parameters
	 */
	public function __construct(Parameters $parameters)
	{
		$this->parameters = $parameters;
	}

	/**
	 * PARTS *******************************************************************
	 */

	/**
	 * @param TextParameter $parameter
	 * @return TextInput
	 */
	protected function addText(TextParameter $parameter)
	{
		$input = new TextInput($parameter->getTitle());

		return $input;
	}

	/**
	 * @param SelectParameter $parameter
	 * @return SelectBox
	 */
	protected function addSelect(SelectParameter $parameter)
	{
		$input = new SelectBox($parameter->getTitle());

		return $input;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @return Form
	 */
	public function create()
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