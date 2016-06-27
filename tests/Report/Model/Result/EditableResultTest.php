<?php

namespace Tlapnet\Report\Tests\Model\Result;

use Tlapnet\Report\Model\Result\EditableResult;
use Tlapnet\Report\Tests\BaseTestCase;

final class EditableResultTest extends BaseTestCase
{

	public function testDefault()
	{
		$er = new EditableResult([]);
		$this->assertEquals([], $er->getData());
	}
	
	public function testSetData()
	{
		$er = new EditableResult([]);
		$er->setData(['foo', 'bar']);
		$this->assertEquals(['foo', 'bar'], $er->getData());
	}

}
