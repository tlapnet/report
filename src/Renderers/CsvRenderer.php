<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Result\Helpers;
use Tlapnet\Report\Model\Result\Result;

class CsvRenderer implements Renderer
{

	/** @var string */
	protected $delimiter = ';';

	/**
	 * @param string $delimiter
	 */
	public function setDelimiter($delimiter)
	{
		$this->delimiter = $delimiter;
	}

	/**
	 * RENDERING ***************************************************************
	 */

	/**
	 * @param Result $result
	 * @return string
	 */
	public function render(Result $result)
	{
		$data = Helpers::toArray($result);
		$csv = [];

		// Headers
		$header = [];
		foreach ($data[0] as $column => $v) {
			$header[] = '"' . $column . '"';
		}
		$csv[] = implode($this->delimiter, $header);

		// Content
		foreach ($data as $row) {
			$crow = [];
			foreach ($row as $c => $value) {
				$crow[] = '"' . $value . '"';
			}
			$csv[] = implode($this->delimiter, $crow);
		}

		return implode("\n", $csv);
	}

}
