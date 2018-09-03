<?php declare(strict_types = 1);

namespace Tlapnet\Report\Export;

abstract class AbstractExport implements Exportable
{

	/** @var mixed[] */
	protected $data = [];

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

}
