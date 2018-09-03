<?php declare(strict_types = 1);

namespace Tlapnet\Report\Service;

use Tlapnet\Report\DataSources\CachedDataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Subreport\Subreport;

class CacheService
{

	/** @var IntrospectionService */
	protected $introspection;

	public function __construct(IntrospectionService $introspection)
	{
		$this->introspection = $introspection;
	}

	/**
	 * Returns array of subreports which have cached datasource. All lazy-loaded.
	 *
	 * @return mixed[]
	 */
	public function findCached(): array
	{
		$output = [];
		$introspect = $this->introspection->introspect();
		$tags = $this->introspection->findTags(CachedDataSource::class);

		foreach ($tags as $tag) {
			foreach ($tag as $key => $value) {
				foreach ($introspect as $item) {
					if ($item->tags['services']['subreport'] === $value) {
						$output[] = [
							'id' => $item->rid . '.' . $item->sid,
							'report_id' => $item->rid,
							'subreport_id' => $item->sid,
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
	 */
	public function warmupSubreport(string $subreport): Resultable
	{
		$subreport = $this->introspection->getService($subreport);

		if (!($subreport instanceof Subreport)) {
			throw new InvalidStateException(sprintf('Service "%s" is not subreport', get_class($subreport)));
		}

		return $subreport->getDataSource()->compile($subreport->getParameters());
	}

}
