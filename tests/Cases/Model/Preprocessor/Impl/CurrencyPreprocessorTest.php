<?php

namespace Tests\Cases\Model\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Model\Preprocessor\Impl\CurrencyPreprocessor;

final class CurrencyPreprocessorTest extends BaseTestCase
{

	/**
	 * @covers CurrencyPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessor()
	{
		$p = new CurrencyPreprocessor();
		$this->assertEquals('0.00 Kč', $p->preprocess(0));
		$this->assertEquals('1.00 Kč', $p->preprocess(1));
		$this->assertEquals('10.00 Kč', $p->preprocess(10));
		$this->assertEquals('100.00 Kč', $p->preprocess(100));
		$this->assertEquals('1 000.00 Kč', $p->preprocess(1000));
		$this->assertEquals('10 000.00 Kč', $p->preprocess(10000));
		$this->assertEquals('100 000.00 Kč', $p->preprocess(100000));
		$this->assertEquals('1 000 000.00 Kč', $p->preprocess(1000000));
	}

	/**
	 * @covers CurrencyPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessorSuffix()
	{
		$p = new CurrencyPreprocessor('CZK');
		$this->assertEquals('0.00 CZK', $p->preprocess(0));
		$this->assertEquals('1.00 CZK', $p->preprocess(1));
		$this->assertEquals('10.00 CZK', $p->preprocess(10));
		$this->assertEquals('100.00 CZK', $p->preprocess(100));
		$this->assertEquals('1 000.00 CZK', $p->preprocess(1000));
		$this->assertEquals('10 000.00 CZK', $p->preprocess(10000));
		$this->assertEquals('100 000.00 CZK', $p->preprocess(100000));
		$this->assertEquals('1 000 000.00 CZK', $p->preprocess(1000000));
	}

}
