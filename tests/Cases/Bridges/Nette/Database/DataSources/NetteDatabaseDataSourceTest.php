<?php declare(strict_types = 1);

namespace Tests\Cases\Bridges\Nette\Database\DataSources;

use Mockery;
use Nette\Database\Connection;
use Nette\Database\ResultSet;
use Tests\Engine\BaseTestCase;
use Tests\Mocks\Nette\Database\NetteDatabaseDataSource;
use Tlapnet\Report\Bridges\Nette\Database\DataSources\LazyResultSet;
use Tlapnet\Report\Parameters\Impl\TextParameter;
use Tlapnet\Report\Parameters\Parameters;

final class NetteDatabaseDataSourceTest extends BaseTestCase
{

	public function testQuery(): void
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

	public function testQueryArgs(): void
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
