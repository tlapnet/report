<?php

namespace Tlapnet\Report\Bridges\Nette\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Adapters\NeonAdapter;
use Nette\DI\Statement;
use Nette\Utils\AssertionException;
use Nette\Utils\Finder;
use Nette\Utils\Validators;
use Tlapnet\Report\Bridges\Nette\Exceptions\FileNotFoundException;
use Tlapnet\Report\Bridges\Nette\Exceptions\FolderNotFoundException;
use Tlapnet\Report\Bridges\Nette\Exceptions\InvalidConfigException;
use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Parameters\ParametersBuilder;
use Tlapnet\Report\Model\Parameters\ParametersFactory;
use Tlapnet\Report\Model\Report\Report;
use Tlapnet\Report\Model\ReportService;
use Tlapnet\Report\Model\Subreport\Subreport;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\ReportManager;

class ReportExtension extends CompilerExtension
{

	/** @var array */
	protected $defaults = [
		'files' => [],
		'reports' => [],
		'folders' => [],
		'groups' => [],
		'definitions' => [],
	];

	/** @var array */
	protected $configuration = [
		'groups' => [],
		'reports' => [],
	];

	/** @var array */
	protected $scheme = [
		'report' => [
			'groups' => [],
			'metadata' => [],
			'subreports' => NULL,
			'subreport' => NULL,
		],
		'subreport' => [
			'metadata' => [],
			'renderer' => NULL,
			'datasource' => NULL,
			'params' => NULL,
			'preprocessors' => [],
		],
	];

	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
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
			->setClass(ReportManager::class);

		$builder->addDefinition($this->prefix('service'))
			->setClass(ReportService::class);

		// Load common services
		if ($config['definitions']) {
			// Temporary fix fox services inner extension..
			$services = ['services' => $config['definitions']];
			Compiler::parseServices($builder, $services, 'report');
		}

		// Load groups
		if ($config['groups']) {
			$this->loadGroups($config['groups']);
		}

		// Load from folders
		if ($config['folders']) {
			$this->loadReportsFromFolders($config['folders']);
		}

		// Load from files
		if ($config['files']) {
			$this->loadReportsFromFiles($config['files']);
		}

		// Load from config
		if ($config['reports']) {
			$this->loadReportsFromConfig($config['reports']);
		}
	}

	/**
	 * @param array $groups
	 */
	protected function loadGroups(array $groups)
	{
		$builder = $this->getContainerBuilder();
		$managerDef = $builder->getDefinition($this->prefix('manager'));

		foreach ($groups as $gid => $name) {

			// Check duplicates
			if (in_array($gid, $this->configuration['groups'])) throw new AssertionException("Duplicate groups $gid.$name");

			// Append to definitions
			$this->configuration['groups'][] = $gid;

			// Create group definition
			$groupDef = $builder->addDefinition($this->prefix('groups.' . $gid))
				->setClass(Group::class, [
					'gid' => $gid,
					'name' => $name,
				]);

			// Do not autowire!
			$groupDef->setAutowired(FALSE);

			// Append to configuration
			$this->configuration['groups'][$gid] = $groupDef;

			// Append to manager
			$managerDef->addSetup('addGroup', [$groupDef]);
		}
	}

	/**
	 * @param array $folders
	 */
	protected function loadReportsFromFolders(array $folders)
	{
		$files = [];
		foreach ($folders as $folder) {
			// Validate folder
			if (!is_dir($folder)) throw new FolderNotFoundException("Folder $folder not found'");

			// Find all configs
			foreach (Finder::findFiles('*.neon')->from($folder) as $file) {
				$files[] = $file;
			}
		}

		$this->loadReportsFromFiles($files);
	}

	/**
	 * @param array $files
	 */
	protected function loadReportsFromFiles(array $files)
	{
		$loader = new NeonAdapter();
		$reports = [];

		foreach ($files as $file) {
			// Validate file
			if (!file_exists($file)) throw new FileNotFoundException("File '$file' not found");

			// File is included as report section
			$filedata = $loader->load($file);

			// Check if report has a appropriate configuration
			if (!$filedata || count($filedata) <= 0) throw new InvalidConfigException('Report configuration cannot be empty');

			// Check if report has a appropriate configuration
			if (count($filedata) > 1) throw new InvalidConfigException('Report must have a name. Specific root node as name of report');

			$name = key($filedata);
			$report = $filedata[$name];

			// Check duplicates
			if (isset($reports[$name])) throw new AssertionException("Duplicate reports '$name'");

			// Append to reports
			$reports[$name] = $report;
		}

		// Load reports
		$this->loadReportsFromConfig($reports);

		// Add as dependencies
		$this->compiler->addDependencies($files);
	}

	/**
	 * @param array $reports
	 */
	protected function loadReportsFromConfig(array $reports)
	{
		$builder = $this->getContainerBuilder();

		foreach ($reports as $rid => $report) {
			// Get report config
			$report = $this->validateConfig($this->scheme['report'], $report);

			// Validate report keys (subreport vs subreports)
			if (is_array($report['subreports']) && is_array($report['subreport'])) {
				throw new AssertionException("You cannot fill keys $rid.subreports and $rid.subreport at the same time.");
			}

			if (is_array($report['subreport'])) {
				$report['subreports'] = [$report['subreport']];
				unset($report['subreport']);
			}

			Validators::assertField($report, 'metadata', 'array', "item '%' in $rid");
			Validators::assertField($report, 'groups', 'array', "item '%' in $rid");
			Validators::assertField($report, 'subreports', 'array', "item '%' in $rid");

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
			if (in_array($rid, $this->configuration['reports'])) throw new AssertionException("Duplicate reports $rid");
			$this->configuration['reports'][] = $rid;

			// =================================================================

			// Add report
			$reportDef = $builder->addDefinition($this->prefix('reports.' . $rid))
				->setClass(Report::class, [
					'rid' => $rid,
				]);

			// Add report metadata
			foreach ((array)$report['metadata'] as $key => $value) {
				$reportDef->addSetup('setOption', [$key, $value]);
			}

			// Check if report is groupless otherwise,
			// add report to groups
			if (!$report['groups']) {
				$builder->getDefinition($this->prefix('manager'))
					->addSetup('addGroupless', [$reportDef]);
			} else {
				foreach ($report['groups'] as $gid) {

					// Validate groups
					if (!in_array($gid, $this->configuration['groups'])) throw new AssertionException("Group $gid defined in $rid.groups does not exist");

					$builder->getDefinition($this->prefix('groups.' . $gid))
						->addSetup('addReport', [$reportDef]);
				}
			}

			// Load all subreports from this report
			$this->loadSubreports($rid, $report['subreports']);
		}
	}

	/**
	 * @param string $rid
	 * @param array $subreports
	 */
	protected function loadSubreports($rid, array $subreports)
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
	 * @param string $rid
	 * @param string $sid
	 * @param array $subreport
	 * @throws AssertionException
	 */
	protected function loadSubreport($rid, $sid, array $subreport)
	{
		// Name of the report (report ID + _ + subreport ID)
		$name = $rid . '_' . $sid;

		// Get subreport config
		$subreport = $this->validateConfig($this->scheme['subreport'], $subreport, 'subreport');

		// =====================================================================

		Validators::assertField($subreport, 'metadata', 'array', "item '%' in $rid subreport $sid");
		Validators::assertField($subreport, 'params', 'array|null', "item '%' in $rid subreport $sid");
		Validators::assertField($subreport, 'datasource', NULL, "item '%' in $rid subreport $sid");
		Validators::assertField($subreport, 'renderer', NULL, "item '%' in $rid subreport $sid");

		// =====================================================================

		// Prepare builder
		$builder = $this->getContainerBuilder();

		// Create parameters service
		$parametersDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.parameters'));
		$parametersDef->setClass(Parameters::class);
		$parametersDef->setAutowired(FALSE);

		// Use
		if ($subreport['params'] && isset($subreport['params']['builder'])) {
			// ParametersBuilder
			$parametersBuilderDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.parameters.builder'));
			$parametersBuilderDef->setClass(ParametersBuilder::class);
			$parametersBuilderDef->setAutowired(FALSE);
			// Parse setup
			Compiler::parseService($parametersBuilderDef, ['setup' => $subreport['params']['builder']]);
			// Set ParametersBuilder as factory for Parameters
			$parametersDef->setFactory('@' . $this->prefix('subreports.' . $name . '.parameters.builder') . '::build');
		} else if ($subreport['params']) {
			// Parse service
			Compiler::parseService($parametersDef, $subreport['params']);
		} else {
			// If params are not filled
			$parametersDef->setFactory(ParametersFactory::class . '::create', [[]]);
		}

		// Create datasource service
		$datasourceDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.datasource'));
		Compiler::parseService($datasourceDef, $subreport['datasource']);
		$datasourceDef->setClass(DataSource::class);
		$datasourceDef->setAutowired(FALSE);

		// Create renderer service
		$rendererDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.renderer'));
		Compiler::parseService($rendererDef, $subreport['renderer']);
		$rendererDef->setClass(Renderer::class);
		$rendererDef->setAutowired(FALSE);

		// Create Subreport
		$subreportDef = $builder->addDefinition($this->prefix('subreports.' . $name))
			->setFactory(Subreport::class, [
				'sid' => $name,
				'parameters' => $parametersDef,
				'dataSource' => $datasourceDef,
				'renderer' => $rendererDef,
			]);

		// Add metadata
		foreach ((array)$subreport['metadata'] as $key => $value) {
			$subreportDef->addSetup('setOption', [$key, $value]);
		}

		// Add preprocessors
		foreach ((array)$subreport['preprocessors'] as $column => $preprocessors) {
			foreach ((array)$preprocessors as $key => $preprocessor) {
				$pname = $column . '_' . $key;
				$preprocessorDef = $builder->addDefinition($this->prefix('subreports.' . $name . '.preprocessor.' . $pname));
				Compiler::parseService($preprocessorDef, $preprocessor);
				$subreportDef->addSetup('addPreprocessor', [$column, $preprocessorDef]);
			}
		}

		// Add subreport to report
		$builder->getDefinition($this->prefix('reports.' . $rid))
			->addSetup('addSubreport', [$subreportDef]);
	}

	/**
	 * @param string $rid
	 * @param string $sid
	 * @param mixed $subreport
	 * @throws AssertionException
	 */
	protected function loadSubreportFactory($rid, $sid, $subreport)
	{
		// Name of the report (report ID + _ + subreport ID)
		$name = $rid . '_' . $sid;

		// Prepare builder
		$builder = $this->getContainerBuilder();

		// Parse subreport service
		$subreportDef = $builder->addDefinition($this->prefix('subreports.' . $name));
		Compiler::parseService($subreportDef, $subreport);

		// Add subreport to report
		$builder->getDefinition($this->prefix('reports.' . $rid))
			->addSetup('addSubreport', [new Statement('@' . $this->prefix('subreports.' . $name) . '::create')]);
	}

}
