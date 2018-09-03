<?php declare(strict_types = 1);

namespace Tlapnet\Report\Subreport;

interface Attachable
{

	/**
	 * @param mixed[] $data
	 */
	public function attach(array $data): void;

}
