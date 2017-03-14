<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable;

use Nette\Application\LinkGenerator;
use Nette\Application\UI\ITemplateFactory;
use Nette\Utils\Strings;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Action;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Blank;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Column;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Component;
use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Model\Result\Result;

class ExtraTableRenderer extends TemplateRenderer
{

	/** @var LinkGenerator */
	protected $linkGenerator;

	/** @var Component[] */
	protected $components = [];

	/** @var array */
	protected $options = [
		'sortable' => TRUE,
	];

	/**
	 * @param ITemplateFactory $templateFactory
	 * @param LinkGenerator $linkGenerator
	 */
	public function __construct(ITemplateFactory $templateFactory, LinkGenerator $linkGenerator)
	{
		parent::__construct($templateFactory);
		$this->linkGenerator = $linkGenerator;
	}

	/**
	 * @param string $name
	 * @param string $title
	 * @return Column
	 */
	public function addColumn($name, $title = NULL)
	{
		$this->components[$name] = $column = new Column($name);

		if ($title) {
			$column->title($title);
		}

		return $column;
	}

	/**
	 * @param string $name
	 * @param string $label
	 * @return Action
	 */
	public function addAction($name, $label = NULL)
	{
		$this->components[$name] = $action = new Action($name);

		if ($label) {
			$action->label($label);
		}

		return $action;
	}

	/**
	 * @param string $name
	 * @return Blank
	 */
	public function addBlank($name)
	{
		$this->components[$name] = $blank = new Blank($name);

		return $blank;
	}

	/**
	 * @param boolean $sortable
	 * @return void
	 */
	public function setSortable($sortable = TRUE)
	{
		$this->options['sortable'] = $sortable;
	}

	/**
	 * TEMPLATE ****************************************************************
	 */

	/**
	 * @param mixed $link
	 * @param mixed $row
	 * @return string
	 */
	public function templateLinker($link, $row)
	{
		// Iterate over all link arguments, find
		// values starting with #<name> and fill
		// that value from row.
		foreach ((array) $link->args as $key => $value) {
			if (Strings::startsWith($value, '#')) {
				$link->args[$key] = $row[substr($value, 1)];
			}
		}

		return $this->linkGenerator->link($link->destination, $link->args);
	}

	/**
	 * RENDER ******************************************************************
	 */

	/**
	 * @param Result $result
	 * @return void
	 */
	public function render(Result $result)
	{
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/table.latte');

		$template->addFilter('linker', [$this, 'templateLinker']);

		$template->options = (object) $this->options;
		$template->components = $this->components;
		$template->rows = $result;

		$template->render();
	}

}
