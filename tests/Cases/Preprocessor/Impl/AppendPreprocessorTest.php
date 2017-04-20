<?php

namespace Tests\Cases\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor;

final class AppendPreprocessorTest extends BaseTestCase
{

	/**
	 * @covers AppendPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessor()
	{
		$p1 = new AppendPreprocessor('foobar');
		$this->assertEquals('1foobar', $p1->preprocess('1'));
		$this->assertEquals('1 1foobar', $p1->preprocess('1 1'));
		$this->assertEquals('foobar', $p1->preprocess(NULL));

		$p2 = new AppendPreprocessor(NULL);
		$this->assertEquals('foobar', $p2->preprocess('foobar'));
	}

}
