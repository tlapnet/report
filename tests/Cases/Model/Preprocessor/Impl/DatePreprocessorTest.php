<?php

namespace Tests\Cases\Model\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Preprocessor\Impl\DatePreprocessor;

final class DatePreprocessorTest extends BaseTestCase
{

	/**
	 * @covers DatePreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessor()
	{
		$p = new DatePreprocessor();
		$this->assertEquals('01.01.2000', $p->preprocess('2000-01-01'));
		$this->assertEquals('01.01.2000', $p->preprocess('2000/01/01'));
		$this->assertEquals('01.01.2000', $p->preprocess('2000/01/01 15:00'));
		$this->assertEquals('01.01.2000', $p->preprocess(strtotime('2000/01/01 15:00')));
	}

	/**
	 * @covers DatePreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessorFormat()
	{
		$p = new DatePreprocessor('Y');
		$this->assertEquals('2000', $p->preprocess('2000-01-01'));
		$this->assertEquals('2000', $p->preprocess('2000/01/01'));
		$this->assertEquals('2000', $p->preprocess('2000/01/01 15:00'));
		$this->assertEquals('2000', $p->preprocess(strtotime('2000/01/01 15:00')));
	}

}
