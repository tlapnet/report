<?php

namespace Tests\Cases\Report;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Report\Report;
use Tlapnet\Report\Subreport\Subreport;

final class ReportTest extends BaseTestCase
{

	/**
	 * @covers Report::getRid
	 * @return void
	 */
	public function testDefault()
	{
		$r = new Report(NULL);
		$this->assertNull($r->getRid());
	}

	/**
	 * @covers Report::getRid
	 * @return void
	 */
	public function testGetters()
	{
		$r = new Report('r1');
		$this->assertEquals('r1', $r->getRid());
	}

	/**
	 * @covers Report::hasSubreport
	 * @covers Report::getSubreports
	 * @return void
	 */
	public function testSubreports()
	{
		$r = new Report('r1');
		$this->assertFalse($r->hasSubreport('s1'));
		$this->assertEquals([], $r->getSubreports());

		$r->addSubreport($s = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer()));
		$this->assertTrue($r->hasSubreport('s1'));
		$this->assertSame($s, $r->getSubreport('s1'));
		$this->assertEquals(['s1' => $s], $r->getSubreports());
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testSubreportsSuggestions()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Subreport "fod" not found, did you mean "foo"?');

		$r = new Report('r1');
		$r->addSubreport($s = new Subreport('foo', new Parameters(), new DevNullDataSource(), new DevNullRenderer()));
		$r->getSubreport('fod');
	}

	/**
	 * @covers Report::hasOption
	 * @covers Report::getOption
	 * @return void
	 */
	public function testOptions()
	{
		$r = new Report('r1');
		$this->assertFalse($r->hasOption('foobar'));

		$r->setOption('foobar', 1);
		$this->assertTrue($r->hasOption('foobar'));
		$this->assertEquals(1, $r->getOption('foobar'));

		$this->assertFalse($r->hasOption('bar'));
		$this->assertEquals('bar', $r->getOption('bar', 'bar'));
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testOptionsSuggestions()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown key "fod", did you mean "foo"?');

		$r = new Report('r1');
		$r->setOption('foo', 1);
		$r->getOption('fod');
	}

}
