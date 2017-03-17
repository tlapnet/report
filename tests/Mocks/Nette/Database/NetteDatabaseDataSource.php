<?php

namespace Tests\Mocks\Nette\Database;

use Nette\Database\Connection;
use Tlapnet\Report\Bridges\Nette\Database\DataSources\NetteDatabaseDataSource as TNetteDatabaseDataSource;

final class NetteDatabaseDataSource extends TNetteDatabaseDataSource
{

	/**
	 * @param Connection $connection
	 * @return void
	 */
	public function setConnection($connection)
	{
		$this->connection = $connection;
	}

}
