<?php

namespace Tlapnet\Report;

use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Loaders\Loader;

class ReportManagerFactory implements IReportManagerFactory
{

	/** @var Loader */
	protected $loader;

	/**
	 * @param Loader $loader
	 */
	public function setLoader($loader)
	{
		$this->loader = $loader;
	}

	/**
	 * @return ReportManager
	 */
	public function create()
	{
		throw new NotImplementedException();
	}

}
