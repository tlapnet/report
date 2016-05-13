<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\Model\Subreport\Parameter;
use Tlapnet\Report\Tests\BaseTestCase;

final class ParameterTest extends BaseTestCase
{

	public function testDefault()
	{
		$p = new Parameter();
		$this->assertNull($p->getName());
		$this->assertNull($p->getTitle());
		$this->assertNull($p->getType());
		$this->assertNull($p->getValue());
	}

	public function testGettersSetters()
	{
		$p = new Parameter();

		$p->setName('foo');
		$this->assertEquals('foo', $p->getName());

		$p->setTitle('foo');
		$this->assertEquals('foo', $p->getTitle());

		$p->setType('foo');
		$this->assertEquals('foo', $p->getType());

		$p->setValue('foo');
		$this->assertEquals('foo', $p->getValue());
	}

}
