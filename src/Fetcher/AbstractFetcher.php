<?php declare(strict_types = 1);

namespace Tlapnet\Report\Fetcher;

abstract class AbstractFetcher implements Fetcher
{

	/** @var string */
	protected $sql;

	public function __construct(string $sql)
	{
		$this->sql = $sql;
	}

	public function getSql(): string
	{
		return $this->sql;
	}

}
