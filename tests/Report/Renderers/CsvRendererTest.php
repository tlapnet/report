<?php

namespace Tlapnet\Report\Tests\Renderers;

use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Renderers\CsvRenderer;
use Tlapnet\Report\Tests\BaseTestCase;

final class CsvRendererTest extends BaseTestCase
{

	/**
	 * @covers CsvRenderer::render
	 * @return void
	 */
	public function testDefault()
	{
		$r = new CsvRenderer();
		$this->assertEquals("\"foo\"\n\"bar\"", $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals("\"foo\";\"foobar\"\n\"bar\";\"1\"", $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

	/**
	 * @covers CsvRenderer::render
	 * @return void
	 */
	public function testDelimiter()
	{
		$r = new CsvRenderer();
		$r->setDelimiter('|');
		$this->assertEquals("\"foo\"\n\"bar\"", $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals("\"foo\"|\"foobar\"\n\"bar\"|\"1\"", $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

}
