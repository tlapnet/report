<?php

namespace Tlapnet\Report\Bridges\Tracy\Panel;

use Tlapnet\Report\Model\Service\IntrospectionService;
use Tracy\IBarPanel;

final class ReportPanel implements IBarPanel
{

	/** @var IntrospectionService */
	private $introspection;

	/**
	 * @param IntrospectionService $introspection
	 */
	public function __construct(IntrospectionService $introspection)
	{
		$this->introspection = $introspection;
	}

	/**
	 * Renders HTML code for custom tab.
	 *
	 * @return string
	 */
	public function getTab()
	{
		ob_start();
		$reports = $this->introspection->introspect();
		require __DIR__ . '/templates/tab.phtml';

		return ob_get_clean();
	}

	/**
	 * Renders HTML code for custom panel.
	 *
	 * @return string
	 */
	public function getPanel()
	{
		ob_start();
		$items = $this->introspection->introspect();
		require __DIR__ . '/templates/panel.phtml';

		return ob_get_clean();
	}

}
