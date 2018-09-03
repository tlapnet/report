<?php declare(strict_types = 1);

namespace Tests\Cases\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\BooleanPreprocessor;

final class BooleanPreprocessorTest extends BaseTestCase
{

	public function testPreprocessor(): void
	{
		$b = new BooleanPreprocessor();
		$this->assertEquals('Ano', $b->preprocess(1));
		$this->assertEquals('Ne', $b->preprocess(0));
	}

}
