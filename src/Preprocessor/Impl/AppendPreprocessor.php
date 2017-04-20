<?php

namespace Tlapnet\Report\Preprocessor\Impl;

final class AppendPreprocessor extends AbstractPreprocessor
{

	/** @var mixed */
	protected $append;

	/**
	 * @param mixed $append
	 */
	public function __construct($append)
	{
		$this->append = $append;
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
		if ($this->append) {
			return $data . ((string) $this->append);
		} else {
			return $data;
		}
	}

}
