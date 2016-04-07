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
use Tlapnet\Report\Model\Box\Box;
use Tlapnet\Report\Model\Box\ParameterListFactory;
use Tlapnet\Report\Model\Collection\Collection;
use Tlapnet\Report\Model\Group\Group;
use Tlapnet\Report\Model\ReportService;
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
		'collections' => [],
		'groups' => [],
		'boxes' => [],
	];

	/** @var array */
	protected $scheme = [
		'box' => [
			'groups' => NULL,
			'subreports' => NULL,
		],
		'group' => [
			'metadata' => [
				'name' => NULL,
				'title' => NULL,
				'description' => NULL,
			],
		],
		'report' => [
			'metadata' => [
				'name' => NULL,
			],
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

		// Load collections
		if ($config['groups']) {
			$this->loadCollections($config['groups']);
		}

		// Load from folders
		if ($config['folders']) {
			$this->loadGroupsFromFolders($config['folders']);
		}

		// Load from files
		if ($config['files']) {
			$this->loadGroupsFromFiles($config['files']);
		}

		// Load from config
		if ($config['reports']) {
			$this->loadGroupsFromConfig($config['reports']);
		}
	}

	/**
	 * @param array $collections
	 */
	protected function loadCollections(array $collections)
	{
		$builder = $this->getContainerBuilder();
		$managerDef = $builder->getDefinition($this->prefix('manager'));

		foreach ($collections as $cid => $name) {

			// Check duplicates
			if (in_array($cid, $this->configuration['collections'])) throw new AssertionException("Duplicate collections '$cid'.'$name'");

			// Append to definitions
			$this->configuration['collections'][] = $cid;

			// Create collection definition
			$collectionDef = $builder->addDefinition($this->prefix('collections.' . $cid))
				->setClass(Collection::class, [
					'cid' => $cid,
					'name' => $name,
				]);

			// Do not autowire!
			$collectionDef->setAutowired(FALSE);

			// Append to configuration
			$this->configuration['collections'][$cid] = $collectionDef;

			// Append to manager
			$managerDef->addSetup('addCollection', [$collectionDef]);
		}
	}

	/**
	 * @param array $folders
	 */
	protected function loadGroupsFromFolders(array $folders)
	{
		$files = [];
		foreach ($folders as $folder) {
			// Validate folder
			if (!is_dir($folder)) throw new FolderNotFoundException("Folder '$folder' not found'");

			// Find all configs
			foreach (Finder::findFiles('*.neon')->from($folder) as $file) {
				$files[] = $file;
			}
		}

		$this->loadGroupsFromFiles($files);
	}

	/**
	 * @param array $files
	 */
	protected function loadGroupsFromFiles(array $files)
	{
		$loader = new NeonAdapter();
		$groups = [];

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
			$group = $filedata[$name];

			// Append group
			$groups[$name] = $group;
		}

		// Load groups
		$this->loadGroupsFromConfig($groups);

		// Add as dependencies
		$this->compiler->addDependencies($files);
	}

	/**
	 * @param array $groups
	 */
	protected function loadGroupsFromConfig(array $groups)
	{
		$builder = $this->getContainerBuilder();

		foreach ($groups as $gid => $group) {
			// Validate group
			Validators::assertField($group, 'groups', 'array', "item '%' in '$gid'");
			Validators::assertField($group, 'metadata', 'array', "item '%' in '$gid'");
			Validators::assertField($group, 'subreports', 'array', "item '%' in '$gid'");

			// Validate group.metadata scheme
			foreach ($this->scheme['group']['metadata'] as $key => $val) {
				Validators::assertField($group['metadata'], $key, NULL, "item '%' in '$gid.metadata'");
			}

			// Check duplicates
			if (in_array($gid, $this->configuration['groups'])) throw new AssertionException("Duplicate reports '$gid'");
			$this->configuration['groups'][] = $gid;

			// =================================================================

			// Add group
			$groupDef = $builder->addDefinition($this->prefix('groups.' . $gid))
				->setClass(Group::class, [$gid]);

			// Add group metadata
			foreach ((array)$group['metadata'] as $key => $value) {
				$groupDef->addSetup('setOption', [$key, $value]);
			}


			// Add group to collections
			foreach ($group['groups'] as $cid) {

				// Validate groups (collection)
				if (!in_array($cid, $this->configuration['collections'])) throw new AssertionException("Group '$cid' not exists");

				$collectionDef = $builder->getDefinition($this->prefix('collections.' . $cid));
				$collectionDef->addSetup('addGroup', [$groupDef]);
			}

			// Load group report boxes
			$this->loadBoxes($gid, $group['subreports']);
		}
	}

	/**
	 * @param string $gid
	 * @param array $boxes
	 */
	protected function loadBoxes($gid, array $boxes)
	{
		foreach ($boxes as $bid => $subreport) {
			$this->loadBox($gid, $bid, $subreport);
		}
	}

	/**
	 * @param string $gid
	 * @param string $bid
	 * @param array $box
	 * @throws AssertionException
	 */
	protected function loadBox($gid, $bid, array $box)
	{
		// Name of the report (group ID + _ + box ID)
		$name = $gid . '_' . $bid;

		// =====================================================================

		// Validate box.metadata
		Validators::assertField($box, 'metadata', 'array', "item '%' in '$name'");

		// Validate box.metadata scheme
		foreach ($this->scheme['report']['metadata'] as $key => $val) {
			Validators::assertField($box['metadata'], $key, NULL, "item '%' in '$name.metadata'");
		}

		// Validate box.params
		if (isset($box['params'])) {
			Validators::assertField($box, 'params', 'array', "item '%' in '$name' report");
		} else {
			$box['params'] = [];
		}

		// Validate box.datasource
		Validators::assertField($box, 'datasource', NULL, "item '%' in '$name' report");

		// Validate box.renderer
		Validators::assertField($box, 'renderer', NULL, "item '%' in '$name' report");

		// =====================================================================

		// Prepare builder
		$builder = $this->getContainerBuilder();

		// Create datasource service
		$datasourceDef = $builder->addDefinition($this->prefix('reports.' . $name . '.datasource'));
		Compiler::parseService($datasourceDef, $box['datasource']);
		$datasourceDef->setClass(DataSource::class);
		$datasourceDef->setAutowired(FALSE);

		// Create renderer service
		$rendererDef = $builder->addDefinition($this->prefix('reports.' . $name . '.renderer'));
		Compiler::parseService($rendererDef, $box['renderer']);
		$rendererDef->setClass(Renderer::class);
		$rendererDef->setAutowired(FALSE);

		// Create ReportBox
		$boxDef = $builder->addDefinition($this->prefix('reports.' . $name))
			->setFactory(Box::class, [
				'bid' => $name,
				'parameters' => new Statement(ParameterListFactory::class . '::create', [(array)$box['params']]),
				'dataSource' => $datasourceDef,
				'renderer' => $rendererDef,
			]);

		// Add metadata
		foreach ((array)$box['metadata'] as $key => $value) {
			$boxDef->addSetup('setOption', [$key, $value]);
		}

		// Add box to group
		$groupDef = $builder->getDefinition($this->prefix('groups.' . $gid));
		$groupDef->addSetup('addBox', [$boxDef]);
	}

}
