<?php declare(strict_types = 1);

namespace Tlapnet\Report\Service;

use Nette\DI\Container;
use Nette\Utils\ArrayHash;
use ReflectionClass;
use Tlapnet\Report\Bridges\Nette\DI\ReportExtension;
use Tlapnet\Report\Utils\Arrays;

class IntrospectionService
{

	/** @var Container */
	protected $container;

	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * Introspection used in panel and etc..
	 *
	 * @return mixed[]
	 */
	public function introspect(): array
	{
		$output = [];
		$registry = $this->getRegistry();
		$subreports = $this->container->findByTag(ReportExtension::TAG_INTROSPECTION);
		foreach ($subreports as $service => $tag) {
			$output[] = $def = ArrayHash::from([
				'id' => 'rp' . md5(serialize($tag)),
				'file' => $tag['file'],
				'rid' => $tag['rid'],
				'sid' => $tag['sid'],
				'metadata' => $tag['metadata'],
				'services' => [
					'report' => (object) [
						'type' => $this->container->getServiceType($tag['services']['report']),
						'name' => Arrays::pop(explode('\\', $this->container->getServiceType($tag['services']['report']))),
						'created' => false,
						'service' => null,
					],
					'subreport' => (object) [
						'type' => $this->container->getServiceType($tag['services']['subreport']),
						'name' => Arrays::pop(explode('\\', $this->container->getServiceType($tag['services']['subreport']))),
						'created' => false,
						'service' => null,
					],
					'datasource' => (object) [
						'type' => $this->container->getServiceType($tag['services']['datasource']),
						'name' => Arrays::pop(explode('\\', $this->container->getServiceType($tag['services']['datasource']))),
						'created' => false,
						'service' => null,
					],
					'renderer' => (object) [
						'type' => $this->container->getServiceType($tag['services']['renderer']),
						'name' => Arrays::pop(explode('\\', $this->container->getServiceType($tag['services']['renderer']))),
						'created' => false,
						'service' => null,
					],
				],
				'tags' => [],
			]);

			$def->tags = $tag;

			if ((isset($registry[$tag['services']['report']]) && !$registry[$tag['services']['report']] instanceof Container)) {
				$def->services->report->created = true;
				$def->services->report->service = $registry[$tag['services']['report']];
			}

			if ((isset($registry[$tag['services']['subreport']]) && !$registry[$tag['services']['subreport']] instanceof Container)) {
				$def->services->subreport->created = true;
				$def->services->subreport->service = $registry[$tag['services']['subreport']];
			}

			if ((isset($registry[$tag['services']['datasource']]) && !$registry[$tag['services']['datasource']] instanceof Container)) {
				$def->services->datasource->created = true;
				$def->services->datasource->service = $registry[$tag['services']['datasource']];
			}

			if ((isset($registry[$tag['services']['renderer']]) && !$registry[$tag['services']['renderer']] instanceof Container)) {
				$def->services->renderer->created = true;
				$def->services->renderer->service = $registry[$tag['services']['renderer']];
			}
		}

		return $output;
	}

	public function compute(): object
	{
		return (object) [
			'reports' => count($this->container->findByTag(ReportExtension::TAG_REPORT)),
			'subreports' => count($this->container->findByTag(ReportExtension::TAG_SUBREPORT)),
		];
	}

	/**
	 * @return mixed[]
	 */
	protected function getRegistry(): array
	{
		$prop = (new ReflectionClass(Container::class))->getProperty('registry');
		$prop->setAccessible(true);

		return $prop->getValue($this->container);
	}

	/**
	 * @return mixed[]
	 */
	protected function getMeta(): array
	{
		$prop = (new ReflectionClass(Container::class))->getProperty('meta');
		$prop->setAccessible(true);

		return $prop->getValue($this->container);
	}

	/**
	 * @return mixed[]
	 */
	public function findTags(string $class): array
	{
		$output = [];
		$services = $this->container->findByType($class);
		$meta = $this->getMeta();

		foreach ($services as $service) {
			$output[$service] = [];
			foreach ($meta[Container::TAGS] as $tag => $structure) {
				if (!isset($structure[$service])) continue;
				$output[$service][$tag] = $structure[$service];
			}
		}

		return $output;
	}

	public function getService(string $name): object
	{
		return $this->container->getService($name);
	}

}
