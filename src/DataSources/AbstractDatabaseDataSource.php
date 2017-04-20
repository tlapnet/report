<?php

namespace Tlapnet\Report\DataSources;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Parameters;

abstract class AbstractDatabaseDataSource implements DataSource
{

	/** @var string */
	protected $sql;

	/** @var string */
	protected $defaultSql;

	/** @var bool */
	protected $pure = TRUE;

	/**
	 * @return string
	 */
	public function getSql()
	{
		return $this->sql;
	}

	/**
	 * @param mixed $sql
	 * @return void
	 */
	public function setSql($sql)
	{
		$this->sql = (string) $sql;
	}

	/**
	 * @return string
	 */
	public function getDefaultSql()
	{
		return $this->defaultSql;
	}

	/**
	 * @param mixed $sql
	 * @return void
	 */
	public function setDefaultSql($sql)
	{
		$this->defaultSql = (string) $sql;
	}

	/**
	 * @param Parameters $parameters
	 * @return string
	 */
	public function getRealSql(Parameters $parameters)
	{
		// If dynamic parameters are not filled
		// and also we've default sql query
		if (!$parameters->isAttached() && $this->getDefaultSql()) {
			$sql = $this->getDefaultSql();
		} else {
			$sql = $this->getSql();
		}

		// Control check - we need any sql query!
		if (empty($sql)) {
			throw new InvalidStateException('You have to fill sql query. Via setSql() or setDefaultSql().');
		}

		return $sql;
	}

}
