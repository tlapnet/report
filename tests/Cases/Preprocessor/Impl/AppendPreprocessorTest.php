<?php declare(strict_types = 1);

namespace Tests\Cases\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\AppendPreprocessor;

final class AppendPreprocessorTest extends BaseTestCase
{

	public function testPreprocessor(): void
	{
		$p1 = new AppendPreprocessor('foobar');
		$this->assertEquals('1foobar', $p1->preprocess('1'));
		$this->assertEquals('1 1foobar', $p1->preprocess('1 1'));
		$this->assertEquals('foobar', $p1->preprocess(null));

		$p2 = new AppendPreprocessor('');
		$this->assertEquals('foobar', $p2->preprocess('foobar'));
	}

}
