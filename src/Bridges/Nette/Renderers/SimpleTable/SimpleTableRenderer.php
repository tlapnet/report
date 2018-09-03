<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Renderers\SimpleTable;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Result\Result;

class SimpleTableRenderer extends TemplateRenderer
{

	/** @var object[] */
	protected $columns = [];

	public function addColumn(string $name, string $title): void
	{
		$this->columns[$name] = (object) [
			'name' => $name,
			'title' => $title,
		];
	}

	public function render(Result $result): void
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/simple.table.latte');

		$template->columns = $this->columns;
		$template->rows = $result;

		$template->render();
	}

}
