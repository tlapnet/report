<?php

namespace Tlapnet\Report\Tests\Model;

use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\Model\ReportService;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\ReportManager;
use Tlapnet\Report\Tests\BaseTestCase;

final class ReportServiceTest extends BaseTestCase
{

	public function testDefault()
	{
		$m = new ReportManager();
		$s = new ReportService($m);

		$this->assertCount(0, $s->getGroups());
		$this->assertCount(0, $s->getGroupless());
	}

	public function testGetGroups()
	{
		$m = new ReportManager();
		$m->addGroup($g = new Group('g', 'Group'));
		$g->addReport($r = new Report('r1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer()));

		$s = new ReportService($m);

		$this->assertCount(1, $s->getGroups());
		$this->assertEquals(['g' => $g], $s->getGroups());
	}

	public function testGetGrouples()
	{
		$m = new ReportManager();
		$m->addGroupless($r = new Report('r1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer()));

		$s = new ReportService($m);

		$this->assertCount(1, $s->getGroupless());
		$this->assertEquals(['r1' => $r], $s->getGroupless());
	}

	public function testGetReport()
	{
		$m = new ReportManager();
		$m->addGroup($g = new Group('g', 'Group'));
		$g->addReport($r1 = new Report('r1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer()));
		$m->addGroupless($r2 = new Report('r2', new Parameters([]), new DevNullDataSource(), new DevNullRenderer()));

		$s = new ReportService($m);
		$this->assertSame($r1, $s->getReport('r1'));
		$this->assertSame($r2, $s->getReport('r2'));
		$this->assertNull($s->getReport('r3'));
	}

}
