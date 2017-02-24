<?php

namespace Tlapnet\Report\Model\Result;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Iterator;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Traversable;

class Result implements Countable, ArrayAccess, Resultable
{

	/** @var array */
	protected $data = [];

	/**
	 * @param array $data
	 */
	public function __construct($data = [])
	{
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * EDITABLE ****************************************************************
	 */

	/**
	 * @return EditableResult
	 */
	public function toEditable()
	{
		return new EditableResult($this->getData());
	}

	/**
	 * COUNTABLE ***************************************************************
	 */

	/**
	 * @return int
	 */
	public function count()
	{
		return count($this->data);
	}

	/**
	 * ARRAY ACCESS ************************************************************
	 */

	/**
	 * @param mixed $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->data[$offset]);
	}

	/**
	 * @param mixed $offset
	 * @return mixed
	 */
	public function offsetGet($offset)
	{
		if (!$this->offsetExists($offset)) {
			throw new InvalidArgumentException(sprintf('Key "%s" not found', $offset));
		}

		return $this->data[$offset];
	}

	/**
	 * @param mixed $offset
	 * @param mixed $value
	 * @return void
	 */
	public function offsetSet($offset, $value)
	{
		$this->data[$offset] = $value;
	}

	/**
	 * @param mixed $offset
	 * @return void
	 */
	public function offsetUnset($offset)
	{
		if (!$this->offsetExists($offset)) {
			throw new InvalidArgumentException(sprintf('Key "%s" not found', $offset));
		}

		unset($this->data[$offset]);
	}

	/**
	 * ITERATOR ****************************************************************
	 */

	/**
	 * @return Traversable|Iterator
	 */
	public function getIterator()
	{
		return new ArrayIterator((array) $this->data);
	}

}
