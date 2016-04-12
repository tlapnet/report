<?php

namespace Tlapnet\Report\DataSources;

abstract class AbstractDatabaseDataSource implements DataSource
{

	/** @var mixed */
	protected $sql;

	/**
	 * @return mixed
	 */
	public function getSql()
	{
		return $this->sql;
	}

	/**
	 * @param mixed $sql
	 */
	public function setSql($sql)
	{
		$this->sql = $sql;
	}

}
