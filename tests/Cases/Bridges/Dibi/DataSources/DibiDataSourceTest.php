<?php declare(strict_types = 1);

namespace Tests\Cases\Bridges\Dibi\DataSources;

use Mockery;
use Tests\Engine\BaseTestCase;
use Tests\Mocks\Dibi\DibiDataSource;
use Tlapnet\Report\Bridges\Dibi\DataSources\LazyDibiResult;
use Tlapnet\Report\Parameters\Impl\TextParameter;
use Tlapnet\Report\Parameters\Parameters;

final class DibiDataSourceTest extends BaseTestCase
{

	public function testQuery(): void
	{
		$result = Mockery::mock('alias:DibiResult');

		$connection = Mockery::mock('alias:DibiConnection');
		$connection->shouldReceive('query')
			->once()
			->with(['SELECT * FROM [foobar]'])
			->andReturn($result);

		$dsx = new DibiDataSource([]);
		$dsx->setConnection($connection);
		$ds = Mockery::mock($dsx)
			->makePartial()
			->shouldAllowMockingProtectedMethods();

		$ds->shouldReceive('connect')
			->once();

		$ds->setSql('SELECT * FROM [foobar]');

		$parameters = new Parameters();

		$result = $ds->compile($parameters);
		$this->assertInstanceOf(LazyDibiResult::class, $result);
	}

	public function testQueryArgs(): void
	{
		$result = Mockery::mock('alias:DibiResult');

		$connection = Mockery::mock('alias:DibiConnection');
		$connection->shouldReceive('query')
			->once()
			->with(['SELECT * FROM [foobar] WHERE [year]=? AND [month]=?', 2000, 10])
			->andReturn($result);

		$dsx = new DibiDataSource([]);
		$dsx->setConnection($connection);
		$ds = Mockery::mock($dsx)
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
		$this->assertInstanceOf(LazyDibiResult::class, $result);
	}

}
