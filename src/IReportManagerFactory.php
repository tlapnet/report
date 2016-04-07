<?php

namespace Tlapnet\Report;

interface IReportManagerFactory
{

	/**
	 * @return ReportManager
	 */
	public function create();

}
