<?php declare(strict_types = 1);

namespace Tlapnet\Report\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Export\Exportable;
use Tlapnet\Report\Export\Exporter;
use Tlapnet\Report\Export\Exporters;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Preprocessor;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\Result\EditableResult;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Utils\Metadata;
use Tlapnet\Report\Utils\TOptions;

class Subreport implements Reportable
{

	use TOptions;

	// States
	public const STATE_CREATED = 1;
	public const STATE_ATTACHED = 2;
	public const STATE_COMPILED = 3;
	public const STATE_PREPROCESSED = 4;
	public const STATE_RENDERED = 5;

	/** @var string */
	protected $sid;

	/** @var Parameters */
	protected $parameters;

	/** @var Renderer */
	protected $renderer;

	/** @var DataSource */
	protected $dataSource;

	/** @var Preprocessors */
	protected $preprocessors;

	/** @var Exporters */
	protected $exporters;

	/** @var Result|Resultable|null */
	protected $rawResult;

	/** @var Result|EditableResult|Resultable|null */
	protected $result;

	/** @var int */
	protected $state;

	public function __construct(string $sid, Parameters $parameters, DataSource $dataSource, Renderer $renderer)
	{
		$this->sid = $sid;
		$this->parameters = $parameters;
		$this->renderer = $renderer;
		$this->dataSource = $dataSource;
		$this->metadata = new Metadata();
		$this->preprocessors = new Preprocessors();
		$this->exporters = new Exporters();
		$this->state = self::STATE_CREATED;
	}

	public function getSid(): string
	{
		return $this->sid;
	}

	public function getParameters(): Parameters
	{
		return $this->parameters;
	}

	public function getRenderer(): Renderer
	{
		return $this->renderer;
	}

	public function getDataSource(): DataSource
	{
		return $this->dataSource;
	}

	public function getPreprocessors(): Preprocessors
	{
		return $this->preprocessors;
	}

	public function getMetadata(): Metadata
	{
		return $this->metadata;
	}

	/**
	 * @return Result|EditableResult|null
	 */
	public function getResult(): ?Result
	{
		return $this->result;
	}

	public function getRawResult(): ?Result
	{
		return $this->rawResult;
	}

	public function isState(int $state): bool
	{
		return $this->state === $state;
	}

	public function addPreprocessor(string $column, Preprocessor $preprocessor): Preprocessor
	{
		$this->preprocessors->add($column, $preprocessor);

		return $preprocessor;
	}

	public function addExporter(string $name, Exporter $exporter): Exporter
	{
		$this->exporters->add($name, $exporter);

		return $exporter;
	}

	public function getExporters(): Exporters
	{
		return $this->exporters;
	}

	/**
	 * @param mixed[] $options
	 */
	public function export(string $name, array $options = []): Exportable
	{
		return $this->exporters->export($name, $this->result, $options);
	}

	/**
	 * @param mixed[] $values
	 */
	public function attach(array $values): void
	{
		if ($this->state === self::STATE_COMPILED || $this->state === self::STATE_PREPROCESSED) {
			throw new InvalidStateException('Cannot attach parameters in this state.');
		}

		$this->parameters->attach($values);
		$this->state = self::STATE_ATTACHED;
	}

	public function compile(): void
	{
		$result = $this->dataSource->compile($this->parameters);

		$this->result = $this->rawResult = $result;
		$this->state = self::STATE_COMPILED;
	}

	public function preprocess(): void
	{
		if ($this->state === self::STATE_PREPROCESSED) {
			throw new InvalidStateException('Cannot preprocess twice same report.');
		}

		if ($this->state !== self::STATE_COMPILED) {
			throw new InvalidStateException('Cannot preprocess subreport. Please compiled it first.');
		}

		// Preprocess result
		if (!$this->preprocessors->isEmpty()) {
			$this->result = $this->rawResult->toEditable();
			$this->preprocessors->preprocess($this->result);
			$this->state = self::STATE_PREPROCESSED;
		}
	}

	/**
	 * @return mixed
	 */
	public function render()
	{
		if ($this->state !== self::STATE_COMPILED && $this->state !== self::STATE_PREPROCESSED) {
			throw new InvalidStateException('Cannot render subreport. Please compiled or preprocess it first.');
		}

		$this->state = self::STATE_RENDERED;

		return $this->renderer->render($this->result);
	}

}
