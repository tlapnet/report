<?php

namespace Tlapnet\Report\Model\Data\Fetcher;

interface FetcherFactory
{

	/**
	 * @param string $sql
	 * @return Fetcher
	 */
	public function create($sql);

}
