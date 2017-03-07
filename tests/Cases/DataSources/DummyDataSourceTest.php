<?php

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\DummyDataSource;
use Tlapnet\Report\Model\Parameters\Parameters;

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
