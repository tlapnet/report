<?php declare(strict_types = 1);

namespace Tests\Cases\Service;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Group\Group;
use Tlapnet\Report\Report\Report;
use Tlapnet\Report\ReportManager;
use Tlapnet\Report\Service\ReportService;

final class ReportServiceTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$m = new ReportManager();
		$s = new ReportService($m);

		$this->assertCount(0, $s->getGroups());
		$this->assertCount(0, $s->getGroupless());
	}

	public function testGetGroups(): void
	{
		$m = new ReportManager();
		$m->addGroup($g = new Group('g', 'Group'));
		$g->addReport($r = new Report('r1'));

		$s = new ReportService($m);

		$this->assertCount(1, $s->getGroups());
		$this->assertEquals(['g' => $g], $s->getGroups());
	}

	public function testGetGrouples(): void
	{
		$m = new ReportManager();
		$m->addGroupless($r = new Report('r1'));

		$s = new ReportService($m);

		$this->assertCount(1, $s->getGroupless());
		$this->assertEquals(['r1' => $r], $s->getGroupless());
	}

	public function testGetReport(): void
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
