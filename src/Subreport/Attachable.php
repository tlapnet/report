<?php

namespace Tlapnet\Report\Subreport;

interface Attachable
{

	/**
	 * @param array $data
	 * @return void
	 */
	public function attach(array $data);

}
