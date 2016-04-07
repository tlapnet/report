<?php

namespace Tlapnet\Report\Model\Box;

interface Reportable
{

	/**
	 * @param array $data
	 * @return void
	 */
	public function attach(array $data);

	/**
	 * @return void
	 */
	public function compile();

	/**
	 * @return mixed
	 */
	public function render();

}
