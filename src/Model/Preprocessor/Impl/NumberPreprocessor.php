<?php

namespace Tlapnet\Report\Model\Preprocessor\Impl;

class NumberPreprocessor extends AbstractPreprocessor
{

	/** @var int */
	protected $decimals = 2;

	/** @var string */
	protected $decimalPoint = '.';

	/** @var string */
	protected $thousandsPoint = ' ';

	/**
	 * @param string $suffix
	 */
	public function setSuffix($suffix)
	{
		$this->suffix = $suffix;
	}

	/**
	 * @param int $decimals
	 */
	public function setDecimals($decimals)
	{
		$this->decimals = $decimals;
	}

	/**
	 * @param string $point
	 */
	public function setDecimalPoint($point)
	{
		$this->decimalPoint = $point;
	}

	/**
	 * @param string $point
	 */
	public function setThousandsPoint($point)
	{
		$this->thousandsPoint = $point;
	}

	/**
	 * PREPROCESSING ***********************************************************
	 */

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function preprocess($data)
	{
		return number_format($data, $this->decimals, $this->decimalPoint, $this->thousandsPoint);
	}

}
