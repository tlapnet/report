<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Database\Fetcher;

use Nette\Database\Connection;
use Tlapnet\Report\Fetcher\Fetcher;
use Tlapnet\Report\Fetcher\FetcherFactory;

final class NetteDatabaseFetcherFactory implements FetcherFactory
{

	/** @var Connection */
	private $connection;

	public function __construct(Connection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @return NetteDatabaseFetcher
	 */
	public function create(string $sql): Fetcher
	{
		return new NetteDatabaseFetcher($sql, $this->connection);
	}

}
