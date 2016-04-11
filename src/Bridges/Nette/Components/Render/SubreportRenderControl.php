<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
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
		// Compile (fetch data)
		$this->subreport->compile();

		// Set template
		$this->template->setFile(__DIR__ . '/templates/subreport.latte');

		// Render
		$this->template->subreport = $this->subreport;
		$this->template->render();
	}

}
