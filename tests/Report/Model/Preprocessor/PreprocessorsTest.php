<?php

namespace Tlapnet\Report\Tests\Model\Preprocessor;

use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Impl\PrependPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Tests\BaseTestCase;

final class PreprocessorsTest extends BaseTestCase
{

	public function testDefault()
	{
		$p = new Preprocessors();
		$this->assertTrue($p->isEmpty());
	}

	public function testPreprocessWithout()
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

	public function testPreprocess()
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

	public function testPreprocessChain()
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
