<?php

namespace Tests\Cases\Model\Parameters\Parameter;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Parameters\Impl\Parameter;
use Tlapnet\Report\Model\Parameters\Impl\SelectParameter;

final class SelectParameterTest extends BaseTestCase
{

	/**
	 * @covers SelectParameter::getName
	 * @covers SelectParameter::getType
	 * @covers SelectParameter::getTitle
	 * @covers SelectParameter::getValue
	 * @covers SelectParameter::getDefaultValue
	 * @return void
	 */
	public function testDefault()
	{
		$p = new SelectParameter('foo');
		$this->assertEquals('foo', $p->getName());
		$this->assertEquals(Parameter::TYPE_SELECT, $p->getType());
		$this->assertNull($p->getTitle());
		$this->assertNull($p->getValue());
		$this->assertNull($p->getDefaultValue());
	}

	/**
	 * @covers SelectParameter::getName
	 * @covers SelectParameter::getType
	 * @covers SelectParameter::setTitle
	 * @covers SelectParameter::setValue
	 * @covers SelectParameter::setDefaultValue
	 * @return void
	 */
	public function testGettersSetters()
	{
		$p = new SelectParameter('foo1');
		$p->setItems([
			'foo' => 'bar',
			'foo2' => 'baz',
		]);

		$this->assertEquals('foo1', $p->getName());
		$this->assertEquals(Parameter::TYPE_SELECT, $p->getType());

		$p->setTitle('foo2');
		$this->assertEquals('foo2', $p->getTitle());

		$p->setValue('foo');
		$this->assertEquals('foo', $p->getValue());

		$p->setDefaultValue('foo4');
		$this->assertEquals('foo4', $p->getDefaultValue());
	}

}
