<?php declare(strict_types = 1);

namespace Tlapnet\Report\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\Utils\Metadata;

class SubreportBuilder
{

	/** @var string|null */
	protected $sid;

	/** @var Parameters|null */
	protected $parameters;

	/** @var Renderer|null */
	protected $renderer;

	/** @var DataSource|null */
	protected $dataSource;

	/** @var Metadata|null */
	protected $metadata;

	/** @var Preprocessors|null */
	protected $preprocessors;

	public function setSid(string $sid): void
	{
		$this->sid = $sid;
	}

	public function setParameters(Parameters $parameters): void
	{
		$this->parameters = $parameters;
	}

	public function setRenderer(Renderer $renderer): void
	{
		$this->renderer = $renderer;
	}

	public function setDataSource(DataSource $dataSource): void
	{
		$this->dataSource = $dataSource;
	}

	public function setMetadata(Metadata $metadata): void
	{
		$this->metadata = $metadata;
	}

	public function setPreprocessors(Preprocessors $preprocessors): void
	{
		$this->preprocessors = $preprocessors;
	}

	public function build(): Subreport
	{
		if ($this->sid === null) {
			throw new InvalidStateException("Missing 'sid'. Please call setSid().");
		}

		if ($this->parameters === null) {
			throw new InvalidStateException("Missing 'parameters'. Please call setParameters().");
		}

		if ($this->dataSource === null) {
			throw new InvalidStateException("Missing 'dataSource'. Please call setDataSource().");
		}

		if ($this->renderer === null) {
			throw new InvalidStateException("Missing 'renderer'. Please call setRenderer().");
		}

		$subreport = new EditableSubreport(
			$this->sid,
			$this->parameters,
			$this->dataSource,
			$this->renderer
		);

		if ($this->metadata !== null) {
			$subreport->setMetadata($this->metadata);
		}

		if ($this->preprocessors !== null) {
			$subreport->setPreprocessors($this->preprocessors);
		}

		return $subreport;
	}

}
