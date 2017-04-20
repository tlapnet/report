<?php

namespace Tlapnet\Report\Fetcher;

interface FetcherFactory
{

	/**
	 * @param string $sql
	 * @return Fetcher
	 */
	public function create($sql);

}
