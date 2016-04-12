<?php

namespace Tlapnet\Report\Loaders;

use Tlapnet\Report\Model\Report\Report;

interface Loader
{

	/**
	 * @return Report
	 */
	public function load();

}
