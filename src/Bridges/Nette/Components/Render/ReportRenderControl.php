<?php

namespace Tlapnet\Report\Bridges\Nette\Components\Render;

use Nette\Application\UI\Control;
use Nette\Application\UI\Multiplier;
use Tlapnet\Report\Report\Report;

class ReportRenderControl extends Control
{

	/** @var Report */
	private $report;

	/**
	 * @param Report $report
	 */
	public function __construct(Report $report)
	{
		parent::__construct();
		$this->report = $report;
	}

	/**
	 * @return Multiplier|SubreportRenderControl[]
	 */
	protected function createComponentSubreport()
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
	 *
	 * @return void
	 */
	public function render()
	{
		// Set template
		$this->template->setFile(__DIR__ . '/templates/report.latte');

		// Render
		$this->template->report = $this->report;
		$this->template->render();
	}

}
