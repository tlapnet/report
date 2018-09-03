<?php declare(strict_types = 1);

namespace Tlapnet\Report\Subreport;

interface Reportable
{

	/**
	 * @param mixed[] $data
	 */
	public function attach(array $data): void;

	public function compile(): void;

	public function preprocess(): void;

	/**
	 * @return mixed
	 */
	public function render();

}
