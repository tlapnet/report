<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Tlapnet\Report\DataSources\AbstractMultiDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Result\MultiResult;
use Tlapnet\Report\Model\Result\Result;

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
		// Create result
		$result = new MultiResult();

		foreach ($this->rows as $row) {
			// Get sql from row
			$sql = $row->sql;

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

			// Execute nette database query
			$resultset = $this->connection->queryArgs($sql, $args);

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
