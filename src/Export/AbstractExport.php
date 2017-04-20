<?php

namespace Tlapnet\Report\Export;

abstract class AbstractExport implements Exportable
{

	/** @var array */
	protected $data = [];

	/**
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

}
