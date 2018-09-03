<?php declare(strict_types = 1);

namespace Tlapnet\Report\DataSources;

abstract class AbstractMultiDataSource implements DataSource
{

	/** @var object[] */
	protected $rows = [];

	public function addRow(string $title, string $sql): void
	{
		$this->rows[] = (object) [
			'title' => $title,
			'sql' => $sql,
		];
	}

}
