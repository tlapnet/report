<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Tlapnet\Report\DataSources\AbstractMultiDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Data\MultiResult;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Parameters\Parameters;

class MultiNetteDatabaseWrapperDataSource extends AbstractMultiDataSource
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
	 * COMPILING ***************************************************************
	 */

	/**
	 * @param Parameters $parameters
	 * @return Result
	 * @throws SqlException
	 */
	public function compile(Parameters $parameters)
	{
		$expander = $parameters->createExpander();
		$params = $parameters->toArray();

		// Create result
		$result = new MultiResult();

		foreach ($this->rows as $row) {
			// Get sql from row
			$sql = $row->sql;

			if ($this->isPure()) {
				// Expand parameters
				$sql = $expander->expand($sql);
				// Execute native query
				$resultset = $this->connection->query($sql);
			} else {
				// Execute nette database query
				$args = array_values($params);
				$resultset = $this->connection->queryArgs($sql, $args);
			}

			// Fetch single data
			$single = $resultset->fetchField();

			// Check data
			if ($single === FALSE) {
				throw new SqlException($sql);
			}

			$result->add(new Result([$row->title => $single]));
		}

		return $result;
	}

}
