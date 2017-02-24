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
	 * @return void
	 */
	public function setLoader($loader)
	{
		$this->loader = $loader;
	}

	/**
	 * @return void
	 */
	public function create()
	{
		throw new NotImplementedException();
	}

}
