<?php

namespace Tlapnet\Report\Bridges\Nette\UI\Response;

use Nette\Application\IResponse as UIResponse;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

class CsvResponse implements UIResponse
{

	/** @var mixed */
	private $payload;

	/** @var string */
	private $contentType;

	/**
	 * @param mixed $payload
	 * @param string $contentType
	 */
	public function __construct($payload, $contentType = NULL)
	{
		$this->payload = $payload;
		$this->contentType = $contentType ? $contentType : 'text/csv';
	}

	/**
	 * @return mixed
	 */
	public function getPayload()
	{
		return $this->payload;
	}

	/**
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * @param IRequest $httpRequest
	 * @param IResponse $httpResponse
	 * @return void
	 */
	public function send(IRequest $httpRequest, IResponse $httpResponse)
	{
		$httpResponse->setContentType($this->contentType, 'utf-8');

		// Write all payload directly to output
		$fp = fopen('php://output', 'w');
		foreach ($this->payload as $line) {
			// This method is thread-safe and setup appropriate delimiters
			fputcsv($fp, $line);
		}
		fclose($fp);
	}

}
