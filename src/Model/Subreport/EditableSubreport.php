<?php

namespace Tlapnet\Report\Model\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Model\Data\Result;
use Tlapnet\Report\Model\Data\Resultable;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Model\Utils\Metadata;
use Tlapnet\Report\Renderers\Renderer;

class EditableSubreport extends Subreport
{

	/**
	 * @param Parameters $parameters
	 */
	public function setParameters(Parameters $parameters)
	{
		$this->parameters = $parameters;
	}

	/**
	 * @param Renderer $renderer
	 */
	public function setRenderer(Renderer $renderer)
	{
		$this->renderer = $renderer;
	}

	/**
	 * @param DataSource $dataSource
	 */
	public function setDataSource(DataSource $dataSource)
	{
		$this->dataSource = $dataSource;
	}

	/**
	 * @param Metadata $metadata
	 */
	public function setMetadata(Metadata $metadata)
	{
		$this->metadata = $metadata;
	}

	/**
	 * @param Preprocessors $preprocessors
	 */
	public function setPreprocessors(Preprocessors $preprocessors)
	{
		$this->preprocessors = $preprocessors;
	}

	/**
	 * @param Result|Resultable $result
	 */
	public function setResult(Resultable $result)
	{
		$this->result = $result;
	}

}
