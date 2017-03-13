<?php

namespace Tests\Mocks\Dibi;

use DibiConnection;
use Tlapnet\Report\Bridges\Dibi\DataSources\DibiDataSource as TDibiDataSource;

final class DibiDataSource extends TDibiDataSource
{

	/**
	 * @param DibiConnection $connection
	 * @return void
	 */
	public function setConnection(DibiConnection $connection)
	{
		$this->connection = $connection;
	}

}
