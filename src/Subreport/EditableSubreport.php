<?php declare(strict_types = 1);

namespace Tlapnet\Report\Subreport;

use Tlapnet\Report\DataSources\DataSource;
use Tlapnet\Report\Parameters\Parameters;
use Tlapnet\Report\Preprocessor\Preprocessors;
use Tlapnet\Report\Renderers\Renderer;
use Tlapnet\Report\Result\Resultable;
use Tlapnet\Report\Utils\Metadata;

class EditableSubreport extends Subreport
{

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

	public function setResult(Resultable $result): void
	{
		$this->result = $result;
		$this->rawResult = $result;
	}

}
