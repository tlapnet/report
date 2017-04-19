<?php

namespace Tlapnet\Report\Model\Result;

interface Mutable extends Resultable
{

	/**
	 * @param mixed $data
	 * @return void
	 */
	public function setData($data);

}
