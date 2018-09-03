<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Renderers\VerticalTable;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Result\Result;

class VerticalTableRenderer extends TemplateRenderer
{

	/** @var mixed[] */
	protected $columns = [];

	public function setColumns(string $key, string $value): void
	{
		$this->columns['key'] = (object) ['title' => $key];
		$this->columns['value'] = (object) ['title' => $value];
	}

	public function render(Result $result): void
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/vertical.table.latte');

		$template->columns = $this->columns;
		$template->rows = $result;

		$template->render();
	}

}
