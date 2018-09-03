<?php declare(strict_types = 1);

namespace Tests\Cases\Preprocessor;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Result\Result;

final class PreprocessorsTest extends BaseTestCase
{

	public function testDefault(): void
	{
		$p = new Preprocessors();
		$this->assertTrue($p->isEmpty());
	}

	public function testPreprocessWithout(): void
	{
		$p = new Preprocessors();

		$result = new Result([
			['a' => 'foo'],
			['a' => 'bar'],
		]);

		$eresult = $result->toEditable();
		$p->preprocess($eresult);

		$this->assertEquals(
			$result->getData(),
			$eresult->getData()
		);
	}

	public function testPreprocess(): void
	{
		$p = new Preprocessors();
		$p->add('a', new AppendPreprocessor('bar'));

		$result = new Result([
			['a' => 'foo'],
			['a' => 'bar'],
		]);

		$eresult = $result->toEditable();
		$p->preprocess($eresult);

		$this->assertEquals([
			['a' => 'foobar'],
			['a' => 'barbar'],
		], $eresult->getData());
	}

	public function testPreprocessChain(): void
	{
		$p = new Preprocessors();
		$p->add('a', new AppendPreprocessor('bar'));
		$p->add('a', new AppendPreprocessor('2000'));
		$p->add('a', new PrependPreprocessor('php'));

		$result = new Result([
			['a' => 'foo'],
			['a' => 'bar'],
		]);

		$eresult = $result->toEditable();
		$p->preprocess($eresult);

		$this->assertEquals([
			['a' => 'phpfoobar2000'],
			['a' => 'phpbarbar2000'],
		], $eresult->getData());
	}

}
