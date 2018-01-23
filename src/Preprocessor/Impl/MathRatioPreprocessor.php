<?php

namespace Tlapnet\Report\Preprocessor\Impl;

final class MathRatioPreprocessor extends AbstractPreprocessor
{

	/** @var int */
	protected $base;

	/** @var bool */
	protected $percentages = TRUE;

	/** @var string */
	protected $format = '%d / %d';

	/**
	 * @param int $base
	 */
	public function __construct($base = 100)
	{
		$this->base = intval($base);
	}

	/**
	 * @param int $base
	 * @return void
	 */
	public function setBase($base)
	{
		$this->base = intval($base);
	}

	/**
	 * @param bool $percentages
	 * @return void
	 */
	public function setPercentages($percentages)
	{
		$this->percentages = boolval($percentages);
	}


	/**
	 * @param string $format
	 * @return void
	 */
	public function setFormat($format)
	{
		$this->format = $format;
	}

	/**
	 * @param int $ratio
	 * @param int $base
	 * @return void
	 */
	public function setFloating($ratio = 2, $base = 0)
	{
		if ($ratio && $base) {
			$this->format = '%01.' . $ratio . 'f / %01.' . $base . 'f';
		} else if ($ratio && !$base) {
			$this->format = '%01.' . $ratio . 'f / %d';
		}
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
		if ($this->percentages) {
			$ratio = $data * $this->base;
		} else {
			$ratio = $data;
		}

		return sprintf($this->format, $ratio, $this->base);
	}

}
