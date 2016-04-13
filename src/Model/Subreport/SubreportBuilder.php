<?php

namespace Tlapnet\Report\Model\Subreport;

use Tlapnet\Report\DataSources\DataSource;
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

	/** @var array */
	protected $preprocessors = [];

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
	 * @param array $preprocessors
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
		return new Subreport(
			$this->sid,
			$this->parameters,
			$this->dataSource,
			$this->renderer
		);
	}
}