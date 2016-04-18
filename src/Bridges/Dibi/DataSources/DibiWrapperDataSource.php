<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use DibiConnection;
use DibiException;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Subreport\Parameters;

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

		// Expand parameters
		$expander = $parameters->createExpander();
		$sql = $this->getSql();

		// Replace placeholders
		$query = $expander->expand($sql);

		try {
			// Execute query
			$resulset = $this->connection->nativeQuery($query);
		} catch (DibiException $e) {
			throw new SqlException($query, NULL, $e);
		}

		$report = new Result($resulset->fetchAll());

		return $report;
	}

}
