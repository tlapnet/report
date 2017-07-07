<?php

namespace Tests\Cases\Preprocessor\Impl;

use ArrayIterator;
use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\DevNullPreprocessor;

final class DevNullPreprocessorTest extends BaseTestCase
{

	/**
	 * @covers DevNullPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessor()
	{
		$d = new DevNullPreprocessor();
		$o = new ArrayIterator();
		$this->assertSame($o, $d->preprocess($o));
	}

}
