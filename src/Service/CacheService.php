<?php

namespace Tlapnet\Report\Service;

use Tlapnet\Report\DataSources\CachedDataSource;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Subreport\Subreport;
use Tlapnet\Report\Utils\Arrays;

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
					if ($item->subreport === $value) {
						$output[] = [
							'name' => Arrays::pop(explode('.', $item->report)) . '.' . Arrays::pop(explode('.', $item->subreport)),
							'report' => $item->report,
							'subreport' => $item->subreport,
						];
					}
				}
			}
		}

		return $output;
	}

	/**
	 * Warmup subreport datasource
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
