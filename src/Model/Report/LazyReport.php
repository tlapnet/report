<?php

namespace Tlapnet\Report\Model\Report;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Subreport\Subreport;

class LazyReport extends Report
{

	/** @var array */
	private $lazy = [];

	/**
	 * @param string $sid
	 * @param callable $factory
	 * @return void
	 */
	public function addLazySubreport($sid, callable $factory)
	{
		$this->lazy[$sid] = $factory;
	}

	/**
	 * @param string $sid
	 * @return Subreport
	 */
	public function getSubreport($sid)
	{
		if (!is_string($sid)) {
			throw new InvalidArgumentException(sprintf('Subreport ID must be string, "%s" given.', gettype($sid)));
		}

		// Do we have some lazy-subreports?
		if (isset($this->lazy[$sid])) {
			// Create real subreport
			$factory = $this->lazy[$sid];
			$this->addSubreport($factory());

			// Remove from lazy set
			unset($this->lazy[$sid]);
		}

		return parent::getSubreport($sid);
	}

	/**
	 * @return Subreport[]
	 */
	public function getSubreports()
	{
		// We need to initialize all lazy subreports
		foreach ($this->lazy as $key => $factory) {
			$this->getSubreport($key);
		}

		return parent::getSubreports();
	}

}
