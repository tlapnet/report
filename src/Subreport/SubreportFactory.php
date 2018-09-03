<?php declare(strict_types = 1);

namespace Tlapnet\Report\Subreport;

interface SubreportFactory
{

	public function create(): Subreport;

}
