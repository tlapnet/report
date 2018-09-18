<?php declare(strict_types = 1);

namespace Tlapnet\Report\Result;

final class Helpers
{

	/**
	 * @return mixed[]
	 */
	public static function toArray(Result $result): array
	{
		// Fetch data
		$data = $result->getData();
		$array = [];

		// Iterate over all rows
		foreach ($data as $rn => $row) {
			// Row has a array of columns?
			if (is_array($row)) {
				$arow = [];

				// Iterate over all columns in a single row
				foreach ($row as $column => $cdata) {
					$arow[$column] = (string) $cdata;
				}
			} else {
				// Scalar one column
				$arow = (string) $row;
			}
			// Append row
			$array[] = $arow;
		}

		return $array;
	}

}
