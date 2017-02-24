<?php

namespace Tlapnet\Report\Bridges\Dibi\Data\Fetcher;

use DibiConnection;
use DibiException;
use DibiResult;
use DibiRow;
use Tlapnet\Report\Model\Data\Fetcher\AbstractFetcher;

final class DibiFetcher extends AbstractFetcher
{

	/** @var DibiConnection */
	private $connection;

	/**
	 * @param string $sql
	 * @param DibiConnection $connection
	 */
	public function __construct($sql, DibiConnection $connection)
	{
		parent::__construct($sql);
		$this->connection = $connection;
	}

	/**
	 * @return DibiRow
	 */
	public function fetch()
	{
		return $this->doQuery()->fetch();
	}

	/**
	 * @param string $column
	 * @return mixed
	 */
	public function fetchSingle($column = NULL)
	{
		return $this->doQuery()->fetchSingle();
	}

	/**
	 * @param int $offset
	 * @param int $limit
	 * @return DibiRow[]
	 */
	public function fetchAll($offset = NULL, $limit = NULL)
	{
		return $this->doQuery()->fetchAll($offset, $limit);
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
	 * @return DibiResult
	 * @throws DibiException
	 */
	protected function doQuery()
	{
		return $this->connection->nativeQuery($this->sql);
	}

}
