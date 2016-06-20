<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Model\Subreport\Subreport;
use Tlapnet\Report\Model\Subreport\SubreportBuilder;
use Tlapnet\Report\Model\Utils\Metadata;
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

		$this->assertTrue(is_subclass_of($builder->build(), Subreport::class));
	}

	public function testMetadata()
	{
		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters([]));
		$builder->setDataSource(new DevNullDataSource());
		$builder->setRenderer(new DevNullRenderer());
		$builder->setMetadata($metadata = new Metadata());
		$result = $builder->build();

		$this->assertSame($metadata, $result->getMetadata());
	}

	public function testPreprocessors()
	{
		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters([]));
		$builder->setDataSource(new DevNullDataSource());
		$builder->setRenderer(new DevNullRenderer());
		$builder->setPreprocessors($preprocessors = new Preprocessors());
		$result = $builder->build();

		$this->assertSame($preprocessors, $result->getPreprocessors());
	}
}
