<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\SimpleTable;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Model\Data\Result;

class SimpleTableRenderer extends TemplateRenderer
{

	/** @var array */
	protected $columns = [];

	/**
	 * @param string $name
	 * @param string $title
	 */
	public function addColumn($name, $title)
	{
		$this->columns[$name] = (object)[
			'name' => $name,
			'title' => $title,
		];
	}

	/**
	 * @param Result $report
	 * @return mixed
	 */
	public function render(Result $report)
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/simple.table.latte');
		$template->columns = $this->columns;
		$template->rows = $report;
		$template->render();
	}

}
