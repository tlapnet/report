<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

use Tlapnet\Report\Utils\DateTime;

final class DatePreprocessor extends AbstractPreprocessor
{

	/** @var string */
	protected $format;

	public function __construct(string $format = 'd.m.Y')
	{
		$this->format = $format;
	}

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function preprocess($data)
	{
		return DateTime::from($data)->format($this->format);
	}

}
