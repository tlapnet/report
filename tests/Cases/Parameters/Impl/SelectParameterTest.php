<?php declare(strict_types = 1);

namespace Tests\Cases\Parameters\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Parameters\Impl\Parameter;
use Tlapnet\Report\Parameters\Impl\SelectParameter;

final class SelectParameterTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$p = new SelectParameter('foo');
		$this->assertEquals('foo', $p->getName());
		$this->assertEquals(Parameter::TYPE_SELECT, $p->getType());
		$this->assertNull($p->getTitle());
		$this->assertNull($p->getValue());
		$this->assertNull($p->getDefaultValue());
	}

	public function testGettersSetters(): void
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

	public function testSetValue(): void
	{
		$p = new SelectParameter('foo1');
		$p->setItems([
			'foo' => 'bar',
			'foo2' => 'baz',
		]);

		$p->setUseKeys(true);
		$p->setValue('foo');
		$this->assertEquals('foo', $p->getValue());

		$p->setUseKeys(false);
		$p->setValue('bar');
		$this->assertEquals('bar', $p->getValue());
	}

	public function testSetValueException1(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Key "xxx" not found in array [foo|foo2] (useKeys:on)');

		$p = new SelectParameter('foo1');
		$p->setItems([
			'foo' => 'bar',
			'foo2' => 'bar2',
		]);

		$p->setUseKeys(true);
		$p->setValue('xxx');
	}

	public function testSetValueException2(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Value "xxx" not found in array [bar|bar2] (useKeys:off)');

		$p = new SelectParameter('foo1');
		$p->setItems([
			'foo' => 'bar',
			'foo2' => 'bar2',
		]);

		$p->setUseKeys(false);
		$p->setValue('xxx');
	}

}
