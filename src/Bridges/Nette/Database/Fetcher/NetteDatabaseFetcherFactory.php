<?php

namespace Tlapnet\Report\Bridges\Nette\Database\Fetcher;

use Nette\Database\Connection;
use Tlapnet\Report\Model\Data\Fetcher\FetcherFactory;

final class NetteDatabaseFetcherFactory implements FetcherFactory
{

	/** @var Connection */
	private $connection;

	/**
	 * @param Connection $connection
	 */
	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @param string $sql
	 * @return NetteDatabaseFetcher
	 */
	public function create($sql)
	{
		return new NetteDatabaseFetcher($sql, $this->connection);
	}

}
