<?php

namespace Tlapnet\Report\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Result\Result;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Utils\Metadata;
use Tlapnet\Report\Renderers\Renderer;

class EditableSubreport extends Subreport
{

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
	 * @param Result|Resultable $result
	 * @return void
	 */
	public function setResult(Resultable $result)
	{
		$this->result = $result;
		$this->rawResult = $result;
	}

}
