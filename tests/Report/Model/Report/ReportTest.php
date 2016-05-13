<?php

namespace Tlapnet\Report\Tests\Model\Report;

use Tlapnet\Report\DataSources\ArrayDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Model\Subreport\Subreport;
use Tlapnet\Report\Renderers\TableRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class ReportTest extends BaseTestCase
{

	public function testGetters()
	{
		$r = new Report('r1');
		$this->assertEquals('r1', $r->getRid());
	}

	public function testSubreports()
	{
		$r = new Report('r1');
		$this->assertFalse($r->hasSubreport('s1'));
		$this->assertEquals([], $r->getSubreports());

		$r->addSubreport($s = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new TableRenderer()));
		$this->assertTrue($r->hasSubreport('s1'));
		$this->assertSame($s, $r->getSubreport('s1'));
		$this->assertEquals(['s1' => $s], $r->getSubreports());
	}

	public function testSubreportsSuggestions()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Subreport 'fod' not found, did you mean 'foo'?");

		$r = new Report('r1');
		$r->addSubreport($s = new Subreport('foo', new Parameters(), new ArrayDataSource([]), new TableRenderer()));
		$r->getSubreport('fod');
	}

	public function testOptions()
	{
		$r = new Report('r1');
		$this->assertFalse($r->hasOption('foobar'));

		$r->setOption('foobar', 1);
		$this->assertTrue($r->hasOption('foobar'));
		$this->assertEquals(1, $r->getOption('foobar'));
	}

	public function testOptionsSuggestions()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Unknown key 'fod', did you mean 'foo'?");

		$r = new Report('r1');
		$r->setOption('foo', 1);
		$r->getOption('fod');
	}

}
