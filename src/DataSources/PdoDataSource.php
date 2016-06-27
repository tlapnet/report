<?php

namespace Tlapnet\Report\DataSources;

use PDO;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Parameters\Parameters;

class PdoDataSource extends AbstractDatabaseConnectionDataSource
{

	/** @var PDO */
	protected $pdo;

	/**
	 * @return void
	 */
	protected function connect()
	{
		$this->pdo = new PDO(sprintf(
			'%s:host=%s;dbname=%s',
			$this->getConfig('driver'),
			$this->getConfig('host'),
			$this->getConfig('database')
		),
			$this->getConfig('user'),
			$this->getConfig('password'));
	}

	/**
	 * @param Parameters $parameters
	 * @return Result
	 * @throws SqlException
	 */
	public function compile(Parameters $parameters)
	{
		// Connect to DB
		if (!$this->pdo) $this->connect();

		// Expand parameters
		$expander = $parameters->createExpander();

		// Get SQL
		$sql = $this->getRealSql($parameters);

		// Replace placeholders
		$query = $expander->expand($sql);

		try {
			$statement = $this->pdo->prepare($query);
			$statement->execute();
		} catch (\PDOException $e) {
			throw new SqlException($query, NULL, $e);
		}

		$result = new Result($statement->fetchAll());

		return $result;
	}

}
