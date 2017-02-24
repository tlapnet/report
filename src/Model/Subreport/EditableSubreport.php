<?php

namespace Tlapnet\Report\Model\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Model\Parameters\Parameters;
use Tlapnet\Report\Model\Preprocessor\Preprocessors;
use Tlapnet\Report\Model\Result\Result;
use Tlapnet\Report\Model\Result\Resultable;
use Tlapnet\Report\Model\Utils\Metadata;
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
