<?php declare(strict_types = 1);

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\DummyDataSource;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;

final class DummyDataSourceTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$ds = new DummyDataSource([]);
		$this->assertEquals(new Result([]), $ds->compile(new Parameters()));

		$ds = new DummyDataSource(['foobar']);
		$this->assertEquals(new Result(['foobar']), $ds->compile(new Parameters()));
	}

}
