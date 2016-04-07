<?php

namespace Tlapnet\Report\DataSources;

use PDO;
use Tlapnet\Report\Model\Box\ParameterList;
use Tlapnet\Report\Model\Data\Report;

class PdoDataSource extends AbstractDatabaseDataSource
{


	/**
	 * @param ParameterList $parameters
	 * @return Report
	 */
	public function compile(ParameterList $parameters)
	{
		$pdo = new PDO(sprintf(
			'%s:host=%s;dbname=%s',
			$this->getConfig('driver'),
			$this->getConfig('host'),
			$this->getConfig('database')
		),
			$this->getConfig('user'),
			$this->getConfig('password'));

		$sql = $this->getSql();

		$statement = $pdo->prepare($sql);
		$statement->execute();

		$report = new Report($statement->fetchAll());

		return $report;
	}

}
