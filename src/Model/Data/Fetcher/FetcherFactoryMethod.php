<?php

namespace Tlapnet\Report\Model\Data\Fetcher;

interface FetcherFactoryMethod
{

	/**
	 * @param string $sql
	 * @return Fetcher
	 */
	public function create($sql);

}
