<?php

namespace Tlapnet\Report\Model\Group;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Box\Box;
use Tlapnet\Report\Model\Box\Metadata;
use Tlapnet\Report\Utils\Suggestions;

class Group
{

	/** @var mixed */
	protected $gid;

	/** @var Box[] */
	protected $boxes = [];

	/** @var Metadata */
	protected $metadata;

	/**
	 * @param mixed $gid
	 */
	public function __construct($gid)
	{
		$this->gid = $gid;
		$this->metadata = new Metadata();
	}

	/**
	 * @return mixed
	 */
	public function getGid()
	{
		return $this->gid;
	}

	/**
	 * @param Box $box
	 */
	public function addBox(Box $box)
	{
		$this->boxes[$box->getBid()] = $box;
	}

	/**
	 * @return Box[]
	 */
	public function getBoxes()
	{
		return $this->boxes;
	}

	/**
	 * @param string $bid
	 * @return Box
	 */
	public function getBox($bid)
	{
		if ($this->hasBox($bid)) {
			return $this->boxes[$bid];
		}

		$hint = Suggestions::getSuggestion(array_map(function (Box $box) {
			return $box->getBid();
		}, $this->boxes), $bid);

		throw new InvalidStateException("Box '$bid' not found" . ($hint ? ", did you mean '$hint'?" : '.'));
	}

	/**
	 * @param string $bid
	 * @return bool
	 */
	public function hasBox($bid)
	{
		return isset($this->boxes[$bid]);
	}

	/**
	 * METADATA ****************************************************************
	 */

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function setOption($key, $value)
	{
		$this->metadata->set($key, $value);
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function getOption($key, $default = NULL)
	{
		if (func_num_args() < 2) {
			return $this->metadata->get($key);
		} else {
			return $this->metadata->get($key, $default);
		}
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasOption($key)
	{
		return $this->metadata->has($key);
	}

}
