<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Model\Data\Result;

class TableRenderer extends TemplateRenderer
{

	/** @var array */
	protected $columns = [];

	/** @var array */
	protected $options = [
		'sortable' => TRUE,
	];

	/**
	 * @param string $name
	 * @param string $title
	 * @return Column
	 */
	public function addColumn($name, $title = NULL)
	{
		$this->columns[$name] = $column = new Column($name);

		if ($title) {
			$column->title($title);
		}

		return $column;
	}

	/**
	 * @param boolean $sortable
	 */
	public function setSortable($sortable = TRUE)
	{
		$this->options['sortable'] = $sortable;
	}

	/**
	 * @param Result $report
	 * @return mixed
	 */
	public function render(Result $report)
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/table.latte');

		$template->options = (object)$this->options;
		$template->columns = $this->columns;
		$template->rows = $report;

		$template->render();
	}

}
