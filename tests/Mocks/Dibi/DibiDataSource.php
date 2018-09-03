<?php declare(strict_types = 1);

namespace Tests\Mocks\Dibi;

use DibiConnection;
use Tlapnet\Report\Bridges\Dibi\DataSources\DibiDataSource as TDibiDataSource;

final class DibiDataSource extends TDibiDataSource
{

	public function setConnection(DibiConnection $connection): void
	{
		$this->connection = $connection;
	}

}
