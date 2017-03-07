<?php

namespace Tests\Cases\Model\Preprocessor;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Impl\PrependPreprocessor;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Model\Result\Result;

final class PreprocessorsTest extends BaseTestCase
{

	/**
	 * @covers Preprocessors::isEmpty
	 * @return void
	 */
	public function testDefault()
	{
		$p = new Preprocessors();
		$this->assertTrue($p->isEmpty());
	}

	/**
	 * @covers Preprocessors::preprocess
	 * @return void
	 */
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

	/**
	 * @covers Preprocessors::preprocess
	 * @return void
	 */
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

	/**
	 * @covers Preprocessors::preprocess
	 * @return void
	 */
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
