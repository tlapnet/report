<?php

namespace Tlapnet\Report\Model;

use Tlapnet\Report\Model\Collection\Collection;
use Tlapnet\Report\Model\Group\Group;
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
	 * GROUPS ******************************************************************
	 */

	/**
	 * @param mixed $cid
	 * @param mixed $gid
	 * @return Group|NULL
	 */
	public function getGroup($cid, $gid)
	{
		if (!$this->manager->hasCollection($cid)) {
			return NULL;
		}

		$collection = $this->manager->getCollection($cid);

		if (!$collection->hasGroup($gid)) {
			return NULL;
		}

		$group = $collection->getGroup($gid);

		return $group;
	}

	/**
	 * COLLECTIONS *************************************************************
	 */

	/**
	 * @return Collection[]
	 */
	public function getCollections()
	{
		return $this->manager->getCollections();
	}

}
