<?php declare(strict_types = 1);

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\ArrayDataSource;
use Tlapnet\Report\Parameters\Parameters;

final class ArrayDataSourceTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$parameters = new Parameters();
		$data = [['foo' => 'bar']];
		$ds = new ArrayDataSource($data);

		$this->assertEquals($data, $ds->compile($parameters)->getData());
	}

}
