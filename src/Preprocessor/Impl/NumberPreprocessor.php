<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

class NumberPreprocessor extends AbstractPreprocessor
{

	/** @var string|null */
	protected $suffix;

	/** @var int */
	protected $decimals = 2;

	/** @var string */
	protected $decimalPoint = '.';

	/** @var string */
	protected $thousandsPoint = ' ';

	public function setSuffix(string $suffix): void
	{
		$this->suffix = $suffix;
	}

	public function setDecimals(int $decimals): void
	{
		$this->decimals = $decimals;
	}

	public function setDecimalPoint(string $point): void
	{
		$this->decimalPoint = $point;
	}

	public function setThousandsPoint(string $point): void
	{
		$this->thousandsPoint = $point;
	}

	/**
	 * @param int|float $data
	 */
	public function preprocess($data): string
	{
		$number = number_format($data, $this->decimals, $this->decimalPoint, $this->thousandsPoint);

		if ($this->suffix !== null) {
			$number .= ' ' . $this->suffix;
		}

		return $number;
	}

}
