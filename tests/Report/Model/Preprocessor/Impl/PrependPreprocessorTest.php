<?php

namespace Tlapnet\Report\Tests\Model\Preprocessor\Impl;

use Tlapnet\Report\Model\Preprocessor\Impl\PrependPreprocessor;
use Tlapnet\Report\Tests\BaseTestCase;

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
