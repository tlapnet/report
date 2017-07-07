<?php

namespace Tests\Cases\Result;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Result\EditableResult;
use Tlapnet\Report\Result\Result;

final class ResultTest extends BaseTestCase
{

	/**
	 * @covers Result::getData
	 * @return void
	 */
	public function testDefault()
	{
		$er = new Result([]);
		$this->assertEquals([], $er->getData());
	}

	/**
	 * @covers Result::getData
	 * @return void
	 */
	public function testGetData()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		$this->assertEquals($data, $r->getData());
	}

	/**
	 * @covers Result::count
	 * @return void
	 */
	public function testCount()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		$this->assertEquals(count($data), $r->count());
		$this->assertEquals(count($data), count($r));
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testArrayAccess()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		// offsetExists
		for ($i = 0; $i < count($data); $i++) {
			$this->assertTrue(isset($r[$i]));
		}
		$this->assertFalse(isset($r['foo']));

		// offsetGet
		for ($i = 0; $i < count($data); $i++) {
			$this->assertEquals($data[$i], $r[$i]);
		}

		// offsetUnset
		unset($r[0]);
		$this->assertFalse(isset($r[0]));

		// offsetSet
		$r[0] = 1;
		$this->assertTrue(isset($r[0]));
		$this->assertEquals(1, $r[0]);
	}

	/**
	 * @covers Result::offsetGet
	 * @covers Result::offsetSet
	 * @covers Result::offsetExists
	 * @covers Result::offsetUnset
	 * @return void
	 */
	public function testArrayAccessExplicit()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		// offsetExists
		for ($i = 0; $i < count($data); $i++) {
			$this->assertTrue($r->offsetExists($i));
		}
		$this->assertFalse($r->offsetExists('foo'));

		// offsetGet
		for ($i = 0; $i < count($data); $i++) {
			$this->assertEquals($r->offsetGet($i), $r[$i]);
		}

		// offsetUnset
		$r->offsetUnset(0);
		$this->assertFalse($r->offsetExists(0));

		// offsetSet
		$r->offsetSet(0, 1);
		$this->assertTrue($r->offsetExists(0));
		$this->assertEquals(1, $r->offsetGet(0));
	}

	/**
	 * @covers Result::offsetGet
	 * @return void
	 */
	public function testOffsetGetException()
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetGet('fod');
	}

	/**
	 * @covers Result::offsetGet
	 * @return void
	 */
	public function testOffsetUnsetException()
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetUnset('fod');
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testArrayAccessException1()
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetGet('fod');
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testArrayAccessException2()
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetUnset('fod');
	}

	/**
	 * @covers Result::getIterator
	 * @return void
	 */
	public function testIterator()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);
		$i = $r->getIterator();

		$this->assertEquals(count($data), count($i));
	}

	/**
	 * @covers Result::toEditable
	 * @return void
	 */
	public function testToEditable()
	{
		$r = new Result(NULL);
		$er = $r->toEditable();

		$this->assertEquals(EditableResult::class, get_class($er));
	}

}
