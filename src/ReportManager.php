<?php

namespace Tlapnet\Report;

use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Collection\Collection;
use Tlapnet\Report\Utils\Suggestions;

class ReportManager
{

	/** @var Collection[] */
	private $collections = [];

	/**
	 * @param Collection $collection
	 */
	public function addCollection(Collection $collection)
	{
		$this->collections[$collection->getCid()] = $collection;
	}

	/**
	 * @return Collection[]
	 */
	public function getCollections()
	{
		return $this->collections;
	}

	/**
	 * @param string $cid
	 * @return Collection
	 */
	public function getCollection($cid)
	{
		if (isset($this->collections[$cid])) {
			return $this->collections[$cid];
		}

		$hint = Suggestions::getSuggestion(array_map(function (Collection $collection) {
			return $collection->getCid();
		}, $this->collections), $cid);

		throw new InvalidStateException("Collection '$cid' not found" . ($hint ? ", did you mean '$hint'?" : '.'));
	}

	/**
	 * @param string $cid
	 * @return bool
	 */
	public function hasCollection($cid)
	{
		return isset($this->collections[$cid]);
	}

}
