<?php declare(strict_types = 1);

namespace Tests\Cases\Subreport;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Subreport\Subreport;
use Tlapnet\Report\Subreport\SubreportBuilder;
use Tlapnet\Report\Utils\Metadata;

final class SubreportBuilderTest extends BaseTestCase
{

	public function testMissingSid(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'sid'. Please call setSid()");

		$builder = new SubreportBuilder();
		$builder->build();
	}

	public function testMissingParameters(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'parameters'. Please call setParameters()");

		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->build();
	}

	public function testMissingDataSource(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'dataSource'. Please call setDataSource()");

		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters());
		$builder->build();
	}

	public function testMissingRenderer(): void
	{
		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("Missing 'renderer'. Please call setRenderer()");

		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters());
		$builder->setDataSource(new DevNullDataSource());
		$builder->build();
	}

	public function testOk(): void
	{
		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters());
		$builder->setDataSource(new DevNullDataSource());
		$builder->setRenderer(new DevNullRenderer());

		$this->assertTrue(is_subclass_of($builder->build(), Subreport::class));
	}

	public function testMetadata(): void
	{
		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters());
		$builder->setDataSource(new DevNullDataSource());
		$builder->setRenderer(new DevNullRenderer());
		$builder->setMetadata($metadata = new Metadata());
		$result = $builder->build();

		$this->assertSame($metadata, $result->getMetadata());
	}

	public function testPreprocessors(): void
	{
		$builder = new SubreportBuilder();
		$builder->setSid('b1');
		$builder->setParameters(new Parameters());
		$builder->setDataSource(new DevNullDataSource());
		$builder->setRenderer(new DevNullRenderer());
		$builder->setPreprocessors($preprocessors = new Preprocessors());
		$result = $builder->build();

		$this->assertSame($preprocessors, $result->getPreprocessors());
	}

}
