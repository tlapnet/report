<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable;

use Nette\Application\LinkGenerator;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Utils\Strings;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Action;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Blank;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Column;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model\Component;
use Tlapnet\Report\Bridges\Nette\Renderers\TemplateRenderer;
use Tlapnet\Report\Result\Result;

class ExtraTableRenderer extends TemplateRenderer
{

	/** @var LinkGenerator */
	protected $linkGenerator;

	/** @var Component[] */
	protected $components = [];

	/** @var mixed[] */
	protected $options = [
		'sortable' => true,
	];

	public function __construct(TemplateFactory $templateFactory, LinkGenerator $linkGenerator)
	{
		parent::__construct($templateFactory);
		$this->linkGenerator = $linkGenerator;
	}

	public function addColumn(string $name, ?string $title = null): Column
	{
		$this->components[$name] = $column = new Column($name);

		if ($title !== null) {
			$column->title($title);
		}

		return $column;
	}

	public function addAction(string $name, ?string $label = null): Action
	{
		$this->components[$name] = $action = new Action($name);

		if ($label !== null) {
			$action->label($label);
		}

		return $action;
	}

	public function addBlank(string $name): Blank
	{
		$this->components[$name] = $blank = new Blank($name);

		return $blank;
	}

	public function setSortable(bool $sortable = true): void
	{
		$this->options['sortable'] = $sortable;
	}

	/**
	 * @param mixed $link
	 * @param mixed $row
	 */
	public function templateLinker($link, $row): string
	{
		// Don't override original link in template
		$tmplink = clone $link;

		// Iterate over all link arguments, find
		// values starting with #<name> and fill
		// that value from row.
		foreach ((array) $tmplink->args as $key => $value) {
			if (Strings::startsWith($value, '#')) {
				$tmplink->args[$key] = $row[substr($value, 1)];
			}
		}

		return $this->linkGenerator->link($tmplink->destination, $tmplink->args);
	}

	public function render(Result $result): void
	{
		/** @var Template $template */
		$template = $this->createTemplate();
		$template->setFile(__DIR__ . '/templates/table.latte');

		$template->addFilter('linker', [$this, 'templateLinker']);

		$template->options = (object) $this->options;
		$template->components = $this->components;
		$template->rows = $result;

		$template->render();
	}

}
