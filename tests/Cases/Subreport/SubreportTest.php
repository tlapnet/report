<?php declare(strict_types = 1);

namespace Tests\Cases\Subreport;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\DataSources\ArrayDataSource;
use Tlapnet\Report\DataSources\DevNullDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Impl\TextParameter;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Preprocessor\Impl\DevNullPreprocessor;
use Tlapnet\Report\Preprocessor\Impl\NumberPreprocessor;
use Tlapnet\Report\Renderers\DevNullRenderer;
use Tlapnet\Report\Renderers\DummyRenderer;
use Tlapnet\Report\Subreport\Subreport;

final class SubreportTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());

		$this->assertSame(Parameters::class, get_class($r->getParameters()));
		$this->assertSame(DevNullDataSource::class, get_class($r->getDataSource()));
		$this->assertSame(DevNullRenderer::class, get_class($r->getRenderer()));
		$this->assertNotNull($r->getMetadata());
		$this->assertNull($r->getResult());
		$this->assertNull($r->getRawResult());
	}

	public function testIsState1(): void
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());
		$this->assertTrue($r->isState($r::STATE_CREATED));

		$r->compile();
		$this->assertTrue($r->isState($r::STATE_COMPILED));

		$r->render();
		$this->assertTrue($r->isState($r::STATE_RENDERED));
	}

	public function testIsState2(): void
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

	public function testHashOption(): void
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->assertTrue($r->hasOption('foo'));
	}

	public function testGetOption(): void
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->assertEquals('bar', $r->getOption('foo'));
	}

	public function testGetOptionDefault(): void
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->assertEquals('foobar', $r->getOption('foobar', 'foobar'));
	}

	public function testGetOptionSuggestion(): void
	{
		$r = new Subreport('s1', new Parameters(), new DevNullDataSource(), new DevNullRenderer());
		$r->setOption('foo', 'bar');

		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Unknown key "fod", did you mean "foo"?');

		$this->assertEquals('foobar', $r->getOption('fod'));
	}

	public function testAttach(): void
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

	public function testCompile(): void
	{
		$data = ['foo' => 'bar'];
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource($data), new DevNullRenderer());

		$r->compile();

		$this->assertNotNull($r->getResult());
		$this->assertNotNull($r->getRawResult());
		$this->assertEquals($data, $r->getResult()->getData());
	}

	public function testPreprocess(): void
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

	public function testPreprocessException1(): void
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot preprocess subreport. Please compiled it first.');

		$r->preprocess();
	}

	public function testPreprocessException2(): void
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());
		$r->addPreprocessor('foo', new DevNullPreprocessor());
		$r->compile();

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot preprocess twice same report.');

		$r->preprocess();
		$r->preprocess();
	}

	public function testRender(): void
	{
		$r1 = new Subreport('s1', new Parameters(), new ArrayDataSource(['foo' => 'bar']), new DummyRenderer());
		$r1->compile();
		$r1->preprocess();

		$result1 = $r1->render();

		$this->assertSame($result1, $r1->getResult());
		$this->assertSame($result1, $r1->getRawResult());
	}

	public function testRenderException1(): void
	{
		$r = new Subreport('s1', new Parameters(), new ArrayDataSource([]), new DevNullRenderer());

		$this->expectException(InvalidStateException::class);
		$this->expectExceptionMessage('Cannot render subreport. Please compiled or preprocess it first.');

		$r->render();
	}

}
