<?php declare(strict_types = 1);

namespace Tlapnet\Report;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Group\Group;
use Tlapnet\Report\Report\Report;
use Tlapnet\Report\Utils\Suggestions;

class ReportManager
{

	/** @var Group[] */
	private $groups = [];

	/** @var Report[] */
	private $groupless = [];

	public function addGroup(Group $Group): void
	{
		$this->groups[$Group->getGid()] = $Group;
	}

	/**
	 * @return Group[]
	 */
	public function getGroups(): array
	{
		return $this->groups;
	}

	public function getGroup(string $cid): Group
	{
		if (!isset($this->groups[$cid])) {
			$hint = Suggestions::getSuggestion(array_map(function (Group $Group) {
				return $Group->getGid();
			}, $this->groups), $cid);

			throw new InvalidStateException(Suggestions::format(sprintf('Group "%s" not found', $cid), $hint));
		}

		return $this->groups[$cid];
	}

	public function hasGroup(string $cid): bool
	{
		return isset($this->groups[$cid]);
	}

	public function addGroupless(Report $report): void
	{
		$this->groupless[$report->getRid()] = $report;
	}

	/**
	 * @return Report[]
	 */
	public function getGroupless(): array
	{
		return $this->groupless;
	}

	/**
	 * @return Report[]
	 */
	public function getReports(): array
	{
		$reports = [];

		foreach ($this->getGroups() as $group) {
			foreach ($group->getReports() as $report) {
				$reports[] = $report;
			}
		}
		foreach ($this->getGroupless() as $report) {
			$reports[] = $report;
		}

		return $reports;
	}

}
