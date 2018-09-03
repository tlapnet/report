<?php declare(strict_types = 1);

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Utils\Html;

class TableRenderer implements Renderer
{

	public function render(Result $result): Html
	{
		// Fetch data
		$data = $result->getData();

		// Do we have any data?
		if ($data === []) {
			return Html::el('div')->setText('No data found');
		}

		// Create root element
		$table = Html::el('table')
			->addAttributes(['class' => 'table']);

		// Create header
		$header = Html::el('tr');
		foreach ($data[0] as $column => $v) {
			// Create th element
			$th = Html::el('th')
				->setHtml($column);

			// Append to header
			$header->add($th);
		}
		$table->add($header);

		// Iterate over all rows
		foreach ($data as $row) {

			// Creat tr element
			$tr = Html::el('tr');

			// Iterate over all columns
			foreach ($row as $column => $cdata) {

				// Create td element
				$td = Html::el('td');
				$td->setHtml((string) $cdata);

				// Append to tr
				$tr->add($td);
			}

			// Add tds to tr
			$table->add($tr);
		}

		return $table;
	}

}
