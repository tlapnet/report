<?php

namespace Tlapnet\Report\Tests\Model\Group;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\Tests\BaseTestCase;

final class GroupTest extends BaseTestCase
{

	/**
	 * @covers Group::getGid
	 * @covers Group::getName
	 * @return void
	 */
	public function testGetters()
	{
		$g = new Group('g1', 'foobar');
		$this->assertEquals('g1', $g->getGid());
		$this->assertEquals('foobar', $g->getName());
	}

	/**
	 * @covers Group::getReports
	 * @return void
	 */
	public function testEmpty()
	{
		$g = new Group('g1', 'foobar');
		$this->assertEmpty($g->getReports());
	}

	/**
	 * @covers Group::hasReport
	 * @return void
	 */
	public function testHasReport1()
	{
		$g = new Group('g1', 'foobar');
		$this->assertFalse($g->hasReport('foobar'));
	}

	/**
	 * @covers Group::hasReport
	 * @return void
	 */
	public function testHasReport2()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport(new Report('r1'));
		$this->assertTrue($g->hasReport('r1'));
	}

	/**
	 * @covers Group::getReport
	 * @return void
	 */
	public function testGetReport()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertSame($r, $g->getReport('r1'));
	}

	/**
	 * @coversNothing
	 * @return void
	 */
	public function testGetReportSuggestions()
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage("Report 'fod' not found, did you mean 'foo'?");

		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('foo'));
		$g->getReport('fod');
	}

	/**
	 * @covers Group::getReports
	 * @return void
	 */
	public function testGetReports1()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertEquals(['r1' => $r], $g->getReports());
	}

	/**
	 * @covers Group::getReports
	 * @return void
	 */
	public function testGetReports2()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertEquals(['r1' => $r], $g->getReports());
		$g->addReport($r2 = new Report('r1'));
		$this->assertEquals(['r1' => $r2], $g->getReports());
	}

}
