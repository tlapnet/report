<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Nette\Database\Helpers;
use Tracy\Debugger;

trait TNetteDatabaseDebugPanel
{

	/** @var bool */
	protected $tracyPanel = false;

	/**
	 * Show or hide tracy panel
	 */
	public function setTracyPanel(bool $show): void
	{
		$this->tracyPanel = $show;
	}

	protected function createDebugPanel(Connection $connection): void
	{
		if (!class_exists(Debugger::class)) return;

		$connection->onConnect[] = function () use ($connection): void {
			Helpers::createDebugPanel($connection, true, 'Tlapnet.Report');
		};
	}

}
