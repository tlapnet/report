<?php declare(strict_types = 1);

namespace Tlapnet\Report\Loaders;

use Tlapnet\Report\Report\Report;

interface Loader
{

	public function load(): Report;

}
