<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
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
