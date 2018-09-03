<?php declare(strict_types = 1);

namespace Tlapnet\Report\Export;

use Tlapnet\Report\Exceptions\Logic\InvalidArgumentException;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Utils\Suggestions;

class Exporters
{

	/** @var Exporter[] */
	private $exporters = [];

	public function add(string $name, Exporter $exporter): void
	{
		if (isset($this->exporters[$name])) {
			throw new InvalidStateException(sprintf('Exporter "%s" already exists', $name));
		}

		$this->exporters[$name] = $exporter;
	}

	public function isEmpty(): bool
	{
		return $this->exporters === [];
	}

	/**
	 * @return Exporter[]
	 */
	public function getAll(): array
	{
		return $this->exporters;
	}

	public function has(string $name): bool
	{
		return isset($this->exporters[$name]);
	}

	public function get(string $name): Exporter
	{
		if (!$this->has($name)) {
			$hint = Suggestions::getSuggestion(array_keys($this->exporters), $name);
			throw new InvalidArgumentException(Suggestions::format(sprintf('Unknown exporter "%s"', $name), $hint));
		}

		return $this->exporters[$name];
	}

	/**
	 * @param mixed[] $options
	 */
	public function export(string $name, Resultable $result, array $options = []): Exportable
	{
		$exporter = $this->get($name);
		return $exporter->export($result, $options);
	}

}
