<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Nette\Database\DriverException;
use Nette\Database\Helpers;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tracy\Debugger;

class NetteDatabaseWrapperDataSource extends AbstractDatabaseDataSource
{

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
	 * GETTERS / SETTERS *******************************************************
	 */

	/**
	 * Show or hide tracy panel
	 *
	 * @param bool $show
	 */
	public function setTracyPanel($show)
	{
		if ($show === TRUE) {
			if (class_exists(Debugger::class)) {
				$this->connection->onConnect[] = function () {
					Helpers::createDebugPanel($this->connection);
				};
			}
		}
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
		// Get SQL
		$sql = $this->getRealSql($parameters);

		try {
			if ($this->isPure()) {
				// Expand parameters
				$expander = $parameters->createExpander();
				$sql = $expander->expand($sql);
				// Execute native query
				$resultset = $this->connection->query($sql);
			} else {
				// Execute nette database query
				$args = array_values($parameters->toArray());
				$resultset = $this->connection->queryArgs($sql, $args);
			}
		} catch (DriverException $e) {
			throw new SqlException($sql, NULL, $e);
		}

		$result = new LazyResultSet($resultset);

		return $result;
	}

}
