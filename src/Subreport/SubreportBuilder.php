<?php

namespace Tlapnet\Report\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\Utils\Metadata;

class SubreportBuilder
{

	/** @var mixed */
	protected $sid;

	/** @var Parameters */
	protected $parameters;

	/** @var Renderer */
	protected $renderer;

	/** @var DataSource */
	protected $dataSource;

	/** @var Metadata */
	protected $metadata;

	/** @var Preprocessors */
	protected $preprocessors;

	/**
	 * @param mixed $sid
	 * @return void
	 */
	public function setSid($sid)
	{
		$this->sid = $sid;
	}

	/**
	 * @param Parameters $parameters
	 * @return void
	 */
	public function setParameters(Parameters $parameters)
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param Renderer $renderer
	 * @return void
	 */
	public function setRenderer(Renderer $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * @param DataSource $dataSource
	 * @return void
	 */
	public function setDataSource(DataSource $dataSource)
	{
		$this->dataSource = $dataSource;
	}

	/**
	 * @param Metadata $metadata
	 * @return void
	 */
	public function setMetadata(Metadata $metadata)
	{
		$this->metadata = $metadata;
	}

	/**
	 * @param Preprocessors $preprocessors
	 * @return void
	 */
	public function setPreprocessors(Preprocessors $preprocessors)
	{
		$this->preprocessors = $preprocessors;
	}

	/**
	 * @return Subreport
	 */
	public function build()
	{
		if (!$this->sid) {
			throw new InvalidStateException("Missing 'sid'. Please call setSid().");
		}

		if (!$this->parameters) {
			throw new InvalidStateException("Missing 'parameters'. Please call setParameters().");
		}

		if (!$this->dataSource) {
			throw new InvalidStateException("Missing 'dataSource'. Please call setDataSource().");
		}

		if (!$this->renderer) {
			throw new InvalidStateException("Missing 'renderer'. Please call setRenderer().");
		}

		$subreport = new EditableSubreport(
			$this->sid,
			$this->parameters,
			$this->dataSource,
			$this->renderer
		);

		if ($this->metadata) {
			$subreport->setMetadata($this->metadata);
		}

		if ($this->preprocessors) {
			$subreport->setPreprocessors($this->preprocessors);
		}

		return $subreport;
	}

}
