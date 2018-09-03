<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Iterator;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;

class Result implements Countable, ArrayAccess, Resultable, Editable
{

	/** @var mixed[] */
	protected $data = [];

	/**
	 * @param mixed[] $data
	 */
	public function __construct(array $data = [])
	{
		$this->data = $data;
	}

	/**
	 * @return mixed[]
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @return EditableResult
	 */
	public function toEditable(): Mutable
	{
		return new EditableResult($this->getData());
	}

	public function count(): int
	{
		return count($this->data);
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetExists($offset): bool
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
	 */
	public function offsetSet($offset, $value): void
	{
		$this->data[$offset] = $value;
	}

	/**
	 * @param mixed $offset
	 */
	public function offsetUnset($offset): void
	{
		if (!$this->offsetExists($offset)) {
			throw new InvalidArgumentException(sprintf('Key "%s" not found', $offset));
		}

		unset($this->data[$offset]);
	}

	/**
	 * @return ArrayIterator
	 */
	public function getIterator(): Iterator
	{
		return new ArrayIterator($this->data);
	}

}
