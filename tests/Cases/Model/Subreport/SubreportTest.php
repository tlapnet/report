<?php

namespace Tests\Cases\Model\Subreport;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\ArrayDataSource;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\DataSources\DummyDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Parameters\Parameter\TextParameter;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Impl\DevNullPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Impl\NumberPreprocessor;
use Tlapnet\Report\Model\Subreport\Subreport;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Renderers\DummyRenderer;

final class SubreportTest extends BaseTestCase
{

	/**
	 * @covers Subreport::getParameters
	 * @covers Subreport::getDataSource
	 * @covers Subreport::getRenderer
	 * @covers Subreport::getMetadata
	 * @covers Subreport::getResult
	 * @covers Subreport::getRawResult
	 * @return void
	 */
	public function testDefault()
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());

		$this->assertSame(Parameters::class, get_class($r->getParameters()));
		$this->assertSame(DevNullDataSource::class, get_class($r->getDataSource()));
		$this->assertSame(DevNullRenderer::class, get_class($r->getRenderer()));
		$this->assertNotNull($r->getMetadata());
		$this->assertNull($r->getResult());
		$this->assertNull($r->getRawResult());
	}

	/**
	 * @covers Subreport::isState
	 * @return void
	 */
	public function testIsState1()
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());
		$this->assertTrue($r->isState($r::STATE_CREATED));

		$r->compile();
		$this->assertTrue($r->isState($r::STATE_COMPILED));

		$r->render();
		$this->assertTrue($r->isState($r::STATE_RENDERED));
	}

	/**
	 * @covers Subreport::isState
	 * @return void
	 */
	public function testIsState2()
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());
		$r->addPreprocessor('foo', new DevNullPreprocessor());
		$this->assertTrue($r->isState($r::STATE_CREATED));

		$r->compile();
		$this->assertTrue($r->isState($r::STATE_COMPILED));

		$r->preprocess();
		$this->assertTrue($r->isState($r::STATE_PREPROCESSED));

		$r->render();
		$this->assertTrue($r->isState($r::STATE_RENDERED));
	}

	/**
	 * @covers Subreport::hasOption
	 * @return void
	 */
	public function testHashOption()
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->assertTrue($r->hasOption('foo'));
	}

	/**
	 * @covers Subreport::getOption
	 * @return void
	 */
	public function testGetOption()
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->assertEquals('bar', $r->getOption('foo'));
	}

	/**
	 * @covers Subreport::getOption
	 * @return void
	 */
	public function testGetOptionDefault()
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->assertEquals('foobar', $r->getOption('foobar', 'foobar'));
	}

	/**
	 * @covers Subreport::getOption
	 * @return void
	 */
	public function testGetOptionSuggestion()
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown key "fod", did you mean "foo"?');

		$this->assertEquals('foobar', $r->getOption('fod'));
	}

	/**
	 * @covers Subreport::attach
	 * @return void
	 */
	public function testAttach()
	{
		$p1 = new TextParameter('foobar');
		$p = new Parameters();
		$p->add($p1);

		$this->assertNull($p1->getValue());
		$this->assertEmpty($p->toArray());

		$r = new Subreport('s1', $p, new DevNullDataSource(), new DevNullRenderer());
		$r->attach(['foobar' => 100]);

		$this->assertEquals(100, $p1->getValue());
		$this->assertEquals(['foobar' => 100], $p->toArray());
	}

	/**
	 * @covers Subreport::compile
	 * @return void
	 */
	public function testCompile()
	{
		$data = ['foo' => 'bar'];
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource($data), new DevNullRenderer());

		$r->compile();

		$this->assertNotNull($r->getResult());
		$this->assertNotNull($r->getRawResult());
		$this->assertEquals($data, $r->getResult()->getData());
	}

	/**
	 * @covers Subreport::compile
	 * @return void
	 */
	public function testCompileException1()
	{
		$r = new Subreport('s1', new Parameters(), new DummyDataSource(NULL), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Compilation cannot return NULL.');

		$r->compile();
	}

	/**
	 * @covers Subreport::compile
	 * @return void
	 */
	public function testCompileException2()
	{
		$r = new Subreport('s1', new Parameters(), new DummyDataSource(new \stdClass()), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage("DataSource returned object 'stdClass' does not implement 'Tlapnet\Report\Model\Result\Resultable'.");

		$r->compile();
	}

	/**
	 * @covers Subreport::preprocess
	 * @return void
	 */
	public function testPreprocess()
	{
		$r1 = new Subreport('s1', new Parameters(), new ArrayDataSource([['foo' => 1000]]), new DevNullRenderer());
		$r1->addPreprocessor('foo', new NumberPreprocessor());

		$r1->compile();
		$r1->preprocess();

		$this->assertEquals([['foo' => '1 000.00']], $r1->getResult()->getData());
		$this->assertEquals([['foo' => 1000]], $r1->getRawResult()->getData());

		// -----

		$r2 = new Subreport('s1', new Parameters(), new ArrayDataSource([['foo' => 'bar']]), new DevNullRenderer());
		$r2->addPreprocessor('foo', new AppendPreprocessor('bar'));

		$r2->compile();
		$r2->preprocess();

		$this->assertEquals([['foo' => 'barbar']], $r2->getResult()->getData());
		$this->assertEquals([['foo' => 'bar']], $r2->getRawResult()->getData());

		// -----

		$r3 = new Subreport('s1', new Parameters(), new ArrayDataSource([['foo' => 'bar']]), new DevNullRenderer());

		$r3->compile();
		$r3->preprocess();

		$this->assertEquals([['foo' => 'bar']], $r3->getResult()->getData());
		$this->assertEquals([['foo' => 'bar']], $r3->getRawResult()->getData());
		$this->assertSame($r3->getResult(), $r3->getResult());
	}

	/**
	 * @covers Subreport::preprocess
	 * @return void
	 */
	public function testPreprocessException1()
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot preprocess subreport. Please compiled it first.');

		$r->preprocess();
	}

	/**
	 * @covers Subreport::preprocess
	 * @return void
	 */
	public function testPreprocessException2()
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());
		$r->addPreprocessor('foo', new DevNullPreprocessor());
		$r->compile();

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot preprocess twice same report.');

		$r->preprocess();
		$r->preprocess();
	}

	/**
	 * @covers Subreport::render
	 * @return void
	 */
	public function testRender()
	{
		$r1 = new Subreport('s1', new Parameters(), new ArrayDataSource(['foo' => 'bar']), new DummyRenderer());
		$r1->compile();
		$r1->preprocess();

		$result1 = $r1->render();

		$this->assertSame($result1, $r1->getResult());
		$this->assertSame($result1, $r1->getRawResult());
	}

	/**
	 * @covers Subreport::render
	 * @return void
	 */
	public function testRenderException1()
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot render subreport. Please compiled or preprocess it first.');

		$r->render();
	}

}
