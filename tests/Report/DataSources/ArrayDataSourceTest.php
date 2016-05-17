<?php

namespace Tlapnet\Report\Tests\DataSources;

use Tlapnet\Report\DataSources\ArrayDataSource;
use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Tests\BaseTestCase;

final class ArrayDataSourceTest extends BaseTestCase
{

	public function testDefault()
	{
		$parameters = new Parameters();
		$data = [['foo' => 'bar']];
		$ds = new ArrayDataSource($data);

		$this->assertEquals($data, $ds->compile($parameters)->getData());
	}

}
