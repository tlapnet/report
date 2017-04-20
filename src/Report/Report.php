<?php

namespace Tlapnet\Report\Report;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Subreport\Subreport;
use Tlapnet\Report\Utils\Metadata;
use Tlapnet\Report\Utils\Suggestions;
use Tlapnet\Report\Utils\TOptions;

class Report
{

	use TOptions;

	/** @var mixed */
	protected $rid;

	/** @var Subreport[] */
	protected $subreports = [];

	/**
	 * @param mixed $rid
	 */
	public function __construct($rid)
	{
		$this->rid = $rid;
		$this->metadata = new Metadata();
	}

	/**
	 * @return mixed
	 */
	public function getRid()
	{
		return $this->rid;
	}

	/**
	 * @param Subreport $subreport
	 * @return void
	 */
	public function addSubreport(Subreport $subreport)
	{
		$this->subreports[$subreport->getSid()] = $subreport;
	}

	/**
	 * @return Subreport[]
	 */
	public function getSubreports()
	{
		return $this->subreports;
	}

	/**
	 * @param string $sid
	 * @return Subreport
	 */
	public function getSubreport($sid)
	{
		if (!$this->hasSubreport($sid)) {
			$hint = Suggestions::getSuggestion(array_map(function (Subreport $subreport) {
				return $subreport->getSid();
			}, $this->subreports), $sid);

			throw new InvalidArgumentException(Suggestions::format(sprintf('Subreport "%s" not found', $sid), $hint));
		}

		return $this->subreports[$sid];
	}

	/**
	 * @param string $bid
	 * @return bool
	 */
	public function hasSubreport($bid)
	{
		return isset($this->subreports[$bid]);
	}

}
