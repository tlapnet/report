<?php

namespace Tlapnet\Report\Model\Data;

use Tlapnet\Report\Model\Data\Result;

final class Helpers
{

	/**
	 * @param Result $result
	 * @return array
	 */
	public static function toArray(Result $result)
	{
		// Fetch data
		$data = $result->getData();
		$array = [];

		// Iterate over all rows
		foreach ($data as $rn => $row) {
			$arow = [];

			// Iterate over all columns in a single row
			foreach ((array)$row as $column => $cdata) {
				$arow[$column] = (string)$cdata;
			}

			// Append row
			$array[] = $arow;
		}

		return $array;
	}

}