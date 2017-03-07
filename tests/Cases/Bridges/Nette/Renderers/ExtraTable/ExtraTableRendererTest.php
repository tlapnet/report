<?php

namespace Tests\Cases\Bridges\Nette\Renderers\ExtraTable;

use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Tests\Engine\BaseTestCase;
use Tests\Mocks\Latte\LatteFactory;
use Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\ExtraTableRenderer;
use Tlapnet\Report\Model\Result\Result;

final class ExtraTableRendererTest extends BaseTestCase
{

	/**
	 * @return TemplateFactory
	 */
	private function createTemplateFactory()
	{
		$latteFactory = new LatteFactory();
		$templateFactory = new TemplateFactory($latteFactory);

		return $templateFactory;
	}

	/**
	 * @covers ExtraTableRenderer::addColumn
	 * @covers ExtraTableRenderer::render
	 * @return void
	 */
	public function testOutput1()
	{
		$templateFactory = $this->createTemplateFactory();
		$table = new ExtraTableRenderer($templateFactory);
		$table->addColumn('foo');

		$result = new Result([
			1 => ['foo' => 'bar'],
			2 => ['foo' => 'baz'],
		]);

		ob_start();
		$table->render($result);
		$output = trim(ob_get_clean()) . PHP_EOL;

		$this->assertStringEqualsFile(__DIR__ . '/files/output1.html', $output);
	}

	/**
	 * @covers ExtraTableRenderer::addColumn
	 * @covers ExtraTableRenderer::render
	 * @return void
	 */
	public function testOutput2()
	{
		$templateFactory = $this->createTemplateFactory();
		$table = new ExtraTableRenderer($templateFactory);
		$table->addColumn('foo', 'Foobar');

		$result = new Result([
			1 => ['foo' => 'bar'],
			2 => ['foo' => 'baz'],
		]);

		ob_start();
		$table->render($result);
		$output = trim(ob_get_clean()) . PHP_EOL;

		$this->assertStringEqualsFile(__DIR__ . '/files/output2.html', $output);
	}

}
