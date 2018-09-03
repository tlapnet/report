<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

final class CurrencyPreprocessor extends NumberPreprocessor
{

	/** @var string */
	protected $suffix;

	public function __construct(string $suffix = 'Kč')
	{
		$this->suffix = $suffix;
	}

	/**
	 * @param int|float $data
	 */
	public function preprocess($data): string
	{
		return number_format($data, $this->decimals, $this->decimalPoint, $this->thousandsPoint) . ' ' . $this->suffix;
	}

}
