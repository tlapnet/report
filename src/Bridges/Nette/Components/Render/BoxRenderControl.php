<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Tlapnet\Report\Model\Box\Box;

class BoxRenderControl extends Control
{

	/** @var Box */
	private $box;

	/**
	 * @param Box $box
	 */
	public function __construct(Box $box)
	{
		parent::__construct();
		$this->box = $box;
	}

	/**
	 * Render box
	 */
	public function render()
	{
		// Compile (fetch data)
		$this->box->compile();

		// Set template
		$this->template->setFile(__DIR__ . '/templates/box.latte');

		// Render
		$this->template->box = $this->box;
		$this->template->render();
	}

}
