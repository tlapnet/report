<?php

namespace Tlapnet\Report\Bridges\Nette\Renderers;

use Nette\Application\UI\ITemplate;
use Nette\Application\UI\ITemplateFactory;
use Nette\Bridges\ApplicationLatte\Template;
use Tlapnet\Report\Renderers\Renderer;

abstract class TemplateRenderer implements Renderer
{

	/** @var ITemplateFactory */
	private $templateFactory;

	/**
	 * @param ITemplateFactory $templateFactory
	 */
	public function __construct(ITemplateFactory $templateFactory)
	{
		$this->templateFactory = $templateFactory;
	}

	/**
	 * @return ITemplate|Template
	 */
	protected function createTemplate()
	{
		return $this->templateFactory->createTemplate();
	}

}
