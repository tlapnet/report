<?php

namespace Tests\Cases\Preprocessor\Impl;

use Tests\Engine\BaseTestCase;
use Tlapnet\Report\Preprocessor\Impl\NumberPreprocessor;

final class NumberPreprocessorTest extends BaseTestCase
{

	/**
	 * @covers NumberPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessor()
	{
		$p = new NumberPreprocessor();
		$this->assertEquals('0.00', $p->preprocess(0));
		$this->assertEquals('1.00', $p->preprocess(1));
		$this->assertEquals('10.00', $p->preprocess(10));
		$this->assertEquals('100.00', $p->preprocess(100));
		$this->assertEquals('1 000.00', $p->preprocess(1000));
		$this->assertEquals('10 000.00', $p->preprocess(10000));
		$this->assertEquals('100 000.00', $p->preprocess(100000));
		$this->assertEquals('1 000 000.00', $p->preprocess(1000000));
	}

	/**
	 * @covers NumberPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessorDecimals()
	{
		$p = new NumberPreprocessor();
		$p->setDecimals(0);
		$this->assertEquals('0', $p->preprocess(0));
		$this->assertEquals('1', $p->preprocess(1));
		$this->assertEquals('10', $p->preprocess(10));
		$this->assertEquals('100', $p->preprocess(100));
		$this->assertEquals('1 000', $p->preprocess(1000));
		$this->assertEquals('10 000', $p->preprocess(10000));
		$this->assertEquals('100 000', $p->preprocess(100000));
		$this->assertEquals('1 000 000', $p->preprocess(1000000));
	}

	/**
	 * @covers NumberPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessorDecimalPoint()
	{
		$p = new NumberPreprocessor();
		$p->setDecimalPoint('|');
		$this->assertEquals('0|00', $p->preprocess(0));
		$this->assertEquals('1|00', $p->preprocess(1));
		$this->assertEquals('10|00', $p->preprocess(10));
		$this->assertEquals('100|00', $p->preprocess(100));
		$this->assertEquals('1 000|00', $p->preprocess(1000));
		$this->assertEquals('10 000|00', $p->preprocess(10000));
		$this->assertEquals('100 000|00', $p->preprocess(100000));
		$this->assertEquals('1 000 000|00', $p->preprocess(1000000));
	}

	/**
	 * @covers NumberPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessorThousandsPoint()
	{
		$p = new NumberPreprocessor();
		$p->setThousandsPoint('-');
		$this->assertEquals('0.00', $p->preprocess(0));
		$this->assertEquals('1.00', $p->preprocess(1));
		$this->assertEquals('10.00', $p->preprocess(10));
		$this->assertEquals('100.00', $p->preprocess(100));
		$this->assertEquals('1-000.00', $p->preprocess(1000));
		$this->assertEquals('10-000.00', $p->preprocess(10000));
		$this->assertEquals('100-000.00', $p->preprocess(100000));
		$this->assertEquals('1-000-000.00', $p->preprocess(1000000));
	}

	/**
	 * @covers NumberPreprocessor::preprocess
	 * @return void
	 */
	public function testPreprocessorSuffix()
	{
		$p = new NumberPreprocessor();
		$p->setThousandsPoint('-');
		$p->setSuffix('CZK');
		$this->assertEquals('0.00 CZK', $p->preprocess(0));
		$this->assertEquals('1-000-000.00 CZK', $p->preprocess(1000000));
	}

}
