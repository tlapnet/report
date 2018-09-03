<?php declare(strict_types = 1);

namespace Tests\Cases\Parameters\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Parameters\Impl\Parameter;
use Tlapnet\Report\Parameters\Impl\TextParameter;

final class TextParameterTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$p = new TextParameter('foo');
		$this->assertEquals('foo', $p->getName());
		$this->assertEquals(Parameter::TYPE_TEXT, $p->getType());
		$this->assertNull($p->getTitle());
		$this->assertNull($p->getValue());
		$this->assertNull($p->getDefaultValue());
	}

	public function testGettersSetters(): void
	{
		$p = new TextParameter('foo1');
		$this->assertEquals('foo1', $p->getName());
		$this->assertEquals(Parameter::TYPE_TEXT, $p->getType());

		$p->setTitle('foo2');
		$this->assertEquals('foo2', $p->getTitle());

		$p->setValue('foo3');
		$this->assertEquals('foo3', $p->getValue());

		$p->setDefaultValue('foo4');
		$this->assertEquals('foo4', $p->getDefaultValue());
	}

}
