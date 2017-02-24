<?php

namespace Tlapnet\Report\Tests\DataSources;

use Tlapnet\Report\DataSources\DummyDataSource;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Tests\BaseTestCase;

final class DummyDataSourceTest extends BaseTestCase
{

	/**
	 * @covers DummyDataSource::compile
	 * @return void
	 */
	public function testDefault()
	{
		$ds = new DummyDataSource(NULL);
		$this->assertNull($ds->compile(new Parameters()));

		$ds = new DummyDataSource('foobar');
		$this->assertEquals('foobar', $ds->compile(new Parameters()));
	}

}
