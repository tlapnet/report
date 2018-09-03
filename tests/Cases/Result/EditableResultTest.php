<?php declare(strict_types = 1);

namespace Tests\Cases\Result;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Result\EditableResult;

final class EditableResultTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$er = new EditableResult([]);
		$this->assertEquals([], $er->getData());
	}

	public function testSetData(): void
	{
		$er = new EditableResult([]);
		$er->setData(['foo', 'bar']);
		$this->assertEquals(['foo', 'bar'], $er->getData());
	}

}
