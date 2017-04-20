<?php

namespace Tlapnet\Report\Result;

interface Mutable extends Resultable
{

	/**
	 * @param mixed $data
	 * @return void
	 */
	public function setData($data);

}
