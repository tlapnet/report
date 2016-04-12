<?php

namespace Tlapnet\Report\Model\Data;

use IteratorAggregate;
use Traversable;

interface Resultable extends IteratorAggregate
{

	/**
	 * @return array
	 */
	public function getData();

	/**
	 * @return Traversable
	 */
	public function getIterator();

}