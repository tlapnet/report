<?php

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use DibiConnection;
use DibiException;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Model\Parameters\Parameters;

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
