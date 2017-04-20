<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\SimpleTable;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Result\Result;

class SimpleTableRenderer extends TemplateRenderer
{

	/** @var array */
	protected $columns = [];

	/**
	 * @param string $name
	 * @param string $title
	 * @return void
	 */
	public function addColumn($name, $title)
	{
		$this->columns[$name] = (object) [
			'name' => $name,
			'title' => $title,
		];
	}

	/**
	 * @param Result $result
	 * @return void
	 */
	public function render(Result $result)
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/simple.table.latte');

		$template->columns = $this->columns;
		$template->rows = $result;

		$template->render();
	}

}
