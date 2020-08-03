<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Dibi\Fetcher;

use Dibi\Connection as DibiConnection;
use Dibi\Exception as DibiException;
use Dibi\Result as DibiResult;
use Dibi\Row as DibiRow;
use Tlapnet\Report\Fetcher\AbstractFetcher;

final class DibiFetcher extends AbstractFetcher
{

	/** @var DibiConnection */
	private $connection;

	public function __construct(string $sql, DibiConnection $connection)
	{
		parent::__construct($sql);
		$this->connection = $connection;
	}

	/** @return array|DibiRow|null */
	public function fetch()
	{
		return $this->doQuery()->fetch();
	}

	/**
	 * @param string|int|null $column
	 * @return mixed
	 */
	public function fetchSingle($column = null)
	{
		return $this->doQuery()->fetchSingle();
	}

	/**
	 * @return DibiRow[]|array[]
	 */
	public function fetchAll(?int $offset = null, ?int $limit = null): array
	{
		return $this->doQuery()->fetchAll($offset, $limit);
	}

	/**
	 * @return mixed[]
	 */
	public function fetchPairs(?string $key = null, ?string $value = null): array
	{
		return $this->doQuery()->fetchPairs($key, $value);
	}

	/**
	 * @throws DibiException
	 */
	protected function doQuery(): DibiResult
	{
		return $this->connection->nativeQuery($this->sql);
	}

}
