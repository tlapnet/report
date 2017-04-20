<?php

namespace Tlapnet\Report\Fetcher;

interface FetcherFactoryMethod
{

	/**
	 * @param string $sql
	 * @return Fetcher
	 */
	public function create($sql);

}
