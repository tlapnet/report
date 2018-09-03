<?php declare(strict_types = 1);

namespace Tests\Cases\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\PrependPreprocessor;

final class PrependPreprocessorTest extends BaseTestCase
{

	public function testPreprocessor(): void
	{
		$p1 = new PrependPreprocessor('foobar');
		$this->assertEquals('foobar1', $p1->preprocess('1'));
		$this->assertEquals('foobar1 1', $p1->preprocess('1 1'));
		$this->assertEquals('foobar', $p1->preprocess(null));

		$p2 = new PrependPreprocessor('');
		$this->assertEquals('foobar', $p2->preprocess('foobar'));
	}

}
