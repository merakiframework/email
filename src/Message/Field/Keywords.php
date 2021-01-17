<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as InformationalField;
use Countable;
use IteratorAggregate;
use Generator;

final class Keywords implements InformationalField, Countable, IteratorAggregate
{
	private $phrases;

	public function __construct(string $phrase, string ...$phrases)
	{
		$this->phrases = array_merge([$phrase], $phrases);
	}

	public function add(string $phrase): self
	{
		return new self(...array_merge($this->phrases, [$phrase]));
	}

	public function phrases(): array
	{
		return $this->phrases;
	}

	public function required(): bool
	{
		return false;
	}

	public function occurrenceCount(): int
	{
		return 1;
	}

	public function name(): string
	{
		return 'Keywords';
	}

	public function body(): string
	{
		return implode(',', $this->phrases);
	}

	public function normalise(): self
	{
		return $this->makeReadable()->fold();
	}

	public function fold(): self
	{
		return new self($this->body());
	}

	public function unfold(): self
	{
		return new self($this->body());
	}

	public function makeReadable(): self
	{
		return new self($this->body());
	}

	public function __toString(): string
	{
		return sprintf("%s: %s\r\n", $this->name(), $this->body());
	}

	public function count(): int
	{
		return count($this->phrases);
	}

	public function getIterator(): Generator
	{
		yield from $this->phrases;
	}
}
