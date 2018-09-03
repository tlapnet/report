<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

use Iterator;
use IteratorAggregate;

interface Resultable extends IteratorAggregate
{

	/**
	 * @return mixed[]
	 */
	public function getData(): array;

	public function getIterator(): Iterator;

}
