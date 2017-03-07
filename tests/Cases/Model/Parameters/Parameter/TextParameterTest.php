<?php

namespace Tests\Cases\Model\Parameters\Parameter;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;

final class TextParameterTest extends BaseTestCase
{

	/**
	 * @covers TextParameter::getName
	 * @covers TextParameter::getType
	 * @covers TextParameter::getTitle
	 * @covers TextParameter::getValue
	 * @return void
	 */
	public function testDefault()
	{
		$p = new TextParameter('foo');
		$this->assertEquals('foo', $p->getName());
		$this->assertEquals('text', $p->getType());
		$this->assertNull($p->getTitle());
		$this->assertNull($p->getValue());
	}

	/**
	 * @covers TextParameter::getName
	 * @covers TextParameter::getType
	 * @covers TextParameter::setTitle
	 * @covers TextParameter::setValue
	 * @return void
	 */
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
