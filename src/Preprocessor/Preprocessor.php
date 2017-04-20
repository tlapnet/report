<?php

namespace Tlapnet\Report\Preprocessor;

interface Preprocessor
{

	/**
	 * @param mixed $data
	 * @return mixed
	 */
	public function preprocess($data);

}
