<?php

namespace Tlapnet\Report\DataSources;

use PDO;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Subreport\Parameters;

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
		$sql = $this->getSql();

		try {
			$statement = $this->pdo->prepare($sql);
			$statement->execute();
		} catch (\PDOException $e) {
			throw new SqlException($sql, NULL, $e);
		}

		$report = new Result($statement->fetchAll());

		return $report;
	}

}
