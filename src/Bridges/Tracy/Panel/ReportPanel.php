<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Tracy\Panel;

use Tlapnet\Report\Service\IntrospectionService;
use Tracy\IBarPanel;

final class ReportPanel implements IBarPanel
{

	/** @var IntrospectionService */
	private $introspection;

	public function __construct(IntrospectionService $introspection)
	{
		$this->introspection = $introspection;
	}

	public function getTab(): string
	{
		ob_start();
		$reports = $this->introspection->introspect();
		require __DIR__ . '/templates/tab.phtml';
		return (string) ob_get_clean();
	}

	public function getPanel(): string
	{
		ob_start();
		$items = $this->introspection->introspect();
		require __DIR__ . '/templates/panel.phtml';
		return (string) ob_get_clean();
	}

}
