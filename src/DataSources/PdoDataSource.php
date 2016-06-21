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
	 * @param string $query
	 * @return Result
	 * @throws SqlException
	 */
	public function doCompile(Parameters $parameters, $query)
	{
		// Connect to DB
		if (!$this->pdo) $this->connect();

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
