<?php

namespace Tlapnet\Report\Bridges\Dibi\Data\Fetcher;

use DibiConnection;
use Tlapnet\Report\Model\Data\Fetcher\FetcherFactory;

final class DibiFetcherFactory implements FetcherFactory
{

	/** @var DibiConnection */
	private $connection;

	/**
	 * @param DibiConnection $connection
	 */
	public function __construct(DibiConnection $connection)
	{
		$this->connection = $connection;
	}

	/**
	 * @param string $sql
	 * @return DibiFetcher
	 */
	public function create($sql)
	{
		return new DibiFetcher($sql, $this->connection);
	}

}
