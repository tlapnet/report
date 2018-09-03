<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use DibiConnection;
use DibiException;
use DibiResult;
use Tlapnet\Report\DataSources\AbstractDatabaseDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\Resultable;

class DibiWrapperDataSource extends AbstractDatabaseDataSource
{

	use TDibiDebugPanel;

	/** @var DibiConnection */
	protected $connection;

	public function __construct(DibiConnection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @return LazyDibiResult
	 * @throws SqlException
	 */
	public function compile(Parameters $parameters): Resultable
	{
		// Ensure connection
		if (!$this->connection->isConnected()) {
			$this->connection->connect();
		}

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
