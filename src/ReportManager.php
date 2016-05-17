<?php

namespace Tlapnet\Report;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\Utils\Suggestions;

class ReportManager
{

	/** @var Group[] */
	private $groups = [];

	/** @var Report[] */
	private $groupless = [];

	/**
	 * GROUPS ******************************************************************
	 */

	/**
	 * @param Group $Group
	 */
	public function addGroup(Group $Group)
	{
		$this->groups[$Group->getGid()] = $Group;
	}

	/**
	 * @return Group[]
	 */
	public function getGroups()
	{
		return $this->groups;
	}

	/**
	 * @param string $cid
	 * @return Group
	 */
	public function getGroup($cid)
	{
		if (isset($this->groups[$cid])) {
			return $this->groups[$cid];
		}

		$hint = Suggestions::getSuggestion(array_map(function (Group $Group) {
			return $Group->getGid();
		}, $this->groups), $cid);

		throw new InvalidStateException("Group '$cid' not found" . ($hint ? ", did you mean '$hint'?" : '.'));
	}

	/**
	 * @param string $cid
	 * @return bool
	 */
	public function hasGroup($cid)
	{
		return isset($this->groups[$cid]);
	}

	/**
	 * GROUPLESS ***************************************************************
	 */

	/**
	 * @param Report $report
	 */
	public function addGroupless(Report $report)
	{
		$this->groupless[$report->getRid()] = $report;
	}

	/**
	 * @return Report[]
	 */
	public function getGroupless()
	{
		return $this->groupless;
	}
}
