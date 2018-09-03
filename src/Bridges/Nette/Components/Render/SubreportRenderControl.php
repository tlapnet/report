<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Tlapnet\Report\Bridges\Nette\Form\Form;
use Tlapnet\Report\Bridges\Nette\Form\FormFactory;
use Tlapnet\Report\Exceptions\Runtime\CompileException;
use Tlapnet\Report\Subreport\Subreport;

/**
 * @property Template $template
 */
class SubreportRenderControl extends Control
{

	// Component inner state
	public const STATE_NORMAL = 1;
	public const STATE_LOADED = 2;

	/** @var Subreport */
	private $subreport;

	/** @var mixed[] */
	private $parameters = [];

	/** @var int */
	private $state = self::STATE_NORMAL;

	public function __construct(Subreport $subreport)
	{
		parent::__construct();
		$this->subreport = $subreport;
	}

	protected function createComponentParametersForm(): Form
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

	public function processParametersForm(Form $form): void
	{
		$values = $form->getRealValues();

		// Reset parameters
		if ($form['reset']->isSubmittedBy()) {
			// Reset all parameters (a.k.a component state)
			$this->parameters = [];
			// Refresh
			$this->redirect('this');
		}

		// Do we have really some values? It might be if all values are empty or null
		if ($values === []) {
			$this->redirect('this');
		}

		// Store form data to parameters and refresh
		$this->parameters = $values;
		$this->redirect('this');
	}

	/**
	 * @param mixed[] $params
	 */
	public function loadState(array $params): void
	{
		parent::loadState($params);

		// Load params from component state
		if (isset($params['params'])) {
			$this->parameters = $params['params'];
		}
	}

	/**
	 * @param mixed[] $params
	 */
	public function saveState(array &$params): void
	{
		parent::saveState($params);

		// Store params to component state
		if ($this->parameters !== []) {
			$params['params'] = $this->parameters;
		}
	}

	public function handleExport(string $exporter): void
	{
		// Load data
		$this->load();

		// Fetch exporter and export data
		$exportable = $this->subreport->export($exporter);
		$exportable->send($this->getPresenter(true));
	}

	/**
	 * Compile, preprocess and attach subreport
	 */
	private function load(): void
	{
		// Skip if it's already loaded
		if ($this->state === self::STATE_LOADED) return;

		// Attach parameters (only if we have some)
		if ($this->parameters !== []) {
			// Attach parameters to form
			$this['parametersForm']->setDefaults($this->parameters);

			// Attach parameters to subreport
			$this->subreport->attach($this->parameters);
		}

		// Compile (fetch data)
		$this->subreport->compile();

		// Preprocess (only if it is not already)
		if (!$this->subreport->isState(Subreport::STATE_PREPROCESSED)) {
			$this->subreport->preprocess();
		}

		// Change inner state
		$this->state = self::STATE_LOADED;
	}

	/**
	 * Render box
	 */
	public function render(): void
	{
		try {
			// Load data
			$this->load();

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
