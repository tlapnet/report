<?php declare(strict_types = 1);

namespace Tlapnet\Report\Group;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Report\Report;
use Tlapnet\Report\Utils\Suggestions;

class Group
{

	/** @var string */
	protected $gid;

	/** @var string */
	protected $name;

	/** @var Report[] */
	protected $reports = [];

	public function __construct(string $gid, string $name)
	{
		$this->gid = $gid;
		$this->name = $name;
	}

	public function getGid(): string
	{
		return $this->gid;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function addReport(Report $report): void
	{
		$this->reports[$report->getRid()] = $report;
	}

	/**
	 * @return Report[]
	 */
	public function getReports(): array
	{
		return $this->reports;
	}

	public function getReport(string $rid): Report
	{
		if (!$this->hasReport($rid)) {
			$hint = Suggestions::getSuggestion(array_map(function (Report $report) {
				return $report->getRid();
			}, $this->reports), $rid);

			throw new InvalidArgumentException(Suggestions::format(sprintf('Report "%s" not found', $rid), $hint));
		}

		return $this->reports[$rid];
	}

	public function hasReport(string $rid): bool
	{
		return isset($this->reports[$rid]);
	}

}
