<?php declare(strict_types = 1);

namespace Tests\Cases\Parameters;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Parameters\Impl\TextParameter;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Utils\Expander;

final class ParametersTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$p = new Parameters();
		$this->assertEmpty($p->getAll());
		$this->assertEmpty($p->toArray());
	}

	public function testGet(): void
	{
		$a = new TextParameter('foo');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$this->assertSame($a, $p->get('foo'));
	}

	/**
	 * @coversNothing
	 */
	public function testGetSuggestions(): void
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

	public function testAdd(): void
	{
		$a = new TextParameter('foo');
		$a->setValue('bar');
		$a->setValue('bar');

		$p = new Parameters();
		$p->add($a);

		$this->assertEquals(['foo' => $a], $p->getAll());
		$this->assertEquals(['foo' => 'bar'], $p->toArray());
	}

	public function testAttach(): void
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

	public function testCreateExpander(): void
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
