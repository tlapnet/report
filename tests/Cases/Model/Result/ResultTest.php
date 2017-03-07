<?php

namespace Tests\Cases\Model\Result;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Result\EditableResult;
use Tlapnet\Report\Model\Result\Result;

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
