<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tlapnet\Report\Bridges\Nette\Form\FormFactory;
use Tlapnet\Report\Exceptions\Runtime\CompileException;
use Tlapnet\Report\Model\Subreport\Subreport;

class SubreportRenderControl extends Control
{

	/** @var Subreport */
	private $subreport;

	/**
	 * @param Subreport $subreport
	 */
	public function __construct(Subreport $subreport)
	{
		parent::__construct();
		$this->subreport = $subreport;
	}

	/**
	 * PARAMETERS FORM *********************************************************
	 */

	/**
	 * @return Form
	 */
	protected function createComponentParametersForm()
	{
		// Special our form factory, it creates inputs by given parameters
		$factory = new FormFactory($this->subreport->getParameters());

		// Create form
		$form = $factory->create();

		// Default send button
		$form->addSubmit('send', 'Process');

		// Attach callback
		$form->onSuccess[] = [$this, 'processParametersForm'];

		return $form;
	}

	/**
	 * @param Form $form
	 */
	public function processParametersForm(Form $form)
	{
		dump($form->getValues());
		die();
	}

	/**
	 * RENDERER ****************************************************************
	 */

	/**
	 * Render box
	 */
	public function render()
	{
		try {
			// Compile (fetch data)
			$this->subreport->compile();

			// Preprocess (only if it is not already)
			if (!$this->subreport->isState(Subreport::STATE_PREPROCESSED)) {
				$this->subreport->preprocess();
			}

			// Set template
			$this->template->setFile(__DIR__ . '/templates/subreport.latte');
		} catch (CompileException $e) {
			// Set error template
			$this->template->exception = $e;
			$this->template->setFile(__DIR__ . '/templates/subreport.error.latte');
		}

		// Render
		$this->template->subreport = $this->subreport;
		$this->template->render();
	}

}
