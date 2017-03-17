<?php

namespace Tests\Cases\Bridges\Nette\Database\DataSources;

use Mockery;
use Nette\Database\Connection;
use Nette\Database\ResultSet;
use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Bridges\Nette\Database\DataSources\LazyResultSet;
use Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseWrapperDataSource;
use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;
use Tlapnet\Report\Model\Parameters\Parameters;

final class NetteDatabaseWrapperDataSourceTest extends BaseTestCase
{

	/**
	 * @covers NetteDatabaseWrapperDataSource::compile
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

		$ndsx = new NetteDatabaseWrapperDataSource($connection);

		$ndsx->setSql('SELECT * FROM [foobar]');

		$parameters = new Parameters();

		$result = $ndsx->compile($parameters);
		$this->assertInstanceOf(LazyResultSet::class, $result);
	}

	/**
	 * @covers NetteDatabaseWrapperDataSource::compile
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

		$ndsx = new NetteDatabaseWrapperDataSource($connection);

		$ndsx->setSql('SELECT * FROM [foobar] WHERE [year]={YEAR} AND [month]={MONTH}');

		$parameters = new Parameters();
		$parameters->add(new TextParameter('YEAR'));
		$parameters->add(new TextParameter('MONTH'));
		$parameters->attach(['YEAR' => 2000, 'MONTH' => 10]);

		$result = $ndsx->compile($parameters);
		$this->assertInstanceOf(LazyResultSet::class, $result);
	}

}
