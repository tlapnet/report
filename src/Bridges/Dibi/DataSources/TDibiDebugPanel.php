<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use DibiConnection;
use DibiNettePanel;
use Tracy\Debugger;

trait TDibiDebugPanel
{

	/** @var bool */
	protected $tracyPanel = FALSE;

	/**
	 * GETTERS / SETTERS *******************************************************
	 */

	/**
	 * Show or hide tracy panel
	 *
	 * @param bool $show
	 * @return void
	 */
	public function setTracyPanel($show)
	{
		$this->tracyPanel = $show;
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @param DibiConnection $connection
	 * @return void
	 */
	protected function createDebugPanel(DibiConnection $connection)
	{
		if (!class_exists(Debugger::class)) return;

		$panel = new DibiNettePanel();
		$panel->register($connection);
	}

}
