<?php

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Model\Parameters\Parameters;

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
