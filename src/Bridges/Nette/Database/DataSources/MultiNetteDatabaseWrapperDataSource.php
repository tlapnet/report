<?php

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Nette\Database\DriverException;
use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Data\MultiResult;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Subreport\Parameters;

class MultiNetteDatabaseWrapperDataSource implements DataSource
{

	/** @var Connection */
	protected $connection;

	/** @var array */
	protected $rows = [];

	/**
	 * @param Connection $connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @param string $sql
	 */
	public function addRow($title, $sql)
	{
		$this->rows[] = (object)[
			'title' => $title,
			'sql' => $sql,
		];
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
		// Expand parameters
		$expander = $parameters->createExpander();

		// Create result
		$result = new MultiResult();

		foreach ($this->rows as $row) {
			// Replace placeholders
			$query = $expander->expand($row->sql);

			try {
				// Execute query
				$resultset = $this->connection->query($query);
			} catch (DriverException $e) {
				throw new SqlException($query, NULL, $e);
			}

			$single = $resultset->fetchField();

			// Check data
			if ($single === FALSE) {
				throw new SqlException($query);
			}

			$result->add(new Result([$row->title => $single]));
		}

		return $result;
	}

}
