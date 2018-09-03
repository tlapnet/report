<?php declare(strict_types = 1);

namespace Tests\Cases\Subreport;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Subreport\EditableSubreport;
use Tlapnet\Report\Utils\Metadata;

final class EditableSubreportTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$er = new EditableSubreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());

		$this->assertSame(Parameters::class, get_class($er->getParameters()));
		$this->assertSame(DevNullDataSource::class, get_class($er->getDataSource()));
		$this->assertSame(DevNullRenderer::class, get_class($er->getRenderer()));
		$this->assertNotNull($er->getMetadata());
		$this->assertNull($er->getResult());
		$this->assertNull($er->getRawResult());
	}

	public function testSetParameters(): void
	{
		$er = new EditableSubreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$er->setParameters($p = new Parameters());

		$this->assertSame($p, $er->getParameters());
	}

	public function testSetDataSource(): void
	{
		$er = new EditableSubreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$er->setDataSource($d = new DevNullDataSource());

		$this->assertSame($d, $er->getDataSource());
	}

	public function testSetRenderer(): void
	{
		$er = new EditableSubreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$er->setRenderer($r = new DevNullRenderer());

		$this->assertSame($r, $er->getRenderer());
	}

	public function testSetResult(): void
	{
		$er = new EditableSubreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$er->setResult($r = new Result([]));

		$this->assertSame($r, $er->getResult());
		$this->assertSame($r, $er->getRawResult());
	}

	public function testSetMetadata(): void
	{
		$er = new EditableSubreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$er->setMetadata($m = new Metadata());

		$this->assertSame($m, $er->getMetadata());
	}

	public function testSetPreprocessors(): void
	{
		$er = new EditableSubreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$er->setPreprocessors($p = new Preprocessors());

		$this->assertSame($p, $er->getPreprocessors());
	}

}
