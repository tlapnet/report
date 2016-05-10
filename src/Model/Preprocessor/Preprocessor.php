<?php

namespace Tlapnet\Report\Model\Preprocessor;

interface Preprocessor
{

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function preprocess($data);

}
