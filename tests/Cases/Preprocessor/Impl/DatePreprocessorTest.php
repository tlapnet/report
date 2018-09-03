<?php declare(strict_types = 1);

namespace Tests\Cases\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\DatePreprocessor;

final class DatePreprocessorTest extends BaseTestCase
{

	public function testPreprocessor(): void
	{
		$p = new DatePreprocessor();
		$this->assertEquals('01.01.2000', $p->preprocess('2000-01-01'));
		$this->assertEquals('01.01.2000', $p->preprocess('2000/01/01'));
		$this->assertEquals('01.01.2000', $p->preprocess('2000/01/01 15:00'));
		$this->assertEquals('01.01.2000', $p->preprocess(strtotime('2000/01/01 15:00')));
	}

	public function testPreprocessorFormat(): void
	{
		$p = new DatePreprocessor('Y');
		$this->assertEquals('2000', $p->preprocess('2000-01-01'));
		$this->assertEquals('2000', $p->preprocess('2000/01/01'));
		$this->assertEquals('2000', $p->preprocess('2000/01/01 15:00'));
		$this->assertEquals('2000', $p->preprocess(strtotime('2000/01/01 15:00')));
	}

}
