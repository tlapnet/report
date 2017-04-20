<?php

namespace Tlapnet\Report\Preprocessor\Impl;

use Tlapnet\Report\Utils\DateTime;

final class DatePreprocessor extends AbstractPreprocessor
{

	/** @var string */
	protected $format;

	/**
	 * @param string $format
	 */
	public function __construct($format = 'd.m.Y')
	{
		$this->format = $format;
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
		return DateTime::from($data)->format($this->format);
	}

}
