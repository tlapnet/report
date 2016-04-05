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
use Tlapnet\Report\HeapBox\HeapBox;
use Tlapnet\Report\HeapBox\ParameterListFactory;
use Tlapnet\Report\HeapBoxManager;
use Tlapnet\Report\Renderers\Renderer;

class ReportExtension extends CompilerExtension
{

	/** @var array */
	protected $defaults = [
		'files' => [],
		'heaps' => [],
		'folders' => [],
		'services' => [],
	];

	/** @var array */
	protected $definitions = [
		'heaps' => [],
	];

	public function loadConfiguration()
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		// Assert config
		Validators::assert($config['folders'], 'array', "'folders'");
		Validators::assert($config['files'], 'array', "'files'");
		Validators::assert($config['heaps'], 'array', "'heaps'");
		Validators::assert($config['services'], 'array', "'services'");

		// Setup one class, HeapBoxManager which holds all HeapBoxes

		$builder->addDefinition($this->prefix('manager'))
			->setClass(HeapBoxManager::class);

		// Load common services
		if ($config['services']) {
			Compiler::parseServices($builder, $config, 'report');
		}

		// Load from folders
		if ($config['folders']) {
			$this->loadHeapsFromFolders($config['folders']);
		}

		// Load from files
		if ($config['files']) {
			$this->loadHeapsFromFiles($config['files']);
		}

		// Load from config
		if ($config['heaps']) {
			$this->loadHeapsFromConfig($config['heaps']);
		}
	}

	/**
	 * @param array $folders
	 */
	protected function loadHeapsFromFolders(array $folders)
	{
		$files = [];
		foreach ($folders as $folder) {
			// Validate folder
			if (!is_dir($folder)) throw new FolderNotFoundException("Folder '$folder' not found.'");

			// Find all configs
			foreach (Finder::findFiles('*.neon')->from($folder) as $file) {
				$files[] = $file;
			}
		}

		$this->loadHeapsFromFiles($files);
	}

	/**
	 * @param array $files
	 */
	protected function loadHeapsFromFiles(array $files)
	{
		$loader = new NeonAdapter();
		foreach ($files as $file) {
			// Validate file
			if (!file_exists($file)) throw new FileNotFoundException("File '$file' not found.");

			// File is included as heap section
			$filedata = $loader->load($file);

			// Check if heap has a appropriate configuration
			if (!$filedata || count($filedata) <= 0) throw new InvalidConfigException('Heap configuration cannot be empty.');

			// Check if heap has a appropriate configuration
			if (count($filedata) > 1) throw new InvalidConfigException('Heap must have a name. Specific root node as name of heap.');

			$name = key($filedata);
			$heap = $filedata[$name];

			// Load heap
			$this->loadHeap($name, $heap);
		}

		// Add as dependencies
		$this->compiler->addDependencies($files);
	}

	/**
	 * @param array $files
	 */
	protected function loadHeapsFromConfig(array $heaps)
	{
		foreach ($heaps as $name => $heap) {
			$this->loadHeap($name, $heap);
		}
	}

	/**
	 * @param string $name
	 * @param array $heap
	 */
	protected function loadHeap($name, array $heap)
	{
		// Validate heap
		if (isset($heap['metadata'])) {
			Validators::assertField($heap, 'metadata', 'array', "item '%' in '$name' heap");
		} else {
			$heap['metadata'] = [];
		}

		if (isset($heap['params'])) {
			Validators::assertField($heap, 'params', 'array', "item '%' in '$name' heap");
		} else {
			$heap['params'] = [];
		}

		Validators::assertField($heap, 'datasource', NULL, "item '%' in '$name' heap");
		Validators::assertField($heap, 'renderer', NULL, "item '%' in '$name' heap");

		// =====================================================================

		// Check duplicates
		if (in_array($name, $this->definitions['heaps'])) throw new AssertionException("Duplicate heaps '$name'.");

		// Append to definitions
		$this->definitions['heaps'][] = $name;

		// Prepare builder
		$builder = $this->getContainerBuilder();

		// Create datasource service
		$datasourceDef = $builder->addDefinition($this->prefix('heaps.' . $name . '.datasource'));
		Compiler::parseService($datasourceDef, $heap['datasource']);
		$datasourceDef->setClass(DataSource::class);
		$datasourceDef->setAutowired(FALSE);

		// Create renderer service
		$rendererDef = $builder->addDefinition($this->prefix('heaps.' . $name . '.renderer'));
		Compiler::parseService($rendererDef, $heap['renderer']);
		$rendererDef->setClass(Renderer::class);
		$rendererDef->setAutowired(FALSE);

		// Create HeapBox
		$heapDef = $builder->addDefinition($this->prefix('heaps.' . $name))
			->setFactory(HeapBox::class, [
				'uid' => $name,
				'parameters' => new Statement(ParameterListFactory::class . '::create', [(array)$heap['params']]),
				'dataSource' => $datasourceDef,
				'renderer' => $rendererDef,
			]);

		// Add metadata
		foreach ((array)$heap['metadata'] as $key => $value) {
			$heapDef->addSetup('setOption', [$key, $value]);
		}

		// Add HeapBox to manager
		$builder->getDefinition($this->prefix('manager'))
			->addSetup('addHeapBox', [$heapDef]);
	}

}
