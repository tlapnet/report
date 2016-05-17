<?php

namespace Tlapnet\Report\Tests\DataSources;

use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Tests\BaseTestCase;

final class DevNullDataSourceTest extends BaseTestCase
{

	public function testDefault()
	{
		$ds = new DevNullDataSource();
		$this->assertNull($ds->compile(new Parameters())->getData());
	}

}
