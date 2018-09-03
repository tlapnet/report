<?php declare(strict_types = 1);

namespace Tests\Cases\Preprocessor\Impl;

use ArrayIterator;
use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\DevNullPreprocessor;

final class DevNullPreprocessorTest extends BaseTestCase
{

	public function testPreprocessor(): void
	{
		$d = new DevNullPreprocessor();
		$o = new ArrayIterator();
		$this->assertSame($o, $d->preprocess($o));
	}

}
