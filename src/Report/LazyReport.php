<?php declare(strict_types = 1);

namespace Tlapnet\Report\Report;

use Tlapnet\Report\Subreport\Subreport;

class LazyReport extends Report
{

	/** @var callable[] */
	private $lazy = [];

	public function addLazySubreport(string $sid, callable $factory): void
	{
		$this->lazy[$sid] = $factory;
	}

	public function getSubreport(string $sid): Subreport
	{
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
	public function getSubreports(): array
	{
		// We need to initialize all lazy subreports
		foreach ($this->lazy as $key => $factory) {
			$this->getSubreport($key);
		}

		return parent::getSubreports();
	}

}
