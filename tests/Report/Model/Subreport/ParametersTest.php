<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Model\Subreport\Parameter;
use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Tests\BaseTestCase;
use Tlapnet\Report\Utils\Expander;

final class ParametersTest extends BaseTestCase
{

	public function testDefault()
	{
		$p = new Parameters();
		$this->assertEmpty($p->getAll());
		$this->assertEmpty($p->toArray());
	}

	public function testGet()
	{
		$a = new Parameter();
		$a->setName('foo');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$this->assertSame($a, $p->get('foo'));
	}

	public function testGetSuggestions()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Unknown parameter 'fod', did you mean 'foo'?");

		$a = new Parameter();
		$a->setName('foo');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$p->get('fod');
	}

	public function testAdd()
	{
		$a = new Parameter();
		$a->setName('foo');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$this->assertEquals(['foo' => $a], $p->getAll());
		$this->assertEquals(['foo' => 'bar'], $p->toArray());
	}

	public function testAttach()
	{
		$p = new Parameters();

		$this->expectException(NotImplementedException::class);

		$p->attach([]);
	}

	public function testCreateExpander()
	{
		$a = new Parameter();
		$a->setName('foo');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$expander = $p->createExpander();

		$this->assertEquals(Expander::class, get_class($expander));
		$this->assertEquals('bar', $expander->str('%foo%'));
	}

}
