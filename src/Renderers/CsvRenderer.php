<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Helpers;
use Tlapnet\Report\Result\Result;

class CsvRenderer implements Renderer
{

	/** @var string */
	protected $delimiter = ';';

	public function setDelimiter(string $delimiter): void
	{
		$this->delimiter = $delimiter;
	}

	public function render(Result $result): string
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
