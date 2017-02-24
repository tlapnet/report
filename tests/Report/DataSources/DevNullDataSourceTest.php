<?php

namespace Tlapnet\Report\Tests\DataSources;

use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Tests\BaseTestCase;

final class DevNullDataSourceTest extends BaseTestCase
{

	/**
	 * @covers DevNullDataSource::compile
	 * @covers DevNullDataSource::getData
	 * @return void
	 */
	public function testDefault()
	{
		$ds = new DevNullDataSource();
		$this->assertNull($ds->compile(new Parameters())->getData());
	}

}
