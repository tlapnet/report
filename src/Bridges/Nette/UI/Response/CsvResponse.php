<?php declare(strict_types = 1);

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
	 * @param mixed  $payload
	 */
	public function __construct($payload, string $contentType = 'text/csv')
	{
		$this->payload = $payload;
		$this->contentType = $contentType;
	}

	/**
	 * @return mixed
	 */
	public function getPayload()
	{
		return $this->payload;
	}

	public function getContentType(): string
	{
		return $this->contentType;
	}

	public function send(IRequest $httpRequest, IResponse $httpResponse): void
	{
		$httpResponse->setContentType($this->contentType, 'utf-8');

		// Write all payload directly to output
		$fp = fopen('php://output', 'w');
		foreach ($this->payload as $line) {
			// This method is thread-safe and setup appropriate delimiters
			fputcsv($fp, (array) $line);
		}
		fclose($fp);
	}

}
