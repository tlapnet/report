<?php declare(strict_types = 1);

namespace Tests\Cases\Result;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Result\MultiResult;
use Tlapnet\Report\Result\Result;

final class MultiResultTest extends BaseTestCase
{

	public function testAdd(): void
	{
		$r1 = new Result([1]);
		$r2 = new Result([2]);

		$multi = new MultiResult();
		$multi->add($r1);
		$multi->add($r2);

		$this->assertCount(2, $multi->getIterator());

		$data = [];
		$iterator = $multi->getIterator();
		while ($iterator->valid()) {
			$data[] = $iterator->current();
			$iterator->next();
		}

		$this->assertEquals([1, 2], $data);
	}

	public function testGetData(): void
	{
		$r1 = new Result([1]);
		$r2 = new Result([2]);

		$multi = new MultiResult();
		$multi->add($r1);
		$multi->add($r2);

		$this->assertCount(2, $multi->getData());
		$this->assertSame($r1, $multi->getData()[0]);
		$this->assertSame($r2, $multi->getData()[1]);
	}

	public function testToEditable(): void
	{
		$r1 = new Result([1 => 1]);
		$r2 = new Result([2 => 2]);

		$multi = new MultiResult();
		$multi->add($r1);
		$multi->add($r2);

		$editable = $multi->toEditable();

		$this->assertCount(2, $editable->getData());
		$this->assertEquals(current($r1->getData()), $editable->getData()[1]);
		$this->assertEquals(current($r2->getData()), $editable->getData()[2]);
	}

}
