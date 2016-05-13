<?php

namespace Tlapnet\Report\Tests\Model\Preprocessor\Impl;

use Tlapnet\Report\Model\Preprocessor\Impl\PrependPreprocessor;
use Tlapnet\Report\Tests\BaseTestCase;

final class PrependPreprocessorTest extends BaseTestCase
{

	public function testPreprocessor()
	{
		$p = new PrependPreprocessor('foobar');
		$this->assertEquals('foobar1', $p->preprocess('1'));
		$this->assertEquals('foobar1 1', $p->preprocess('1 1'));
		$this->assertEquals('foobar', $p->preprocess(NULL));
	}

}
