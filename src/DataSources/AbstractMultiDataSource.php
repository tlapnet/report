<?php

namespace Tlapnet\Report\DataSources;

abstract class AbstractMultiDataSource implements DataSource
{

	/** @var array */
	protected $rows = [];

	/** @var bool */
	protected $pure = TRUE;

	/**
	 * @param string $sql
	 */
	public function addRow($title, $sql)
	{
		$this->rows[] = (object)[
			'title' => $title,
			'sql' => $sql,
		];
	}

	/**
	 * @return boolean
	 */
	public function isPure()
	{
		return $this->pure;
	}

	/**
	 * @param boolean $pure
	 */
	public function setPure($pure)
	{
		$this->pure = (bool)$pure;
	}

}
