<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Adapters\NeonAdapter;
use Nette\DI\Helpers;
use Nette\DI\Statement;
use Nette\PhpGenerator\ClassType;
use Nette\Utils\Arrays;
use Nette\Utils\AssertionException;
use Nette\Utils\Finder;
use Nette\Utils\Validators;
use SplFileInfo;
use Tlapnet\Report\Bridges\Nette\Exceptions\FileNotFoundException;
use Tlapnet\Report\Bridges\Nette\Exceptions\FolderNotFoundException;
use Tlapnet\Report\Bridges\Nette\Exceptions\InvalidConfigException;
use Tlapnet\Report\Bridges\Tracy\Export\DebugExporter;
use Tlapnet\Report\Bridges\Tracy\Panel\ReportPanel;
use Tlapnet\Report\DataSources\CachedDataSource;
use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Group\Group;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Parameters\ParametersBuilder;
use Tlapnet\Report\Parameters\ParametersFactory;
use Tlapnet\Report\Report\LazyReport;
use Tlapnet\Report\ReportManager;
use Tlapnet\Report\Service\CacheInspector;
use Tlapnet\Report\Service\CacheService;
use Tlapnet\Report\Service\IntrospectionService;
use Tlapnet\Report\Service\ReportService;
use Tlapnet\Report\Subreport\Subreport;

class ReportExtension extends CompilerExtension
{

	// Subreport introspection (metadata, services, etc..)
	public const TAG_INTROSPECTION = 'report.introspecition';

	// Name of the report
	public const TAG_REPORT = 'report.report';

	// Name of the subreport
	public const TAG_SUBREPORT = 'report.subreport';

	// Tag points to report service
	public const TAG_SUBREPORT_PARENT = 'report.subreport.parent';

	// Tags points to subreport service
	public const TAG_SUBREPORT_DATASOURCE = 'report.subreport.datasource';
	public const TAG_SUBREPORT_RENDERER = 'report.subreport.renderer';
	public const TAG_SUBREPORT_PARAMETERS = 'report.subreport.paremeters';

	// Subreport cache tag
	public const TAG_CACHE = 'report.cache';

	/** @var mixed[] */
	protected $defaults = [
		'debug' => false,
		'files' => [],
		'reports' => [],
		'folders' => [],
		'groups' => [],
		'definitions' => [],
	];

	/** @var mixed[] */
	protected $configuration = [
		'files' => [],
		'groups' => [],
		'reports' => [],
	];

	/** @var mixed[] */
	protected $metadata = [
		'reports' => [],
		'subreports' => [],
	];

	/** @var mixed[] */
	protected $scheme = [
		'report' => [
			'groups' => [],
			'metadata' => [
				'menu' => null,
				'title' => null,
				'description' => null,
			],
			'subreports' => null,
			'subreport' => null,
		],
		'subreport' => [
			'metadata' => [
				'title' => null,
				'description' => null,
			],
			'renderer' => null,
			'datasource' => null,
			'params' => null,
			'preprocessors' => [],
			'exports' => [],
		],
		'tags' => [
			self::TAG_CACHE => [
				'key' => null,
				'expiration' => null,
			],
		],
	];

	/** @var bool */
	private $debugMode;

	public function __construct(bool $debugMode = false)
	{
		$this->debugMode = $debugMode;
	}

	public function loadConfiguration(): void
	{
		$config = $this->getMainConfig();
		$builder = $this->getContainerBuilder();

		// Assert config
		Validators::assert($config['folders'], 'array', "'folders'");
		Validators::assert($config['files'], 'array', "'files'");
		Validators::assert($config['reports'], 'array', "'reports'");
		Validators::assert($config['groups'], 'array', "'groups'");
		Validators::assert($config['definitions'], 'array', "'definitions'");

		// =====================================================================

		// Setup ReportManager which holds all ReportGroups
		$builder->addDefinition($this->prefix('manager'))
			->setFactory(ReportManager::class);

		$builder->addDefinition($this->prefix('service.report'))
			->setFactory(ReportService::class);

		$builder->addDefinition($this->prefix('service.cache'))
			->setFactory(CacheService::class);

		$builder->addDefinition($this->prefix('service.cache.inspector'))
			->setFactory(CacheInspector::class);

		$builder->addDefinition($this->prefix('service.introspection'))
			->setFactory(IntrospectionService::class);

		$builder->addDefinition($this->prefix('panel'))
			->setFactory(ReportPanel::class);

		// Load common services
		if ($config['definitions'] !== []) {
			$services = $config['definitions'];
			Compiler::loadDefinitions($builder, $services, 'report');
		}

		// Load groups
		if ($config['groups'] !== []) {
			$this->loadGroups($config['groups']);
		}

		// Load from folders
		if ($config['folders'] !== []) {
			$this->loadReportsFromFolders($config['folders']);
		}

		// Load from files
		if ($config['files'] !== []) {
			$this->loadReportsFromFiles($config['files']);
		}

		// Load from config
		if ($config['reports'] !== []) {
			$this->loadReportsFromConfig($config['reports']);
		}
	}

	public function beforeCompile(): void
	{
		$this->decorateSubreports();
	}

	public function afterCompile(ClassType $class): void
	{
		$class->getMethod('initialize')
			->addBody('$this->getService(?)->addPanel($this->getByType(?));', ['tracy.bar', ReportPanel::class]);
	}

	/**
	 * @param string[] $groups
	 */
	protected function loadGroups(array $groups): void
	{
		$builder = $this->getContainerBuilder();
		$managerDef = $builder->getDefinition($this->prefix('manager'));

		foreach ($groups as $gid => $name) {

			// Check duplicates
			if (in_array($gid, $this->configuration['groups'], true)) throw new AssertionException(sprintf('Duplicate groups "%s.%s"', $gid, $name));

			// Append to definitions
			$this->configuration['groups'][] = $gid;

			// Create group definition
			$groupDef = $builder->addDefinition($this->prefix('groups.' . $gid))
				->setFactory(Group::class, [
					'gid' => $gid,
					'name' => $name,
				]);

			// Do not autowire!
			$groupDef->setAutowired(false);

			// Append to configuration
			$this->configuration['groups'][$gid] = $groupDef;

			// Append to manager
			$managerDef->addSetup('addGroup', [$groupDef]);
		}
	}

	/**
	 * @param string[] $folders
	 */
	protected function loadReportsFromFolders(array $folders): void
	{
		$files = [];
		foreach ($folders as $folder) {
			// Validate folder
			if (!is_dir($folder)) throw new FolderNotFoundException(sprintf('Folder "%s" not found', $folder));

			// Find all configs
			foreach (Finder::findFiles('*.neon')->from($folder) as $file) {
				if (is_string($file)) {
					$files[] = $file;
				} else {
					/** @var SplFileInfo $file */
					$files[] = $file->getRealPath();
				}
			}
		}

		// make files order environment independent
		usort($files, function ($a, $b) {
			return strcmp(pathinfo($a, PATHINFO_FILENAME), pathinfo($b, PATHINFO_FILENAME));
		});

		$this->loadReportsFromFiles($files);
	}

	/**
	 * @param string[] $files
	 */
	protected function loadReportsFromFiles(array $files): void
	{
		$loader = new NeonAdapter();
		$reports = [];

		foreach ($files as $file) {
			// Validate file
			if (!file_exists($file)) throw new FileNotFoundException(sprintf('File "%s" not found', $file));

			// File is included as report section
			$filedata = $loader->load($file);

			// Check if report has a appropriate configuration
			if ($filedata === []) throw new InvalidConfigException(sprintf('Report configuration (%s) cannot be empty', $file));

			// Check if report has a appropriate configuration
			if (count($filedata) > 1) throw new InvalidConfigException('Report must have a name. Specific root node as name of report');

			$name = key($filedata);
			$report = $filedata[$name];

			// Check duplicates
			if (isset($reports[$name])) throw new AssertionException(sprintf('Duplicate reports "%s"', $name));

			// Append to reports
			$reports[$name] = $report;

			// Append to files
			$this->configuration['files'][$name] = $file;
		}

		// Load reports
		$this->loadReportsFromConfig($reports);

		// Add as dependencies
		$this->compiler->addDependencies($files);
	}

	/**
	 * @param mixed[] $reports
	 */
	protected function loadReportsFromConfig(array $reports): void
	{
		$builder = $this->getContainerBuilder();
		$globalconfig = $builder->parameters;

		foreach ($reports as $rid => $report) {
			// Get report config
			$report = $this->validateConfig($this->scheme['report'], $report, $rid);

			// =================================================================

			// Validate report keys (subreport vs subreports)
			if (is_array($report['subreports']) && is_array($report['subreport'])) {
				throw new AssertionException(sprintf('You cannot fill keys %s.subreports and %s.subreport at the same time.', $rid, $rid));
			}

			if (is_array($report['subreport'])) {
				$report['subreports'] = [$report['subreport']];
				unset($report['subreport']);
			}

			Validators::assertField($report, 'metadata', 'array', sprintf('item "%%" in %s', $rid));
			Validators::assertField($report, 'groups', 'array', sprintf('item "%%" in %s', $rid));
			Validators::assertField($report, 'subreports', 'array', sprintf('item "%%" in %s', $rid));

			// Validate report metadata
			$this->validateConfig($this->scheme['report']['metadata'], $report['metadata'], sprintf('%s.metadata', $rid));

			// Autofill metadata
			if (!isset($report['metadata']['menu']) && isset($report['metadata']['title'])) {
				$report['metadata']['menu'] = $report['metadata']['title'];
			}
			if (!isset($report['metadata']['title']) && isset($report['metadata']['menu'])) {
				$report['metadata']['title'] = $report['metadata']['menu'];
			}
			if (!isset($report['metadata']['menu'])) {
				$report['metadata']['menu'] = $rid;
			}

			// Check duplicates
			if (in_array($rid, $this->configuration['reports'], true)) throw new AssertionException(sprintf('Duplicate reports "%s"', $rid));
			$this->configuration['reports'][] = $rid;

			// =================================================================

			// Add report
			$reportDef = $builder->addDefinition($this->prefix('reports.' . $rid))
				->setFactory(LazyReport::class, ['rid' => $rid])
				->setTags([self::TAG_REPORT => $this->prefix('reports.' . $rid)]);

			// Add report metadata
			$this->metadata['reports'][$rid] = [];
			foreach ((array) $report['metadata'] as $key => $value) {
				// Skip empty values
				if (in_array($value, ['', [], null], true)) continue;
				// Append and expand parameters
				$reportDef->addSetup('setOption', [$key, Helpers::expand($value, $globalconfig)]);
				$this->metadata['reports'][$rid][$key] = Helpers::expand($value, $globalconfig);
			}

			// Check if report is groupless,
			// otherwise add report to groups
			if ($report['groups'] === []) {
				$builder->getDefinition($this->prefix('manager'))
					->addSetup('addGroupless', [$reportDef]);
			} else {
				foreach ($report['groups'] as $gid) {
					// Validate groups
					if (!in_array($gid, $this->configuration['groups'], true)) throw new AssertionException(sprintf('Group %s defined in %s.groups does not exist', $gid, $rid));

					$builder->getDefinition($this->prefix('groups.' . $gid))
						->addSetup('addReport', [$reportDef]);
				}
			}

			// Load all subreports from this report
			$this->loadSubreports($rid, $report['subreports']);
		}
	}

	/**
	 * @param mixed[] $subreports
	 */
	protected function loadSubreports(string $rid, array $subreports): void
	{
		foreach ($subreports as $sid => $subreport) {
			if (is_array($subreport)) {
				// Array
				$this->loadSubreport($rid, $sid, $subreport);
			} else {
				// One-class factory
				$this->loadSubreportFactory($rid, $sid, $subreport);
			}
		}
	}

	/**
	 * @param mixed[] $subreport
	 */
	protected function loadSubreport(string $rid, string $sid, array $subreport): void
	{
		// Name of the report (report ID + _ + subreport ID)
		$name = $rid . '_' . $sid;

		// Get subreport config
		$subreport = $this->validateConfig($this->scheme['subreport'], $subreport, sprintf('%s.subreports.%s', $rid, $sid));

		// =====================================================================

		Validators::assertField($subreport, 'metadata', 'array', sprintf('item "%%" in %s subreport %s', $rid, $sid));
		Validators::assertField($subreport, 'params', 'array|null', sprintf('item "%%" in %s subreport %s', $rid, $sid));
		Validators::assertField($subreport, 'datasource', null, sprintf('item "%%" in %s subreport %s', $rid, $sid));
		Validators::assertField($subreport, 'renderer', null, sprintf('item "%%" in %s subreport %s', $rid, $sid));
		Validators::assertField($subreport, 'preprocessors', 'array', sprintf('item "%%" in %s subreport %s', $rid, $sid));
		Validators::assertField($subreport, 'exports', 'array', sprintf('item "%%" in %s subreport %s', $rid, $sid));

		// Validate subreport metadata
		$this->validateConfig($this->scheme['subreport']['metadata'], $subreport['metadata'], sprintf('%s.subreports.%s.metadata', $rid, $sid));

		// =====================================================================

		// Prepare builder
		$builder = $this->getContainerBuilder();
		$config = $this->getMainConfig();
		$globalconfig = $builder->parameters;

		// Create parameters service
		$parametersDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.parameters'));
		$parametersDef->setFactory(Parameters::class);
		$parametersDef->setAutowired(false);
		$parametersDef->addTag(self::TAG_SUBREPORT_PARAMETERS, $this->prefix('subreports.' . $name));

		// Use
		if ($subreport['params'] !== null && $subreport['params'] !== [] && isset($subreport['params']['builder'])) {
			// ParametersBuilder
			$parametersBuilderDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.parameters.builder'));
			$parametersBuilderDef->setFactory(ParametersBuilder::class);
			$parametersBuilderDef->setAutowired(false);
			// Parse setup
			Compiler::loadDefinition($parametersBuilderDef, ['setup' => $subreport['params']['builder']]);
			// Set ParametersBuilder as factory for Parameters
			$parametersDef->setFactory('@' . $this->prefix('subreports.' . $name . '.parameters.builder') . '::build');
		} elseif ($subreport['params'] !== null && $subreport['params'] !== []) {
			// Parse service
			Compiler::loadDefinition($parametersDef, $subreport['params']);
		} else {
			// If params are not filled
			$parametersDef->setFactory(ParametersFactory::class . '::create', [[]]);
		}

		// Create datasource service
		$datasourceDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.datasource'));
		Compiler::loadDefinition($datasourceDef, $subreport['datasource']);
		$datasourceDef->setAutowired(false);
		$datasourceDef->addTag(self::TAG_SUBREPORT_DATASOURCE, $this->prefix('subreports.' . $name));

		// Create renderer service
		$rendererDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.renderer'));
		Compiler::loadDefinition($rendererDef, $subreport['renderer']);
		$rendererDef->setAutowired(false);
		$rendererDef->addTag(self::TAG_SUBREPORT_RENDERER, $this->prefix('subreports.' . $name));

		// Create Subreport
		$subreportDef = $builder->addDefinition($this->prefix('subreports.' . $name))
			->setFactory(Subreport::class, [
				'sid' => $name,
				'parameters' => '@' . $this->prefix('subreports.' . $name . '.parameters'),
				'dataSource' => '@' . $this->prefix('subreports.' . $name . '.datasource'),
				'renderer' => '@' . $this->prefix('subreports.' . $name . '.renderer'),
			]);

		// Add metadata
		$this->metadata['subreports'][$name] = [];
		foreach ((array) $subreport['metadata'] as $key => $value) {
			// Skip empty values
			if (in_array($value, ['', [], null], true)) continue;
			// Append and expand parameters
			$subreportDef->addSetup('setOption', [$key, Helpers::expand($value, $globalconfig)]);
			$this->metadata['subreports'][$name][$key] = Helpers::expand($value, $globalconfig);
		}

		// Add preprocessors
		foreach ($subreport['preprocessors'] as $column => $preprocessors) {
			foreach ((array) $preprocessors as $key => $preprocessor) {
				$pname = $column . '_' . $key;
				$preprocessorDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.preprocessor.' . $pname));
				Compiler::loadDefinition($preprocessorDef, $preprocessor);
				$subreportDef->addSetup('addPreprocessor', [$column, $preprocessorDef]);
			}
		}

		// Add exports
		if ($config['debug'] === true) {
			$subreport['exports']['debug'] = [
				'class' => DebugExporter::class,
				'setup' => [
					new Statement('setOption', ['title', 'DEBUG']),
					new Statement('setOption', ['class', 'btn btn-danger']),
					new Statement('setOption', ['icon', 'icon-bug']),
				]];
		}
		foreach ($subreport['exports'] as $key => $export) {
			$exportDef = $builder->addDefinition($this->prefix('subreports.' . $key . '.export.' . $name));
			Compiler::loadDefinition($exportDef, $export);
			$subreportDef->addSetup('addExporter', [$key, $exportDef]);
		}

		// Setup subreports tags
		$subreportDef->setTags([
			self::TAG_INTROSPECTION => [
				'file' => Arrays::get($this->configuration['files'], $rid, null),
				'rid' => $rid,
				'sid' => $sid,
				'services' => [
					'report' => $this->prefix('reports.' . $rid),
					'subreport' => $this->prefix('subreports.' . $name),
					'datasource' => $this->prefix('subreports.' . $name . '.datasource'),
					'renderer' => $this->prefix('subreports.' . $name . '.renderer'),
					'parameters' => $this->prefix('subreports.' . $name . '.paremeters'),
				],
				'metadata' => [
					'report' => $this->metadata['reports'][$rid],
					'subreport' => $subreport['metadata'],
				],
			],
			self::TAG_SUBREPORT => $name,
			self::TAG_SUBREPORT_PARENT => $rid,
		]);

		// Add subreport to report
		$builder->getDefinition($this->prefix('reports.' . $rid))
			->addSetup('addLazySubreport', [
				$name,
				new Statement('function(){return ?->getService(?);}', ['@container', $this->prefix('subreports.' . $name)]),
			]);
	}

	/**
	 * @param mixed $subreport
	 */
	protected function loadSubreportFactory(string $rid, string $sid, $subreport): void
	{
		// Name of the report (report ID + _ + subreport ID)
		$name = $rid . '_' . $sid;

		// Prepare builder
		$builder = $this->getContainerBuilder();

		// Parse subreport service
		$subreportDef = $builder->addDefinition($this->prefix('subreports.' . $name));
		Compiler::loadDefinition($subreportDef, $subreport);

		// Add subreport to report
		$builder->getDefinition($this->prefix('reports.' . $rid))
			->addSetup('addSubreport', [new Statement('@' . $this->prefix('subreports.' . $name) . '::create')]);
	}

	/**
	 * Decorate subreports
	 * - subreport datasource
	 */
	protected function decorateSubreports(): void
	{
		$this->decorateSubreportsDataSource();
	}

	/**
	 * Make subreport cachable
	 * - cache tags
	 */
	protected function decorateSubreportsDataSource(): void
	{
		$builder = $this->getContainerBuilder();

		$subreports = $builder->findByTag(self::TAG_CACHE);
		foreach ($subreports as $service => $tag) {
			$serviceDef = $builder->getDefinition($service);

			// Validate datasource class
			if (!is_subclass_of((string) $serviceDef->getType(), DataSource::class)) {
				throw new AssertionException(sprintf(
					'Please use tag "%s" only on datasource (object %s found in %s).',
					self::TAG_CACHE,
					$serviceDef->getType(),
					$service
				));
			}

			// If cache.key is not provided, pick subreport name (fullname)
			if (!isset($tag['key'])) $tag['key'] = $serviceDef->getTag(self::TAG_SUBREPORT_DATASOURCE);

			// Validate cache scheme
			$this->validateConfig($this->scheme['tags'][self::TAG_CACHE], $tag, sprintf('%s', self::TAG_CACHE));

			// Wrap factory to cache
			$wrappedDef = $builder->addDefinition(sprintf('%s_cached', $service), $serviceDef);

			// Remove definition
			$builder->removeDefinition($service);

			// Add cached defition of datasource with wrapped original datasource
			$builder->addDefinition($service)
				->setFactory(CachedDataSource::class, [1 => $wrappedDef])
				->addSetup('setKey', [$tag['key']])
				->addSetup('setExpiration', [$tag['expiration']])
				->addTag(self::TAG_SUBREPORT_DATASOURCE, $wrappedDef->getTag(self::TAG_SUBREPORT_DATASOURCE));

			// Append cache tags to subreport introspection
			$subreportDef = $builder->getDefinition($wrappedDef->getTag(self::TAG_SUBREPORT_DATASOURCE));
			$introspection = $subreportDef->getTag(self::TAG_INTROSPECTION);
			$introspection['cache'] = ['datasource' => $tag];
			$subreportDef->addTag(self::TAG_INTROSPECTION, $introspection);
		}
	}

	/**
	 * @return mixed[]
	 */
	private function getMainConfig(): array
	{
		$config = $this->validateConfig($this->defaults);

		if ($this->debugMode === true) {
			$config['debug'] = $this->debugMode;
		}

		return $config;
	}

}
