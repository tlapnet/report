<?php

namespace Tlapnet\Report\Service;

use Nette\ArrayHash;
use Nette\DI\Container;
use ReflectionClass;
use Tlapnet\Report\Bridges\Nette\DI\ReportExtension;
use Tlapnet\Report\Utils\Arrays;

class IntrospectionService
{

	/** @var Container */
	protected $container;

	/**
	 * @param Container $container
	 */
	public function __construct(Container $container)
	{
		$this->container = $container;
	}

	/**
	 * INTROSPECTION ***********************************************************
	 */

	/**
	 * Introspection used in panel and etc..
	 *
	 * @return array
	 */
	public function introspect()
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
						'created' => FALSE,
						'service' => NULL,
					],
					'subreport' => (object) [
						'type' => $this->container->getServiceType($tag['services']['subreport']),
						'name' => Arrays::pop(explode('\\', $this->container->getServiceType($tag['services']['subreport']))),
						'created' => FALSE,
						'service' => NULL,
					],
					'datasource' => (object) [
						'type' => $this->container->getServiceType($tag['services']['datasource']),
						'name' => Arrays::pop(explode('\\', $this->container->getServiceType($tag['services']['datasource']))),
						'created' => FALSE,
						'service' => NULL,
					],
					'renderer' => (object) [
						'type' => $this->container->getServiceType($tag['services']['renderer']),
						'name' => Arrays::pop(explode('\\', $this->container->getServiceType($tag['services']['renderer']))),
						'created' => FALSE,
						'service' => NULL,
					],
				],
				'tags' => [],
			]);

			$def->tags = $tag;

			if ((isset($registry[$tag['services']['report']]) && !$registry[$tag['services']['report']] instanceof Container)) {
				$def->services->report->created = TRUE;
				$def->services->report->service = $registry[$tag['services']['report']];
			}

			if ((isset($registry[$tag['services']['subreport']]) && !$registry[$tag['services']['subreport']] instanceof Container)) {
				$def->services->subreport->created = TRUE;
				$def->services->subreport->service = $registry[$tag['services']['subreport']];
			}

			if ((isset($registry[$tag['services']['datasource']]) && !$registry[$tag['services']['datasource']] instanceof Container)) {
				$def->services->datasource->created = TRUE;
				$def->services->datasource->service = $registry[$tag['services']['datasource']];
			}

			if ((isset($registry[$tag['services']['renderer']]) && !$registry[$tag['services']['renderer']] instanceof Container)) {
				$def->services->renderer->created = TRUE;
				$def->services->renderer->service = $registry[$tag['services']['renderer']];
			}
		}

		return $output;
	}

	/**
	 * STATISTICS **************************************************************
	 */

	/**
	 * @return object
	 */
	public function compute()
	{
		return (object) [
			'reports' => count($this->container->findByTag(ReportExtension::TAG_REPORT)),
			'subreports' => count($this->container->findByTag(ReportExtension::TAG_SUBREPORT)),
		];
	}

	/**
	 * HELPERS *****************************************************************
	 */

	/**
	 * @return array
	 */
	protected function getRegistry()
	{
		$prop = (new ReflectionClass(Container::class))->getProperty('registry');
		$prop->setAccessible(TRUE);

		return $prop->getValue($this->container);
	}

	/**
	 * @return array
	 */
	protected function getMeta()
	{
		$prop = (new ReflectionClass(Container::class))->getProperty('meta');
		$prop->setAccessible(TRUE);

		return $prop->getValue($this->container);
	}

	/**
	 * @param string $class
	 * @return array
	 */
	public function findTags($class)
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

	/**
	 * @param string $name
	 * @return object
	 */
	public function getService($name)
	{
		return $this->container->getService($name);
	}

}
