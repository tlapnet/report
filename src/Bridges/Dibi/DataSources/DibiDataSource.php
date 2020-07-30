<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use Dibi\Connection as DibiConnection;
use Dibi\Exception as DibiException;
use Dibi\Result as DibiResult;
use Tlapnet\Report\DataSources\AbstractDatabaseConnectionDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Resultable;

class DibiDataSource extends AbstractDatabaseConnectionDataSource
{

	use TDibiDebugPanel;

	/** @var DibiConnection */
	protected $connection;

	protected function connect(): void
	{
		$this->connection = new DibiConnection([
			'driver' => $this->getConfig('driver'),
			'host' => $this->getConfig('host'),
			'username' => $this->getConfig('user'),
			'password' => $this->getConfig('password'),
		]);
	}

	/**
	 * @return LazyDibiResult
	 * @throws SqlException
	 */
	public function compile(Parameters $parameters): Resultable
	{
		// Connect to DB
		if (!$this->connection) $this->connect();

		// Debug panel
		if ($this->tracyPanel) $this->createDebugPanel($this->connection);

		// Get SQL
		$sql = $this->getRealSql($parameters);

		try {
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

			// Execute dibi query
			$query = array_merge([$sql], $args);
			/** @var DibiResult $resultset */
			$resultset = $this->connection->query($query);
		} catch (DibiException $e) {
			throw new SqlException($sql, 0, $e);
		}

		return new LazyDibiResult($resultset);
	}

}
