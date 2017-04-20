<?php

namespace Tlapnet\Report\Subreport;

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
	 * @return void
	 */
	public function preprocess();

	/**
	 * @return mixed
	 */
	public function render();

}
