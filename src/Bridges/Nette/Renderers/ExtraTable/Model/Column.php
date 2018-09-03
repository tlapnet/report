<?php declare(strict_types = 1);

namespace Tlapnet\Report\Bridges\Nette\Renderers\ExtraTable\Model;

class Column extends Component
{

	/** @var string|null */
	private $title;

	/** @var object|null */
	private $link;

	/** @var object|null */
	private $url;

	/** @var object|null */
	private $label;

	/** @var callable|null */
	private $callback;

	/** @var mixed[] */
	private $options = [
		'align' => null,
		'type' => null,
		'sortable' => true,
	];

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function getOptions(): ?object
	{
		return (object) $this->options;
	}

	public function getLink(): ?object
	{
		return $this->link;
	}

	public function getUrl(): ?object
	{
		return $this->url;
	}

	public function getLabel(): ?object
	{
		return $this->label;
	}

	public function getCallback(): ?callable
	{
		return $this->callback;
	}

	public function title(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function type(string $type): self
	{
		$this->options['type'] = $type;

		return $this;
	}

	public function align(string $align): self
	{
		$this->options['align'] = $align;

		return $this;
	}

	public function sortable(bool $sortable): self
	{
		$this->options['sortable'] = $sortable;

		return $this;
	}

	/**
	 * @param mixed[] $args
	 */
	public function link(string $destination, array $args = []): self
	{
		$this->link = (object) [
			'destination' => $destination,
			'args' => $args,
		];

		return $this;
	}

	public function url(string $url): self
	{
		$this->url = (object) [
			'url' => $url,
		];

		return $this;
	}

	public function label(string $label): self
	{
		$this->label = (object) [
			'name' => $label,
		];

		return $this;
	}

	public function callback(callable $callback): self
	{
		$this->callback = $callback;

		return $this;
	}

}
