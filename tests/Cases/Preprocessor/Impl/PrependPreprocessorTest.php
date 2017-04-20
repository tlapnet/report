<?php

namespace Tests\Cases\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor;

final class PrependPreprocessorTest extends BaseTestCase
{

	/**
	 * @covers PrependPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessor()
	{
		$p1 = new PrependPreprocessor('foobar');
		$this->assertEquals('foobar1', $p1->preprocess('1'));
		$this->assertEquals('foobar1 1', $p1->preprocess('1 1'));
		$this->assertEquals('foobar', $p1->preprocess(NULL));

		$p2 = new PrependPreprocessor(NULL);
		$this->assertEquals('foobar', $p2->preprocess('foobar'));
	}

}
