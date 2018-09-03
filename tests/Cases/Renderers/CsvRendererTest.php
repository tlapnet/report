<?php declare(strict_types = 1);

namespace Tests\Cases\Renderers;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Renderers\CsvRenderer;
use Tlapnet\Report\Result\Result;

final class CsvRendererTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$r = new CsvRenderer();
		$this->assertEquals("\"foo\"\n\"bar\"", $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals("\"foo\";\"foobar\"\n\"bar\";\"1\"", $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

	public function testDelimiter(): void
	{
		$r = new CsvRenderer();
		$r->setDelimiter('|');
		$this->assertEquals("\"foo\"\n\"bar\"", $r->render(new Result([['foo' => 'bar']])));
		$this->assertEquals("\"foo\"|\"foobar\"\n\"bar\"|\"1\"", $r->render(new Result([['foo' => 'bar', 'foobar' => 1]])));
	}

}
