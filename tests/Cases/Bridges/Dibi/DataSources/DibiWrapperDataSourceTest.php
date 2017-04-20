<?php

namespace Tests\Cases\Bridges\Dibi\DataSources;

use Mockery;
use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Bridges\Dibi\DataSources\DibiWrapperDataSource;
use Tlapnet\Report\Bridges\Dibi\DataSources\LazyDibiResult;
use Tlapnet\Report\Model\Parameters\Impl\TextParameter;
use Tlapnet\Report\Model\Parameters\Parameters;

final class DibiWrapperDataSourceTest extends BaseTestCase
{

	/**
	 * @covers DibiWrapperDataSource::compile
	 * @return void
	 */
	public function testQuery()
	{
		$result = Mockery::mock('alias:DibiResult');

		$connection = Mockery::mock('alias:DibiConnection');
		$connection->shouldReceive('isConnected')
			->once()
			->andReturn(TRUE);
		$connection->shouldReceive('query')
			->once()
			->with(['SELECT * FROM [foobar]'])
			->andReturn($result);

		$ds = new DibiWrapperDataSource($connection);
		$ds->setSql('SELECT * FROM [foobar]');

		$parameters = new Parameters();

		$result = $ds->compile($parameters);
		$this->assertInstanceOf(LazyDibiResult::class, $result);
	}

	/**
	 * @covers DibiWrapperDataSource::compile
	 * @return void
	 */
	public function testQueryArgs()
	{
		$result = Mockery::mock('alias:DibiResult');

		$connection = Mockery::mock('alias:DibiConnection');
		$connection->shouldReceive('isConnected')
			->once()
			->andReturn(TRUE);
		$connection->shouldReceive('query')
			->once()
			->with(['SELECT * FROM [foobar] WHERE [year]=? AND [month]=?', 2000, 10])
			->andReturn($result);

		$ds = new DibiWrapperDataSource($connection);
		$ds->setSql('SELECT * FROM [foobar] WHERE [year]={YEAR} AND [month]={MONTH}');

		$parameters = new Parameters();
		$parameters->add(new TextParameter('YEAR'));
		$parameters->add(new TextParameter('MONTH'));
		$parameters->attach(['YEAR' => 2000, 'MONTH' => 10]);

		$result = $ds->compile($parameters);
		$this->assertInstanceOf(LazyDibiResult::class, $result);
	}

}
