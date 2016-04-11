<?php

namespace Tlapnet\Report\Model;

use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\ReportManager;

class ReportService
{

	/** @var ReportManager */
	private $manager;

	/**
	 * @param ReportManager $manager
	 */
	public function __construct(ReportManager $manager)
	{
		$this->manager = $manager;
	}

	/**
	 * REPORTS *****************************************************************
	 */

	/**
	 * @param mixed $rid
	 * @return Report|NULL
	 */
	public function getReport($rid)
	{
		$groups = $this->manager->getGroups();
		foreach ($groups as $group) {
			if ($group->hasReport($rid)) {
				return $group->getReport($rid);
			}
		}

		return NULL;
	}

	/**
	 * GROUPS ******************************************************************
	 */

	/**
	 * @return Group[]
	 */
	public function getGroups()
	{
		return $this->manager->getGroups();
	}

}
