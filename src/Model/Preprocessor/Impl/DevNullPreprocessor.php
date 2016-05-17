<?php

namespace Tlapnet\Report\Model\Preprocessor\Impl;

final class DevNullPreprocessor extends AbstractPreprocessor
{

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function preprocess($data)
	{
		return $data;
	}

}
