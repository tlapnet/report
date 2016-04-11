<?php

namespace Tlapnet\Report\Model\Subreport;

interface Attachable
{

	/**
	 * @param array $data
	 * @return void
	 */
	public function attach(array $data);

}
