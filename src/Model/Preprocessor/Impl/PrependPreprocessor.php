<?php

namespace Tlapnet\Report\Model\Preprocessor\Impl;

final class PrependPreprocessor extends AbstractPreprocessor
{

	/** @var mixed */
	protected $prepend;

	/**
	 * @param mixed $prepend
	 */
	public function __construct($prepend)
	{
		$this->prepend = $prepend;
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
		if ($this->prepend) {
			return (string) $this->prepend . $data;
		} else {
			return $data;
		}
	}

}
