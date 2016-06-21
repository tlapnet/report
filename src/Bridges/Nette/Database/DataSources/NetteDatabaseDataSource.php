<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Nette\Database\DriverException;
use Nette\Database\Helpers;
use Tlapnet\Report\DataSources\AbstractDatabaseConnectionDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Parameters\Parameters;
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
		} catch (DriverException $e) {
			throw new SqlException($query, NULL, $e);
		}

		$result = new LazyResultSet($resultset);

		return $result;
	}

}
