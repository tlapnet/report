<?php

namespace Tlapnet\Report\DataSources;

use PDO;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Result;

class PdoDataSource extends AbstractDatabaseConnectionDataSource
{

	/** @var PDO */
	protected $pdo;

	/**
	 * @return void
	 */
	protected function connect()
	{
		$this->pdo = new PDO(
            sprintf(
                '%s:host=%s;dbname=%s',
                $this->getConfig('driver'),
                $this->getConfig('host'),
                $this->getConfig('database')
            ),
			$this->getConfig('user'),
            $this->getConfig('password')
        );
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

		// Get SQL
		$sql = $this->getRealSql($parameters);

		try {
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

			// Execute native pdo query
			$statement = $this->pdo->prepare($sql);
			$statement->execute($args);
		} catch (\PDOException $e) {
			throw new SqlException($sql, NULL, $e);
		}

		$result = new Result($statement->fetchAll());

		return $result;
	}

}
