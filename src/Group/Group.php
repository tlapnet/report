<?php

namespace Tlapnet\Report\Group;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Report\Report;
use Tlapnet\Report\Utils\Suggestions;

class Group
{

	/** @var mixed */
	protected $gid;

	/** @var string */
	protected $name;

	/** @var Report[] */
	protected $reports = [];

	/**
	 * @param mixed $gid
	 * @param string $name
	 */
	public function __construct($gid, $name)
	{
		$this->gid = $gid;
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getGid()
	{
		return $this->gid;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param Report $report
	 * @return void
	 */
	public function addReport(Report $report)
	{
		$this->reports[$report->getRid()] = $report;
	}

	/**
	 * @return Report[]
	 */
	public function getReports()
	{
		return $this->reports;
	}

	/**
	 * @param string $rid
	 * @return Report
	 */
	public function getReport($rid)
	{
		if (!$this->hasReport($rid)) {
			$hint = Suggestions::getSuggestion(array_map(function (Report $report) {
				return $report->getRid();
			}, $this->reports), $rid);

			throw new InvalidArgumentException('Report "' . $rid . '" not found' . ($hint ? ', did you mean "' . $hint . '"?' : '.'));
		}

		return $this->reports[$rid];
	}

	/**
	 * @param string $rid
	 * @return bool
	 */
	public function hasReport($rid)
	{
		return isset($this->reports[$rid]);
	}

}
