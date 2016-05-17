<?php

namespace Tlapnet\Report\Tests\DataSources;

use Tlapnet\Report\DataSources\CallbackDataSource;
use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Tests\BaseTestCase;

final class CallbackDataSourceTest extends BaseTestCase
{

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
