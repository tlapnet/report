<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use Dibi\Connection as DibiConnection;
use Dibi\Bridges\Nette\Panel as DibiNettePanel;
use Tracy\Debugger;

trait TDibiDebugPanel
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

	protected function createDebugPanel(DibiConnection $connection): void
	{
		if (!class_exists(Debugger::class)) return;

		$panel = new DibiNettePanel();
		$panel->register($connection);
	}

}
