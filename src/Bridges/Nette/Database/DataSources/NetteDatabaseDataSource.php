<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Nette\Database\DriverException;
use Nette\Database\Helpers;
use Tlapnet\Report\DataSources\AbstractDatabaseConnectionDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Result\Result;
use Tracy\Debugger;

class NetteDatabaseDataSource extends AbstractDatabaseConnectionDataSource
{

	/** @var Connection */
	protected $connection;

	/** @var bool */
	protected $tracyPanel = FALSE;

	/**
	 * GETTERS / SETTERS *******************************************************
	 */

	/**
	 * Show or hide tracy panel
	 *
	 * @param bool $show
	 * @return void
	 */
	public function setTracyPanel($show)
	{
		$this->tracyPanel = $show;
	}

	/**
	 * API *********************************************************************
	 */

	/**
	 * @return void
	 */
	protected function connect()
	{
		$this->connection = new Connection(
			sprintf(
				'%s:host=%s;dbname=%s',
				$this->getConfig('driver'),
				$this->getConfig('host'),
				$this->getConfig('database')
			),
			$this->getConfig('user'),
			$this->getConfig('password'),
			$this->getConfig('options', ['lazy' => TRUE])
		);

		if ($this->tracyPanel === TRUE) {
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
		// Connect to DB
		if (!$this->connection) $this->connect();

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
