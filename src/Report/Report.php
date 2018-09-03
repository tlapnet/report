<?php declare(strict_types = 1);

namespace Tlapnet\Report\Report;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Subreport\Subreport;
use Tlapnet\Report\Utils\Metadata;
use Tlapnet\Report\Utils\Suggestions;
use Tlapnet\Report\Utils\TOptions;

class Report
{

	use TOptions;

	/** @var string */
	protected $rid;

	/** @var Subreport[] */
	protected $subreports = [];

	public function __construct(string $rid)
	{
		$this->rid = $rid;
		$this->metadata = new Metadata();
	}

	public function getRid(): string
	{
		return $this->rid;
	}

	public function addSubreport(Subreport $subreport): void
	{
		$this->subreports[$subreport->getSid()] = $subreport;
	}

	/**
	 * @return Subreport[]
	 */
	public function getSubreports(): array
	{
		return $this->subreports;
	}

	public function getSubreport(string $sid): Subreport
	{
		if (!$this->hasSubreport($sid)) {
			$hint = Suggestions::getSuggestion(array_map(function (Subreport $subreport) {
				return $subreport->getSid();
			}, $this->subreports), $sid);

			throw new InvalidArgumentException(Suggestions::format(sprintf('Subreport "%s" not found', $sid), $hint));
		}

		return $this->subreports[$sid];
	}

	public function hasSubreport(string $bid): bool
	{
		return isset($this->subreports[$bid]);
	}

}
