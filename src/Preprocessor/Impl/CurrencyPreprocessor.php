<?php

namespace Tlapnet\Report\Preprocessor\Impl;

final class CurrencyPreprocessor extends NumberPreprocessor
{

	/** @var string */
	protected $suffix;

	/**
	 * @param string $suffix
	 */
	public function __construct($suffix = 'Kč')
	{
		$this->suffix = $suffix;
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
		return number_format($data, $this->decimals, $this->decimalPoint, $this->thousandsPoint) . ' ' . $this->suffix;
	}

}
