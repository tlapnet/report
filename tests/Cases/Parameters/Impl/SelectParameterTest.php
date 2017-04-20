<?php

namespace Tests\Cases\Parameters\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Parameters\Impl\Parameter;
use Tlapnet\Report\Parameters\Impl\SelectParameter;

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

		$p->setDefaultValue('foo4');
		$this->assertEquals('foo4', $p->getDefaultValue());
	}

	/**
	 * @covers SelectParameter::setValue
	 * @return void
	 */
	public function testSetValue()
	{
		$p = new SelectParameter('foo1');
		$p->setItems([
			'foo' => 'bar',
			'foo2' => 'baz',
		]);

		$p->setUseKeys(TRUE);
		$p->setValue('foo');
		$this->assertEquals('bar', $p->getValue());

		$p->setUseKeys(FALSE);
		$p->setValue('bar');
		$this->assertEquals('bar', $p->getValue());
	}

	/**
	 * @covers SelectParameter::setValue
	 * @return void
	 */
	public function testSetValueException1()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "xxx" not found in array [foo|foo2] (useKeys:on)');

		$p = new SelectParameter('foo1');
		$p->setItems([
			'foo' => 'bar',
			'foo2' => 'bar2',
		]);

		$p->setUseKeys(TRUE);
		$p->setValue('xxx');
	}

	/**
	 * @covers SelectParameter::setValue
	 * @return void
	 */
	public function testSetValueException2()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Value "xxx" not found in array [bar|bar2] (useKeys:off)');

		$p = new SelectParameter('foo1');
		$p->setItems([
			'foo' => 'bar',
			'foo2' => 'bar2',
		]);

		$p->setUseKeys(FALSE);
		$p->setValue('xxx');
	}

}
