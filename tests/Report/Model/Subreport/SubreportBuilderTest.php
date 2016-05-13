<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Model\Subreport\Subreport;
use Tlapnet\Report\Model\Subreport\SubreportBuilder;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class SubreportBuilderTest extends BaseTestCase
{

	public function testMissingSid()
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'sid'. Please call setSid()");

		$builder = new SubreportBuilder();
		$builder->build();
	}

	public function testMissingParameters()
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'parameters'. Please call setParameters()");

		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->build();
	}

	public function testMissingDataSource()
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'dataSource'. Please call setDataSource()");

		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters([]));
		$builder->build();
	}

	public function testMissingRenderer()
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'renderer'. Please call setRenderer()");

		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters([]));
		$builder->setDataSource(new DevNullDataSource());
		$builder->build();
	}

	public function testOk()
	{
		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters([]));
		$builder->setDataSource(new DevNullDataSource());
		$builder->setRenderer(new DevNullRenderer());

		$this->assertEquals(Subreport::class, get_class($builder->build()));
	}


}
