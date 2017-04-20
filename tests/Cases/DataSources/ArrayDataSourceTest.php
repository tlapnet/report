<?php

namespace Tests\Cases\DataSources;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\ArrayDataSource;
use Tlapnet\Report\Parameters\Parameters;

final class ArrayDataSourceTest extends BaseTestCase
{

	/**
	 * @covers ArrayDataSource::compile
	 * @covers ArrayDataSource::getData
	 * @return void
	 */
	public function testDefault()
	{
		$parameters = new Parameters();
		$data = [['foo' => 'bar']];
		$ds = new ArrayDataSource($data);

		$this->assertEquals($data, $ds->compile($parameters)->getData());
	}

}
