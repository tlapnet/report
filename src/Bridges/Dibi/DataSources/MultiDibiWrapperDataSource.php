<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Dibi\DataSources;

use Dibi\Connection as DibiConnection;
use Dibi\Result as DibiResult;
use Tlapnet\Report\DataSources\AbstractMultiDataSource;
use Tlapnet\Report\Exceptions\Runtime\DataSource\SqlException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Result\MultiResult;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;

class MultiDibiWrapperDataSource extends AbstractMultiDataSource
{

	use TDibiDebugPanel;

	/** @var DibiConnection */
	protected $connection;

	public function __construct(DibiConnection $connection)
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
			/** @var DibiResult $resultset */
			$resultset = $this->connection->query($sql, $args);

			// Fetch single data
			$single = $resultset->fetchSingle();

			// Check data
			if ($single === null) {
				throw new SqlException($sql);
			}

			$result->add(new Result([$row->title => $single]));
		}

		return $result;
	}

}
