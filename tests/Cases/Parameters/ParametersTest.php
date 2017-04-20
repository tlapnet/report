<?php

namespace Tests\Cases\Parameters;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Parameters\Impl\TextParameter;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Utils\Expander;

final class ParametersTest extends BaseTestCase
{

	/**
	 * @covers Parameters::getAll
	 * @covers Parameters::toArray
	 * @return void
	 */
	public function testDefault()
	{
		$p = new Parameters();
		$this->assertEmpty($p->getAll());
		$this->assertEmpty($p->toArray());
	}

	/**
	 * @covers Parameters::get
	 * @return void
	 */
	public function testGet()
	{
		$a = new TextParameter('foo');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$this->assertSame($a, $p->get('foo'));
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testGetSuggestions()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown parameter "fod", did you mean "foo"?');

		$a = new TextParameter('foo');
		$a->setValue('bar');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$p->get('fod');
	}

	/**
	 * @covers Parameters::add
	 * @return void
	 */
	public function testAdd()
	{
		$a = new TextParameter('foo');
		$a->setValue('bar');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$this->assertEquals(['foo' => $a], $p->getAll());
		$this->assertEquals(['foo' => 'bar'], $p->toArray());
	}

	/**
	 * @covers Parameters::attach
	 * @return void
	 */
	public function testAttach()
	{
		$p1 = new TextParameter('foobar');
		$p = new Parameters();
		$p->add($p1);

		$this->assertNull($p1->getValue());
		$this->assertEmpty($p->toArray());

		$p->attach(['foobar' => 100]);

		$this->assertEquals(100, $p1->getValue());
		$this->assertEquals(['foobar' => 100], $p->toArray());
	}

	/**
	 * @covers Parameters::add
	 * @return void
	 */
	public function testCreateExpander()
	{
		$a = new TextParameter('foo');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$expander = $p->createExpander();

		$this->assertEquals(Expander::class, get_class($expander));
		$this->assertEquals('bar', $expander->doSingle('{foo}'));
	}

}
