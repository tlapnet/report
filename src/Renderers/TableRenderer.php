<?php

namespace Tlapnet\Report\Renderers;

use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Utils\Html;

class TableRenderer implements Renderer
{

	/**
	 * @param Result $result
	 * @return string
	 */
	public function render(Result $result)
	{
		// Fetch data
		$data = $result->getData();

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
				$td->setHtml((string)$cdata);

				// Append to tr
				$tr->add($td);
			}

			// Add tds to tr
			$table->add($tr);
		}

		return $table;
	}

}
