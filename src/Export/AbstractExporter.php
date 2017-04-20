<?php

namespace Tlapnet\Report\Export;

use Tlapnet\Report\Utils\Metadata;
use Tlapnet\Report\Utils\TOptions;

abstract class AbstractExporter implements Exporter
{

	use TOptions;

	/**
	 * Creates exporter
	 */
	public function __construct()
	{
		$this->metadata = new Metadata();
	}

}
