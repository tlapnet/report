<?php

namespace Tlapnet\Report\Tests\Model\Preprocessor\Impl;

use Tlapnet\Report\Model\Preprocessor\Impl\AppendPreprocessor;
use Tlapnet\Report\Tests\BaseTestCase;

final class AppendPreprocessorTest extends BaseTestCase
{

	public function testPreprocessor()
	{
		$p = new AppendPreprocessor('foobar');
		$this->assertEquals('1foobar', $p->preprocess('1'));
		$this->assertEquals('1 1foobar', $p->preprocess('1 1'));
		$this->assertEquals('foobar', $p->preprocess(NULL));
	}

}
