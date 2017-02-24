<?php

namespace Tlapnet\Report\Model\Preprocessor\Impl;

class NumberPreprocessor extends AbstractPreprocessor
{

	/** @var string */
	protected $suffix;

	/** @var int */
	protected $decimals = 2;

	/** @var string */
	protected $decimalPoint = '.';

	/** @var string */
	protected $thousandsPoint = ' ';

	/**
	 * @param string $suffix
	 * @return void
	 */
	public function setSuffix($suffix)
	{
		$this->suffix = $suffix;
	}

	/**
	 * @param int $decimals
	 * @return void
	 */
	public function setDecimals($decimals)
	{
		$this->decimals = $decimals;
	}

	/**
	 * @param string $point
	 * @return void
	 */
	public function setDecimalPoint($point)
	{
		$this->decimalPoint = $point;
	}

	/**
	 * @param string $point
	 * @return void
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
		$number = number_format($data, $this->decimals, $this->decimalPoint, $this->thousandsPoint);

		if ($this->suffix) {
			$number = $number . ' ' . $this->suffix;
		}

		return $number;
	}

}
