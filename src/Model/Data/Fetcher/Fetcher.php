<?php

namespace Tlapnet\Report\Model\Data\Fetcher;

interface Fetcher
{

	/**
	 * @return mixed
	 */
	public function fetch();

	/**
	 * @param string $column
	 * @return mixed
	 */
	public function fetchSingle($column = NULL);

	/**
	 * @param int $offset
	 * @param int $limit
	 * @return mixed
	 */
	public function fetchAll($offset = NULL, $limit = NULL);

	/**
	 * @param string $key
	 * @param string $value
	 * @return mixed
	 */
	public function fetchPairs($key = NULL, $value = NULL);

}
