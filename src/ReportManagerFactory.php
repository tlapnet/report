<?php declare(strict_types = 1);

namespace Tlapnet\Report;

use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Loaders\Loader;

class ReportManagerFactory implements IReportManagerFactory
{

	/** @var Loader */
	protected $loader;

	public function setLoader(Loader $loader): void
	{
		$this->loader = $loader;
	}

	public function create(): ReportManager
	{
		throw new NotImplementedException();
	}

}
