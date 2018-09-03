<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Dibi\Fetcher;

use DibiConnection;
use Tlapnet\Report\Fetcher\Fetcher;
use Tlapnet\Report\Fetcher\FetcherFactory;

final class DibiFetcherFactory implements FetcherFactory
{

	/** @var DibiConnection */
	private $connection;

	public function __construct(DibiConnection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @return DibiFetcher
	 */
	public function create(string $sql): Fetcher
	{
		return new DibiFetcher($sql, $this->connection);
	}

}
