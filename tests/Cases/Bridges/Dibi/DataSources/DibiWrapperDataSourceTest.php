<?php declare(strict_types = 1);

namespace Tests\Cases\Bridges\Dibi\DataSources;

use Mockery;
use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Bridges\Dibi\DataSources\DibiWrapperDataSource;
use Tlapnet\Report\Bridges\Dibi\DataSources\LazyDibiResult;
use Tlapnet\Report\Parameters\Impl\TextParameter;
use Tlapnet\Report\Parameters\Parameters;

final class DibiWrapperDataSourceTest extends BaseTestCase
{

	public function testQuery(): void
	{
		$result = Mockery::mock('alias:Dibi\Result');

		$connection = Mockery::mock('alias:Dibi\Connection');
		$connection->shouldReceive('isConnected')
			->once()
			->andReturn(true);
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

	public function testQueryArgs(): void
	{
		$result = Mockery::mock('alias:Dibi\Result');

		$connection = Mockery::mock('alias:Dibi\Connection');
		$connection->shouldReceive('isConnected')
			->once()
			->andReturn(true);
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
