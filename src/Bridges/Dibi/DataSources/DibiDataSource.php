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
	 * @return Result
	 * @throws SqlException
	 */
	public function compile(Parameters $parameters)
	{
		// Connect to DB
		if (!$this->connection) $this->connect();

		// Get SQL
		$sql = $this->getRealSql($parameters);

		try {
			if ($this->isPure()) {
				// Expand parameters
				$expander = $parameters->createExpander();
				$sql = $expander->expand($sql);
				// Execute native query
				$resultset = $this->connection->nativeQuery($sql);
			} else {
				// Execute dibi query
				$args = array_values($parameters->toArray());
				$resultset = $this->connection->query($sql, $args);
			}
		} catch (DibiException $e) {
			throw new SqlException($sql, NULL, $e);
		}

		$result = new LazyDibiResult($resultset);

		return $result;
	}

}
