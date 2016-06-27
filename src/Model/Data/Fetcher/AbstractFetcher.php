<?php

namespace Tlapnet\Report\Model\Data\Fetcher;

abstract class AbstractFetcher implements Fetcher
{

	/** @var string */
	protected $sql;

	/**
	 * @param string $sql
	 */
	public function __construct($sql)
	{
		$this->sql = $sql;
	}

	/**
	 * @return string
	 */
	public function getSql()
	{
		return $this->sql;
	}

}
