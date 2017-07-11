<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Nette\Database\DriverException;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Parameters\Parameters;

class NetteDatabaseWrapperDataSource extends AbstractDatabaseDataSource
{

	use TNetteDatabaseDebugPanel;

	/** @var Connection */
	protected $connection;

	/**
	 * @param Connection $connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * COMPILING ***************************************************************
	 */

	/**
	 * @param Parameters $parameters
	 * @return LazyResultSet
	 * @throws SqlException
	 */
	public function compile(Parameters $parameters)
	{
		// Debug panel
		if ($this->tracyPanel) $this->createDebugPanel($this->connection);

		// Get SQL
		$sql = $this->getRealSql($parameters);

		try {
			// Prepare parameters
			if ($parameters->canSwitch()) {
				$switch = $parameters->createSwitcher();
				$switch->setPlaceholder('?');
				// Replace named parameters for ? and return
				// accurate sequenced array of arguments
				list ($sql, $args) = $switch->execute($sql);
			} else {
				// Keep empty arguments
				$args = [];
			}

			// Execute nette database query
			$resultset = $this->connection->queryArgs($sql, $args);
		} catch (DriverException $e) {
			throw new SqlException($sql, NULL, $e);
		}

		$result = new LazyResultSet($resultset);

		return $result;
	}

}
