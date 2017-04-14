<?php

namespace Tests\Cases\Model\Service;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\Model\Service\ReportService;
use Tlapnet\Report\ReportManager;

final class ReportServiceTest extends BaseTestCase
{

	/**
	 * @covers ReportService::getGroups
	 * @covers ReportService::getGroupless
	 * @return void
	 */
	public function testDefault()
	{
		$m = new ReportManager();
		$s = new ReportService($m);

		$this->assertCount(0, $s->getGroups());
		$this->assertCount(0, $s->getGroupless());
	}

	/**
	 * @covers ReportService::getGroups
	 * @return void
	 */
	public function testGetGroups()
	{
		$m = new ReportManager();
		$m->addGroup($g = new Group('g', 'Group'));
		$g->addReport($r = new Report('r1'));

		$s = new ReportService($m);

		$this->assertCount(1, $s->getGroups());
		$this->assertEquals(['g' => $g], $s->getGroups());
	}

	/**
	 * @covers ReportService::getGroupless
	 * @return void
	 */
	public function testGetGrouples()
	{
		$m = new ReportManager();
		$m->addGroupless($r = new Report('r1'));

		$s = new ReportService($m);

		$this->assertCount(1, $s->getGroupless());
		$this->assertEquals(['r1' => $r], $s->getGroupless());
	}

	/**
	 * @covers ReportService::getReport
	 * @return void
	 */
	public function testGetReport()
	{
		$m = new ReportManager();
		$m->addGroup($g = new Group('g', 'Group'));
		$g->addReport($r1 = new Report('r1'));
		$g->addReport($r2 = new Report('r2'));

		$s = new ReportService($m);
		$this->assertSame($r1, $s->getReport('r1'));
		$this->assertSame($r2, $s->getReport('r2'));
		$this->assertNull($s->getReport('r3'));
	}

}
