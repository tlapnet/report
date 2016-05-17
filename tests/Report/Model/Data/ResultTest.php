<?php

namespace Tlapnet\Report\Tests\Model\Data;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Data\EditableResult;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Tests\BaseTestCase;

final class ResultTest extends BaseTestCase
{

	public function testDefault()
	{
		$er = new Result([]);
		$this->assertEquals([], $er->getData());
	}

	public function testGetData()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		$this->assertEquals($data, $r->getData());
	}

	public function testCount()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		$this->assertEquals(count($data), $r->count());
		$this->assertEquals(count($data), count($r));
	}

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

	public function testArrayAccessException1()
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Key 'fod' not found");

		$r->offsetGet('fod');
	}

	public function testArrayAccessException2()
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Key 'fod' not found");

		$r->offsetUnset('fod');
	}

	public function testIterator()
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);
		$i = $r->getIterator();

		$this->assertEquals(count($data), count($i));
	}


	public function testToEditable()
	{
		$r = new Result(NULL);
		$er = $r->toEditable();

		$this->assertEquals(EditableResult::class, get_class($er));
	}

}
