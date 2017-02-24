<?php

namespace Tlapnet\Report\Model\Result;

use Iterator;
use IteratorAggregate;
use Traversable;

interface Resultable extends IteratorAggregate
{

	/**
	 * @return array
	 */
	public function getData();

	/**
	 * @return Traversable|Iterator
	 */
	public function getIterator();

}
