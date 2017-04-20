<?php

namespace Tests\Cases\Result;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Result\EditableResult;

final class EditableResultTest extends BaseTestCase
{

	/**
	 * @covers EditableResult::getData
	 * @return void
	 */
	public function testDefault()
	{
		$er = new EditableResult([]);
		$this->assertEquals([], $er->getData());
	}

	/**
	 * @covers EditableResult::getData
	 * @return void
	 */
	public function testSetData()
	{
		$er = new EditableResult([]);
		$er->setData(['foo', 'bar']);
		$this->assertEquals(['foo', 'bar'], $er->getData());
	}

}
