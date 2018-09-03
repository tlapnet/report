<?php declare(strict_types = 1);

namespace Tests\Mocks\Nette\Database;

use Nette\Database\Connection;
use Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseDataSource as TNetteDatabaseDataSource;

final class NetteDatabaseDataSource extends TNetteDatabaseDataSource
{

	public function setConnection(Connection $connection): void
	{
		$this->connection = $connection;
	}

}
