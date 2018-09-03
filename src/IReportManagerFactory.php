<?php declare(strict_types = 1);

namespace Tlapnet\Report;

interface IReportManagerFactory
{

	public function create(): ReportManager;

}
