<?php

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\CallbackDataSource;
use Tlapnet\Report\Parameters\Parameters;

final class CallbackDataSourceTest extends BaseTestCase
{

	/**
	 * @covers CallbackDataSource::compile
	 * @return void
	 */
	public function testDefault()
	{
		$parameters = new Parameters();
		$ds = new CallbackDataSource(function (Parameters $inner) use ($parameters) {
			$this->assertSame($parameters, $inner);

			return 1;
		});

		$this->assertEquals(1, $ds->compile($parameters));
	}

}
