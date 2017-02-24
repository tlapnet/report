<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Nette\Utils\Json;
use Tlapnet\Report\Bridges\Nette\Form\Form;
use Tlapnet\Report\Bridges\Nette\Form\FormFactory;
use Tlapnet\Report\Exceptions\Runtime\CompileException;
use Tlapnet\Report\Model\Subreport\Subreport;

class SubreportRenderControl extends Control
{

	// Request parameter
	const REPORT_PARAMETERS = '_rp';

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

		// Default buttons
		$form->addSubmit('send', 'Process');
		$form->addSubmit('reset', 'Reset');

		// Attach callback
		$form->onSuccess[] = [$this, 'processParametersForm'];

		return $form;
	}

	/**
	 * @param Form $form
	 * @return void
	 */
	public function processParametersForm(Form $form)
	{
		$values = $form->getRealValues();

		// Reset parameters
		if ($form['reset']->isSubmittedBy()) {
			$this->redirect('this');
		}

		// Do we have really some values? It might be if all values
		// ale empty or null
		if (!$values) {
			$this->redirect('this');
		}

		// Store form data to parameter
		$this->presenter->redirect('this', [self::REPORT_PARAMETERS => base64_encode(Json::encode($values))]);
	}

	/**
	 * RENDERER ****************************************************************
	 */

	/**
	 * Render box
	 *
	 * @return void
	 */
	public function render()
	{
		try {
			// Attach parameters (only if we have some)
			$parameters = $this->presenter->getParameter(self::REPORT_PARAMETERS);
			if ($parameters) {
				$parameters = (array) Json::decode(base64_decode($parameters));

				// Attach parameters to subreport
				$this->subreport->attach($parameters);

				// Attach parameters to form
				$this['parametersForm']->setDefaults($parameters);
			}

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
