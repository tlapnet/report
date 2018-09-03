<?php declare(strict_types = 1);

namespace Tlapnet\Report\Fetcher;

interface FetcherFactory
{

	public function create(string $sql): Fetcher;

}
