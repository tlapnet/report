<?php declare(strict_types = 1);

namespace Tests\Cases\Result;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Result\EditableResult;
use Tlapnet\Report\Result\Result;

final class ResultTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$er = new Result([]);
		$this->assertEquals([], $er->getData());
	}

	public function testGetData(): void
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		$this->assertEquals($data, $r->getData());
	}

	public function testCount(): void
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		$this->assertEquals(count($data), $r->count());
		$this->assertEquals(count($data), count($r));
	}

	/**
	 * @coversNothing
	 */
	public function testArrayAccess(): void
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		// offsetExists
		foreach ($r as $i => $iValue) {
			$this->assertTrue(isset($r[$i]));
		}
		$this->assertFalse(isset($r['foo']));

		// offsetGet
		for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
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

	public function testArrayAccessExplicit(): void
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);

		// offsetExists
		for ($i = 0, $iMax = count($data); $i < $iMax; $i++) {
			$this->assertTrue($r->offsetExists($i));
		}
		$this->assertFalse($r->offsetExists('foo'));

		// offsetGet
		foreach ($r as $i => $iValue) {
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

	public function testOffsetGetException(): void
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetGet('fod');
	}

	public function testOffsetUnsetException(): void
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetUnset('fod');
	}

	/**
	 * @coversNothing
	 */
	public function testArrayAccessException1(): void
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetGet('fod');
	}

	/**
	 * @coversNothing
	 */
	public function testArrayAccessException2(): void
	{
		$r = new Result([]);

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "fod" not found');

		$r->offsetUnset('fod');
	}

	public function testIterator(): void
	{
		$data = [1, 2, 3, 4, 5];
		$r = new Result($data);
		$i = $r->getIterator();

		$this->assertCount(count($data), $i);
	}

	public function testToEditable(): void
	{
		$r = new Result([]);
		$er = $r->toEditable();

		$this->assertEquals(EditableResult::class, get_class($er));
	}

}
