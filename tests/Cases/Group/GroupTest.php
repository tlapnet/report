<?php declare(strict_types = 1);

namespace Tests\Cases\Group;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Group\Group;
use Tlapnet\Report\Report\Report;

final class GroupTest extends BaseTestCase
{

	public function testGetters(): void
	{
		$g = new Group('g1', 'foobar');
		$this->assertEquals('g1', $g->getGid());
		$this->assertEquals('foobar', $g->getName());
	}

	public function testEmpty(): void
	{
		$g = new Group('g1', 'foobar');
		$this->assertEmpty($g->getReports());
	}

	public function testHasReport1(): void
	{
		$g = new Group('g1', 'foobar');
		$this->assertFalse($g->hasReport('foobar'));
	}

	public function testHasReport2(): void
	{
		$g = new Group('g1', 'foobar');
		$g->addReport(new Report('r1'));
		$this->assertTrue($g->hasReport('r1'));
	}

	public function testGetReport(): void
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertSame($r, $g->getReport('r1'));
	}

	/**
	 * @coversNothing
	 */
	public function testGetReportSuggestions(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Report "fod" not found, did you mean "foo"?');

		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('foo'));
		$g->getReport('fod');
	}

	public function testGetReports1(): void
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertEquals(['r1' => $r], $g->getReports());
	}

	public function testGetReports2(): void
	{
		$g = new Group('g1', 'foobar');
		$g->addReport($r = new Report('r1'));
		$this->assertEquals(['r1' => $r], $g->getReports());
		$g->addReport($r2 = new Report('r1'));
		$this->assertEquals(['r1' => $r2], $g->getReports());
	}

}
