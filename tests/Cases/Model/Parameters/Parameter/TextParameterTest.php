<?php

namespace Tests\Cases\Model\Parameters\Parameter;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Parameters\Impl\Parameter;
use Tlapnet\Report\Model\Parameters\Impl\TextParameter;

final class TextParameterTest extends BaseTestCase
{

	/**
	 * @covers TextParameter::getName
	 * @covers TextParameter::getType
	 * @covers TextParameter::getTitle
	 * @covers TextParameter::getValue
	 * @covers TextParameter::getDefaultValue
	 * @return void
	 */
	public function testDefault()
	{
		$p = new TextParameter('foo');
		$this->assertEquals('foo', $p->getName());
		$this->assertEquals(Parameter::TYPE_TEXT, $p->getType());
		$this->assertNull($p->getTitle());
		$this->assertNull($p->getValue());
		$this->assertNull($p->getDefaultValue());
	}

	/**
	 * @covers TextParameter::getName
	 * @covers TextParameter::getType
	 * @covers TextParameter::setTitle
	 * @covers TextParameter::setValue
	 * @covers TextParameter::setDefaultValue
	 * @return void
	 */
	public function testGettersSetters()
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
