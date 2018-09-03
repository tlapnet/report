<?php declare(strict_types = 1);

namespace Tlapnet\Report\Preprocessor\Impl;

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
