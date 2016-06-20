<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\VerticalTable;

use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Data\MultiResult;
use Tlapnet\Report\Model\Data\Result;

class VerticalTableRenderer extends TemplateRenderer
{

	/** @var array */
	protected $columns = [];

	/**
	 * @param string $key
	 * @param string $value
	 */
	public function setColumns($key, $value)
	{
		$this->columns['key'] = (object)['title' => $key];
		$this->columns['value'] = (object)['title' => $value];
	}

	/**
	 * @param Result $result
	 * @return mixed
	 */
	public function render(Result $result)
	{
		if (!($result instanceof MultiResult)) {
			throw new InvalidStateException('Result must be type of MultiResult');
		}

		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/vertical.table.latte');
		$template->columns = $this->columns;
		$template->rows = $result;
		$template->render();
	}

}
