<?php declare(strict_types = 1);

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\CallbackDataSource;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;

final class CallbackDataSourceTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$result = new Result();
		$parameters = new Parameters();
		$ds = new CallbackDataSource(function (Parameters $inner) use ($result, $parameters) {
			$this->assertSame($parameters, $inner);
			return $result;
		});

		$this->assertEquals($result, $ds->compile($parameters));
	}

}
