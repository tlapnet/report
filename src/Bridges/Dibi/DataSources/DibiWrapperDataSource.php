<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use DibiConnection;
use DibiException;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Result\Result;

class DibiWrapperDataSource extends AbstractDatabaseDataSource
{

	/** @var DibiConnection */
	protected $connection;

	/**
	 * @param DibiConnection $connection
	 */
	public function __construct(DibiConnection $connection)
	{
		$this->connection = $connection;
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
		// Ensure connection
		if (!$this->connection->isConnected()) {
			$this->connection->connect();
		}

		// Get SQL
		$sql = $this->getRealSql($parameters);

		try {
			// Prepare parameters
			if (!$parameters->isEmpty()) {
				$switch = $parameters->createSwitcher();
				$switch->setPlaceholder('?');
				// Replace named parameters for ? and return
				// accurate sequenced array of arguments
				list ($sql, $args) = $switch->execute($sql);
			} else {
				// Keep empty arguments
				$args = [];
			}

			// Execute dibi query
			$resultset = $this->connection->query($sql, $args);
		} catch (DibiException $e) {
			throw new SqlException($sql, NULL, $e);
		}

		$result = new LazyDibiResult($resultset);

		return $result;
	}

}
