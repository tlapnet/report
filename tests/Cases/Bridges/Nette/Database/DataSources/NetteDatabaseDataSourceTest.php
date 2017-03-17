<?php

namespace Tests\Cases\Bridges\Nette\Database\DataSources;

use Mockery;
use Nette\Database\Connection;
use Nette\Database\ResultSet;
use Tests\Engine\BaseTestCase;
use Tests\Mocks\Nette\Database\NetteDatabaseDataSource;
use Tlapnet\Report\Bridges\Nette\Database\DataSources\LazyResultSet;
use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;
use Tlapnet\Report\Model\Parameters\Parameters;

final class NetteDatabaseDataSourceTest extends BaseTestCase
{

	/**
	 * @covers NetteDatabaseDataSource::compile
	 * @return void
	 */
	public function testQuery()
	{
		$result = Mockery::mock(ResultSet::class);

		$connection = Mockery::mock(Connection::class);
		$connection->shouldReceive('queryArgs')
			->once()
			->with('SELECT * FROM [foobar]', [])
			->andReturn($result);

		$ndsx = new NetteDatabaseDataSource([]);
		$ndsx->setConnection($connection);
		$ds = Mockery::mock($ndsx)
			->makePartial()
			->shouldAllowMockingProtectedMethods();

		$ds->shouldReceive('connect')
			->once();

		$ds->setSql('SELECT * FROM [foobar]');

		$parameters = new Parameters();

		$result = $ds->compile($parameters);
		$this->assertInstanceOf(LazyResultSet::class, $result);
	}

	/**
	 * @covers NetteDatabaseDataSource::compile
	 * @return void
	 */
	public function testQueryArgs()
	{
		$result = Mockery::mock(ResultSet::class);

		$connection = Mockery::mock(Connection::class);
		$connection->shouldReceive('queryArgs')
			->once()
			->with('SELECT * FROM [foobar] WHERE [year]=? AND [month]=?', [2000, 10])
			->andReturn($result);

		$ndsx = new NetteDatabaseDataSource([]);
		$ndsx->setConnection($connection);
		$ds = Mockery::mock($ndsx)
			->makePartial()
			->shouldAllowMockingProtectedMethods();

		$ds->shouldReceive('connect')
			->once();

		$ds->setSql('SELECT * FROM [foobar] WHERE [year]={YEAR} AND [month]={MONTH}');

		$parameters = new Parameters();
		$parameters->add(new TextParameter('YEAR'));
		$parameters->add(new TextParameter('MONTH'));
		$parameters->attach(['YEAR' => 2000, 'MONTH' => 10]);

		$result = $ds->compile($parameters);
		$this->assertInstanceOf(LazyResultSet::class, $result);
	}

}
