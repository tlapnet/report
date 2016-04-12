<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\Table;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Model\Data\Result;

class TableRenderer extends TemplateRenderer
{

	/** @var array */
	protected $columns = [];

	/** @var array */
	protected $options = [];

	/**
	 * @param string $name
	 * @return Column
	 */
	public function add($name)
	{
		return $this->columns[$name] = new Column($name);
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
