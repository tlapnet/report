<?php declare(strict_types = 1);

namespace Tlapnet\Report\Service;

use Tlapnet\Report\Group\Group;
use Tlapnet\Report\Report\Report;
use Tlapnet\Report\ReportManager;

class ReportService
{

	/** @var ReportManager */
	private $manager;

	public function __construct(ReportManager $manager)
	{
		$this->manager = $manager;
	}

	public function getReport(string $rid): ?Report
	{
		$groups = $this->getGroups();
		foreach ($groups as $group) {
			if ($group->hasReport($rid)) {
				return $group->getReport($rid);
			}
		}

		$groupless = $this->getGroupless();
		foreach ($groupless as $report) {
			if ($report->getRid() === $rid) {
				return $report;
			}
		}

		return null;
	}

	/**
	 * @return Group[]
	 */
	public function getGroups(): array
	{
		return $this->manager->getGroups();
	}

	/**
	 * @return Report[]
	 */
	public function getGroupless(): array
	{
		return $this->manager->getGroupless();
	}

}
