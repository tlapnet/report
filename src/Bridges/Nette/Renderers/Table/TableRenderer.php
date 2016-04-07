<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\Table;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Model\Data\Report;

class TableRenderer extends TemplateRenderer
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
	 * @param Report $report
	 * @return mixed
	 */
	public function render(Report $report)
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/table.latte');
		$template->columns = $this->columns;
		$template->rows = $report;
		$template->render();
	}

}
