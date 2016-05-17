<?php

namespace Tlapnet\Report\Tests\Model\Subreport;

use Tlapnet\Report\DataSources\ArrayDataSource;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\DataSources\DummyDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Impl\DevNullPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Impl\NumberPreprocessor;
use Tlapnet\Report\Model\Subreport\Parameters;
use Tlapnet\Report\Model\Subreport\Subreport;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class SubreportTest extends BaseTestCase
{

	public function testDefault()
	{
		$r = new Subreport('s1', new Parameters([]), new DevNullDataSource(), new DevNullRenderer());

		$this->assertSame(Parameters::class, get_class($r->getParameters()));
		$this->assertSame(DevNullDataSource::class, get_class($r->getDataSource()));
		$this->assertSame(DevNullRenderer::class, get_class($r->getRenderer()));
		$this->assertNotNull($r->getMetadata());
		$this->assertNull($r->getResult());
		$this->assertNull($r->getRawResult());
	}

	public function testCompile()
	{
		$data = ['foo' => 'bar'];
		$r = new Subreport('s1', new Parameters([]), new ArrayDataSource($data), new DevNullRenderer());

		$r->compile();

		$this->assertNotNull($r->getResult());
		$this->assertNotNull($r->getRawResult());
		$this->assertEquals($data, $r->getResult()->getData());
	}

	public function testCompileException1()
	{
		$r = new Subreport('s1', new Parameters([]), new DummyDataSource(NULL), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Compilation cannot return NULL.');

		$r->compile();
	}

	public function testCompileException2()
	{
		$r = new Subreport('s1', new Parameters([]), new DummyDataSource(new \stdClass()), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("DataSource returned object 'stdClass' does not implement 'Tlapnet\Report\Model\Data\Resultable'.");

		$r->compile();
	}

	public function testPreprocess()
	{
		$r1 = new Subreport('s1', new Parameters([]), new ArrayDataSource([['foo' => 1000]]), new DevNullRenderer());
		$r1->addPreprocessor('foo', new NumberPreprocessor());

		$r1->compile();
		$r1->preprocess();

		$this->assertEquals([['foo' => '1 000.00']], $r1->getResult()->getData());
		$this->assertEquals([['foo' => 1000]], $r1->getRawResult()->getData());

		// -----

		$r2 = new Subreport('s1', new Parameters([]), new ArrayDataSource([['foo' => 'bar']]), new DevNullRenderer());
		$r2->addPreprocessor('foo', new AppendPreprocessor('bar'));

		$r2->compile();
		$r2->preprocess();

		$this->assertEquals([['foo' => 'barbar']], $r2->getResult()->getData());
		$this->assertEquals([['foo' => 'bar']], $r2->getRawResult()->getData());
	}

	public function testPreprocessException1()
	{
		$r = new Subreport('s1', new Parameters([]), new ArrayDataSource([]), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot preprocess subreport. Please compiled it first.');

		$r->preprocess();
	}

	public function testPreprocessException2()
	{
		$r = new Subreport('s1', new Parameters([]), new ArrayDataSource([]), new DevNullRenderer());
		$r->addPreprocessor('foo', new DevNullPreprocessor());
		$r->compile();

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot preprocess twice same report.');

		$r->preprocess();
		$r->preprocess();
	}

}
