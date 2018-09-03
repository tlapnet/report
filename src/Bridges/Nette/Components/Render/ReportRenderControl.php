<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;
use Nette\Bridges\ApplicationLatte\Template;
use Tlapnet\Report\Report\Report;

/**
 * @property Template $template
 */
class ReportRenderControl extends Control
{

	/** @var Report */
	private $report;

	public function __construct(Report $report)
	{
		parent::__construct();
		$this->report = $report;
	}

	protected function createComponentSubreport(): Multiplier
	{
		// Add all lazy-loading subreports to prevent
		// non-natural sorting
		$this->report->getSubreports();

		return new Multiplier(function ($sid) {
			$subreport = $this->report->getSubreport($sid);

			return new SubreportRenderControl($subreport);
		});
	}

	/**
	 * Render group
	 */
	public function render(): void
	{
		// Set template
		$this->template->setFile(__DIR__ . '/templates/report.latte');

		// Render
		$this->template->report = $this->report;
		$this->template->render();
	}

}
