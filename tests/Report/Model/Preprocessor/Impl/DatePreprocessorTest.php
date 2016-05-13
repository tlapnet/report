<?php

namespace Tlapnet\Report\Tests\Model\Preprocessor\Impl;

use Tlapnet\Report\Model\Preprocessor\Impl\DatePreprocessor;
use Tlapnet\Report\Tests\BaseTestCase;

final class DatePreprocessorTest extends BaseTestCase
{

	public function testPreprocessor()
	{
		$p = new DatePreprocessor();
		$this->assertEquals('01.01.2000', $p->preprocess('2000-01-01'));
		$this->assertEquals('01.01.2000', $p->preprocess('2000/01/01'));
		$this->assertEquals('01.01.2000', $p->preprocess('2000/01/01 15:00'));
		$this->assertEquals('01.01.2000', $p->preprocess(strtotime('2000/01/01 15:00')));
	}


	public function testPreprocessorFormat()
	{
		$p = new DatePreprocessor('Y');
		$this->assertEquals('2000', $p->preprocess('2000-01-01'));
		$this->assertEquals('2000', $p->preprocess('2000/01/01'));
		$this->assertEquals('2000', $p->preprocess('2000/01/01 15:00'));
		$this->assertEquals('2000', $p->preprocess(strtotime('2000/01/01 15:00')));
	}

}
