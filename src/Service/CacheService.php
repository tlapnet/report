<?php

namespace Tlapnet\Report\Service;

use Tlapnet\Report\DataSources\CachedDataSource;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Subreport\Subreport;

class CacheService
{

	/** @var IntrospectionService */
	protected $introspection;

	/**
	 * @param IntrospectionService $introspection
	 */
	public function __construct(IntrospectionService $introspection)
	{
		$this->introspection = $introspection;
	}

	/**
	 * Returns array of subreports which have cached datasource.
	 * All lazy-loaded.
	 *
	 * @return array
	 */
	public function findCached()
	{
		$output = [];
		$introspect = $this->introspection->introspect();
		$tags = $this->introspection->findTags(CachedDataSource::class);

		foreach ($tags as $tag) {
			foreach ($tag as $key => $value) {
				foreach ($introspect as $item) {
					if ($item->tags['services']['subreport'] === $value) {
						$output[] = [
							'name' => $item->rid . '.' . $item->sid,
							'report' => $item->tags['services']['report'],
							'subreport' => $item->tags['services']['subreport'],
							'key' => $item->tags['services']['subreport'],
							'cache' => $item->tags['cache'],
						];
					}
				}
			}
		}

		return $output;
	}

	/**
	 * Warmup subreport cache
	 *
	 * - datasource
	 *
	 * @param string $subreport
	 * @return Resultable
	 */
	public function warmup($subreport)
	{
		/** @var Subreport $subreport */
		$subreport = $this->introspection->getService($subreport);
		$parameters = $subreport->getParameters();

		return $subreport->getDataSource()->compile($parameters);
	}

}
