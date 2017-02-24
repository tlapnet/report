<?php

namespace Tlapnet\Report\Bridges\Nette\Database\Fetcher;

use Nette\Database\Connection;
use Nette\Database\IRow;
use Nette\Database\ResultSet;
use Nette\Database\Row;
use Tlapnet\Report\Model\Data\Fetcher\AbstractFetcher;

final class NetteDatabaseFetcher extends AbstractFetcher
{

	/** @var Connection */
	private $connection;

	/**
	 * @param string $sql
	 * @param Connection $connection
	 */
	public function __construct($sql, Connection $connection)
	{
		parent::__construct($sql);
		$this->connection = $connection;
	}

	/**
	 * @return IRow|Row
	 */
	public function fetch()
	{
		return $this->doQuery()->fetch();
	}

	/**
	 * @param string|int $column
	 * @return mixed
	 */
	public function fetchSingle($column = 0)
	{
		return $this->doQuery()->fetchField($column);
	}

	/**
	 * @param int $offset
	 * @param int $limit
	 * @return IRow[]|Row[]
	 */
	public function fetchAll($offset = NULL, $limit = NULL)
	{
		return $this->doQuery()->fetchAll();
	}

	/**
	 * @param string $key
	 * @param string $value
	 * @return array
	 */
	public function fetchPairs($key = NULL, $value = NULL)
	{
		return $this->doQuery()->fetchPairs($key, $value);
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @return ResultSet
	 */
	protected function doQuery()
	{
		return $this->connection->query($this->sql);
	}

}
