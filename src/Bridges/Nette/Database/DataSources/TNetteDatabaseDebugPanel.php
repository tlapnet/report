<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Nette\Database\Helpers;
use Tracy\Debugger;

trait TNetteDatabaseDebugPanel
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
	 * @param Connection $connection
	 * @return void
	 */
	protected function createDebugPanel(Connection $connection)
	{
		if (!class_exists(Debugger::class)) return;

		$connection->onConnect[] = function () use ($connection) {
			Helpers::createDebugPanel($connection, TRUE, 'Tlapnet.Report');
		};
	}

}
