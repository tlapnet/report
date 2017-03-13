<?php

namespace Tests\Cases\Bridges\Nette\Renderers\ExtraTable;

use Latte\Engine;
use Mockery;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\Presenter;
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
		$latteFactory->onCreate(function (Engine $engine) {
			$presenter = Mockery::mock(Presenter::class);
			$presenter->shouldReceive('link')->andReturn('FOOBAR');
			$engine->addProvider('uiPresenter', $presenter);
		});
		$templateFactory = new TemplateFactory($latteFactory);

		return $templateFactory;
	}

	/**
	 * @return LinkGenerator
	 */
	private function createLinkGenerator()
	{
		$linkGenerator = Mockery::mock(LinkGenerator::class);
		$linkGenerator->shouldReceive('link')
			->andReturnUsing(function ($destination, array $args = []) {
				if ($args) {
					$parts = [];
					foreach ($args as $key => $val) {
						$parts[] = $key . '=' . $val;
					}

					return implode('|', $parts);
				}

				return $destination;
			});

		return $linkGenerator;
	}

	/**
	 * @covers ExtraTableRenderer::addColumn
	 * @covers ExtraTableRenderer::render
	 * @return void
	 */
	public function testOutput1()
	{
		$templateFactory = $this->createTemplateFactory();
		$linkGenerator = $this->createLinkGenerator();
		$table = new ExtraTableRenderer($templateFactory, $linkGenerator);
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
		$linkGenerator = $this->createLinkGenerator();
		$table = new ExtraTableRenderer($templateFactory, $linkGenerator);
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

	/**
	 * @covers ExtraTableRenderer::addColumn
	 * @covers ExtraTableRenderer::render
	 * @return void
	 */
	public function testOutput3()
	{
		$templateFactory = $this->createTemplateFactory();
		$linkGenerator = $this->createLinkGenerator();
		$table = new ExtraTableRenderer($templateFactory, $linkGenerator);
		$table->addColumn('foo', 'Foo')
			->url('http://example.com');
		$table->addColumn('bar', 'Bar')
			->link('Fake:link', ['args1' => 'foobar']);

		$result = new Result([
			1 => ['foo' => 'bar1a', 'bar' => 'baz2a'],
			2 => ['foo' => 'baz1b', 'bar' => 'baz2b'],
		]);

		ob_start();
		$table->render($result);
		$output = trim(ob_get_clean()) . PHP_EOL;

		$this->assertStringEqualsFile(__DIR__ . '/files/output3.html', $output);
	}

	/**
	 * @covers ExtraTableRenderer::addColumn
	 * @covers ExtraTableRenderer::render
	 * @return void
	 */
	public function testOutput4()
	{
		$templateFactory = $this->createTemplateFactory();
		$linkGenerator = $this->createLinkGenerator();
		$table = new ExtraTableRenderer($templateFactory, $linkGenerator);
		$table->addColumn('foo', 'Foo')
			->link('Fake:link', ['args1' => '#bar']);

		$result = new Result([
			1 => ['foo' => 'bar', 'bar' => 'baz'],
		]);

		ob_start();
		$table->render($result);
		$output = trim(ob_get_clean()) . PHP_EOL;

		$this->assertStringEqualsFile(__DIR__ . '/files/output4.html', $output);
	}

}
