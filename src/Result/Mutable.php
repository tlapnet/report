<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

interface Mutable extends Resultable
{

	/**
	 * @param mixed $data
	 */
	public function setData($data): void;

}
