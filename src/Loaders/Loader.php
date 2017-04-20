<?php

namespace Tlapnet\Report\Loaders;

use Tlapnet\Report\Report\Report;

interface Loader
{

	/**
	 * @return Report
	 */
	public function load();

}
