<?php

namespace Tlapnet\Report\DataSources;

abstract class AbstractMultiDataSource implements DataSource
{

	/** @var array */
	protected $rows = [];

	/**
	 * @param string $title
	 * @param string $sql
	 * @return void
	 */
	public function addRow($title, $sql)
	{
		$this->rows[] = (object) [
			'title' => $title,
			'sql' => $sql,
		];
	}

}
