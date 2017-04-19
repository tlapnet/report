<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\VerticalTable;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Model\Result\Result;

class VerticalTableRenderer extends TemplateRenderer
{

	/** @var array */
	protected $columns = [];

	/**
	 * @param string $key
	 * @param string $value
	 * @return void
	 */
	public function setColumns($key, $value)
	{
		$this->columns['key'] = (object) ['title' => $key];
		$this->columns['value'] = (object) ['title' => $value];
	}

	/**
	 * @param Result $result
	 * @return void
	 */
	public function render(Result $result)
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/vertical.table.latte');

		$template->columns = $this->columns;
		$template->rows = $result;

		$template->render();
	}

}
