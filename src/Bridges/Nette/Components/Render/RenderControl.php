<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Tlapnet\Report\HeapBox\HeapBox;

class RenderControl extends Control
{

	/** @var HeapBox */
	private $box;

	/**
	 * @param HeapBox $heap
	 */
	public function __construct(HeapBox $heap)
	{
		parent::__construct();
		$this->box = $heap;
	}

	/**
	 * Render heap
	 */
	public function render()
	{
		// Compile (fetch data)
		$this->box->compile();

		// Set template
		$this->template->setFile(__DIR__ . '/templates/render.latte');

		// Render
		$this->template->box = $this->box;
		$this->template->render();
	}

}
