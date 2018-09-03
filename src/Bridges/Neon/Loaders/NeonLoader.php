<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Neon\Loaders;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\NotImplementedException;
use Tlapnet\Report\Loaders\AbstractLoader;
use Tlapnet\Report\Report\Report;

class NeonLoader extends AbstractLoader
{

	/** @var string[] */
	private $files = [];

	/** @var string[] */
	private $folders = [];

	public function addFile(string $file): void
	{
		if (!file_exists($file)) throw new InvalidArgumentException(sprintf('File "%s" not found.', $file));

		$this->files[] = $file;
	}

	public function addFolder(string $folder): void
	{
		if (!is_dir($folder)) throw new InvalidArgumentException(sprintf('Folder "%s" not found.', $folder));

		$this->folders[] = $folder;
	}

	public function load(): Report
	{
		throw new NotImplementedException();
	}

}
