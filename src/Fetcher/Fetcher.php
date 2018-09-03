<?php declare(strict_types = 1);

namespace Tlapnet\Report\Fetcher;

interface Fetcher
{

	/**
	 * @return mixed
	 */
	public function fetch();

	/**
	 * @param string|int|null $column
	 * @return mixed
	 */
	public function fetchSingle($column = 0);

	/**
	 * @return mixed
	 */
	public function fetchAll(?int $offset = null, ?int $limit = null);

	/**
	 * @return mixed
	 */
	public function fetchPairs(?string $key = null, ?string $value = null);

}
