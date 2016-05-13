<?php

namespace Tlapnet\Report\Tests\Model\Group;

use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\Tests\BaseTestCase;

final class GroupTest extends BaseTestCase
{

	public function testGetters()
	{
		$g = new Group('g1', 'foobar');
		$this->assertEquals('g1', $g->getGid());
		$this->assertEquals('foobar', $g->getName());
	}

	public function testEmpty()
	{
		$g = new Group('g1', 'foobar');
		$this->assertEmpty($g->getReports());
	}

	public function testHasReport1()
	{
		$g = new Group('g1', 'foobar');
		$this->assertFalse($g->hasReport('foobar'));
	}

	public function testHasReport2()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport(new Report('r1'));
		$this->assertTrue($g->hasReport('r1'));
	}

	public function testGetReport()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertSame($r, $g->getReport('r1'));
	}

	public function testGetReports1()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertEquals(['r1' => $r], $g->getReports());
	}

	public function testGetReports2()
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertEquals(['r1' => $r], $g->getReports());
		$g->addReport($r2 = new Report('r1'));
		$this->assertEquals(['r1' => $r2], $g->getReports());
	}

}
