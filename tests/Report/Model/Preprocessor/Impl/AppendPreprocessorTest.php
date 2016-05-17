<?php

namespace Tlapnet\Report\Tests\Model\Preprocessor\Impl;

use Tlapnet\Report\Model\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Tests\BaseTestCase;

final class AppendPreprocessorTest extends BaseTestCase
{

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
