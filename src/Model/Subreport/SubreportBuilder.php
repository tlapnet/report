<?php

namespace Tlapnet\Report\Model\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Exceptions\Logic\InvalidStateException;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Model\Utils\Metadata;
use Tlapnet\Report\Renderers\Renderer;

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
	 */
	public function setSid($sid)
	{
		$this->sid = $sid;
	}

	/**
	 * @param Parameters $parameters
	 */
	public function setParameters($parameters)
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param Renderer $renderer
	 */
	public function setRenderer($renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * @param DataSource $dataSource
	 */
	public function setDataSource($dataSource)
	{
		$this->dataSource = $dataSource;
	}

	/**
	 * @param Metadata $metadata
	 */
	public function setMetadata($metadata)
	{
		$this->metadata = $metadata;
	}

	/**
	 * @param Preprocessors $preprocessors
	 */
	public function setPreprocessors($preprocessors)
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