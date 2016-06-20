<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;
use Tlapnet\Report\Tests\BaseTestCase;

final class TextParameterTest extends BaseTestCase
{

	public function testDefault()
	{
		$p = new TextParameter('foo');
		$this->assertEquals('foo', $p->getName());
		$this->assertEquals('text', $p->getType());
		$this->assertNull($p->getTitle());
		$this->assertNull($p->getValue());
	}

	public function testGettersSetters()
	{
		$p = new TextParameter('foo');
		$this->assertEquals('foo', $p->getName());
		$this->assertEquals('text', $p->getType());

		$p->setTitle('foo');
		$this->assertEquals('foo', $p->getTitle());

		$p->setValue('foo');
		$this->assertEquals('foo', $p->getValue());
	}

}
