<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Database\Fetcher;

use Nette\Database\Connection;
use Nette\Database\IRow;
use Nette\Database\ResultSet;
use Nette\Database\Row;
use Tlapnet\Report\Fetcher\AbstractFetcher;

final class NetteDatabaseFetcher extends AbstractFetcher
{

	/** @var Connection */
	private $connection;

	public function __construct(string $sql, Connection $connection)
	{
		parent::__construct($sql);
		$this->connection = $connection;
	}

	/**
	 * @return IRow|Row
	 */
	public function fetch(): IRow
	{
		return $this->doQuery()->fetch();
	}

	/**
	 * @param string|int|null $column
	 * @return mixed
	 */
	public function fetchSingle($column = 0)
	{
		return $this->doQuery()->fetchField($column);
	}

	/**
	 * @return IRow[]|Row[]
	 */
	public function fetchAll(?int $offset = null, ?int $limit = null): array
	{
		return $this->doQuery()->fetchAll();
	}

	/**
	 * @return mixed[]
	 */
	public function fetchPairs(?string $key = null, ?string $value = null): array
	{
		return $this->doQuery()->fetchPairs($key, $value);
	}

	protected function doQuery(): ResultSet
	{
		return $this->connection->query($this->sql);
	}

}
