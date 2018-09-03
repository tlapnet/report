<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

final class MathRatioPreprocessor extends AbstractPreprocessor
{

	/** @var int */
	protected $base;

	/** @var bool */
	protected $percentages = true;

	/** @var string */
	protected $format = '%d / %d';

	public function __construct(int $base = 100)
	{
		$this->base = $base;
	}

	public function setBase(int $base): void
	{
		$this->base = $base;
	}

	public function setPercentages(bool $percentages): void
	{
		$this->percentages = $percentages;
	}

	public function setFormat(string $format): void
	{
		$this->format = $format;
	}

	public function setFloating(int $ratio = 2, int $base = 0): void
	{
		if ($ratio !== 0 && $base !== 0) {
			$this->format = '%01.' . $ratio . 'f / %01.' . $base . 'f';
		} elseif ($ratio !== 0 && $base === 0) {
			$this->format = '%01.' . $ratio . 'f / %d';
		}
	}

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function preprocess($data)
	{
		if ($this->percentages) {
			$ratio = $data * $this->base;
		} else {
			$ratio = $data;
		}

		return sprintf($this->format, $ratio, $this->base);
	}

}
