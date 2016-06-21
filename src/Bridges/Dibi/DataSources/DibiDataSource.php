<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use DibiConnection;
use DibiException;
use Tlapnet\Report\DataSources\AbstractDatabaseConnectionDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Parameters\Parameters;

class DibiDataSource extends AbstractDatabaseConnectionDataSource
{

	/** @var DibiConnection */
	protected $connection;

	/**
	 * @return void
	 */
	protected function connect()
	{
		$this->connection = new DibiConnection([
			'driver' => $this->getConfig('driver'),
			'host' => $this->getConfig('host'),
			'username' => $this->getConfig('user'),
			'password' => $this->getConfig('password'),
		]);
	}

	/**
	 * COMPILING ***************************************************************
	 */

	/**
	 * @param Parameters $parameters
	 * @param string $query
	 * @return Result
	 * @throws SqlException
	 */
	public function doCompile(Parameters $parameters, $query)
	{
		// Connect to DB
		if (!$this->connection) $this->connect();

		try {
			// Execute query
			$resultset = $this->connection->query($query);
		} catch (DibiException $e) {
			throw new SqlException($query, NULL, $e);
		}

		$result = new LazyDibiResult($resultset);

		return $result;
	}

}
