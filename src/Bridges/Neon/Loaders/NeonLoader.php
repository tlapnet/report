<?php

namespace Tlapnet\Report\Bridges\Neon\Loaders;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Loaders\AbstractLoader;

class NeonLoader extends AbstractLoader
{

	/** @var array */
	private $files = [];

	/** @var array */
	private $folders = [];

	/**
	 * @param string $file
	 * @return void
	 */
	public function addFile($file)
	{
		if (!file_exists($file)) throw new InvalidArgumentException(sprintf('File "%s" not found.', $file));

		$this->files[] = $file;
	}

	/**
	 * @param string $folder
	 * @return void
	 */
	public function addFolder($folder)
	{
		if (!is_dir($folder)) throw new InvalidArgumentException(sprintf('Folder "%s" not found.', $folder));

		$this->folders[] = $folder;
	}

	/**
	 * @return void
	 */
	public function load()
	{
		throw new NotImplementedException();
	}

}
