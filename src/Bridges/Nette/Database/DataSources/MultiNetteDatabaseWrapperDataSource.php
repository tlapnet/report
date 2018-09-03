<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Database\DataSources;

use Nette\Database\Connection;
use Tlapnet\Report\DataSources\AbstractMultiDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\MultiResult;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;

class MultiNetteDatabaseWrapperDataSource extends AbstractMultiDataSource
{

	use TNetteDatabaseDebugPanel;

	/** @var Connection */
	protected $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @return Result
	 * @throws SqlException
	 */
	public function compile(Parameters $parameters): Resultable
	{
		// Debug panel
		if ($this->tracyPanel) $this->createDebugPanel($this->connection);

		// Create result
		$result = new MultiResult();

		foreach ($this->rows as $row) {
			// Get sql from row
			$sql = $row->sql;

			// Prepare parameters
			if ($parameters->canSwitch()) {
				$switch = $parameters->createSwitcher();
				$switch->setPlaceholder('?');
				// Replace named parameters for ? and return
				// accurate sequenced array of arguments
				 [$sql, $args] = $switch->execute($sql);
			} else {
				// Keep empty arguments
				$args = [];
			}

			// Execute nette database query
			$resultset = $this->connection->queryArgs($sql, $args);

			// Fetch single data
			$single = $resultset->fetchField();

			// Check data
			if ($single === false) {
				throw new SqlException($sql);
			}

			$result->add(new Result([$row->title => $single]));
		}

		return $result;
	}

}
