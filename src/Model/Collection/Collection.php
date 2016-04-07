<?php

namespace Tlapnet\Report\Model\Collection;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Utils\Suggestions;

class Collection
{

	/** @var mixed */
	protected $cid;

	/** @var string */
	protected $name;

	/** @var Group[] */
	protected $groups = [];

	/**
	 * @param mixed $cid
	 * @param string $name
	 */
	public function __construct($cid, $name)
	{
		$this->cid = $cid;
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getCid()
	{
		return $this->cid;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param Group $group
	 */
	public function addGroup(Group $group)
	{
		$this->groups[$group->getGid()] = $group;
	}

	/**
	 * @return Group[]
	 */
	public function getGroups()
	{
		return $this->groups;
	}

	/**
	 * @param string $gid
	 * @return Group
	 */
	public function getGroup($gid)
	{
		if ($this->hasGroup($gid)) {
			return $this->groups[$gid];
		}

		$hint = Suggestions::getSuggestion(array_map(function (Group $group) {
			return $group->getGid();
		}, $this->groups), $gid);

		throw new InvalidStateException("Group '$gid' not found" . ($hint ? ", did you mean '$hint'?" : '.'));
	}

	/**
	 * @param string $gid
	 * @return bool
	 */
	public function hasGroup($gid)
	{
		return isset($this->groups[$gid]);
	}

}
