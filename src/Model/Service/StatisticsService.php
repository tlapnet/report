<?php

namespace Tlapnet\Report\Model\Service;

use Tlapnet\Report\ReportManager;

class StatisticsService
{

	/** @var ReportManager */
	protected $manager;

	/**
	 * @param ReportManager $manager
	 */
	public function __construct(ReportManager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * @return object
	 */
	public function compute()
	{
		$reports = $this->manager->getReports();
		$subreports = [];

		foreach ($reports as $report) {
			foreach ($report->getSubreports() as $subreport) {
				$subreports[] = $subreport;
			}
		}

		return (object) [
			'reports' => count($reports),
			'subreports' => count($subreports),
		];
	}

}
