<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Model\Subreport\EditableSubreport;
use Tlapnet\Report\Model\Utils\Metadata;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class EditableSubreportTest extends BaseTestCase
{

	public function testDefault()
	{
		$er = new EditableSubreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());

		$this->assertSame(Parameters::class, get_class($er->getParameters()));
		$this->assertSame(DevNullDataSource::class, get_class($er->getDataSource()));
		$this->assertSame(DevNullRenderer::class, get_class($er->getRenderer()));
		$this->assertNotNull($er->getMetadata());
		$this->assertNull($er->getResult());
		$this->assertNull($er->getRawResult());
	}

	public function testSetParameters()
	{
		$er = new EditableSubreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());
		$er->setParameters($p = new Parameters([]));

		$this->assertSame($p, $er->getParameters());
	}

	public function testSetDataSource()
	{
		$er = new EditableSubreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());
		$er->setDataSource($d = new DevNullDataSource());

		$this->assertSame($d, $er->getDataSource());
	}

	public function testSetRenderer()
	{
		$er = new EditableSubreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());
		$er->setRenderer($r = new DevNullRenderer());

		$this->assertSame($r, $er->getRenderer());
	}

	public function testSetResult()
	{
		$er = new EditableSubreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());
		$er->setResult($r = new Result([]));

		$this->assertSame($r, $er->getResult());
		$this->assertSame($r, $er->getRawResult());
	}

	public function testSetMetadata()
	{
		$er = new EditableSubreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());
		$er->setMetadata($m = new Metadata([]));

		$this->assertSame($m, $er->getMetadata());
	}

	public function testSetPreprocessors()
	{
		$er = new EditableSubreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());
		$er->setPreprocessors($p = new Preprocessors());

		$this->assertSame($p, $er->getPreprocessors());
	}

}
