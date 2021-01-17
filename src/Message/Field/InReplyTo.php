<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as IdentificationField;
use Countable;
use IteratorAggregate;
use Generator;

final class InReplyTo implements IdentificationField, Countable, IteratorAggregate
{
	private $ids;

	public function __construct(string $id, string ...$ids)
	{
		$ids = array_merge([$id], $ids);

		foreach ($ids as $id) {
			$this->add($id);
		}
	}

	public function add($id): self
	{
		$this->ids[] = sprintf('<%s>', $id);

		return $this;
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
		return 'In-Reply-To';
	}

	public function body(): string
	{
		return implode(' ', $this->ids);
	}

	public function normalise(): self
	{
		return $this->makeReadable()->fold();
	}

	public function fold(): self
	{
		return new self($this->name(), $this->body());
	}

	public function unfold(): self
	{
		return new self($this->name(), $this->body());
	}

	public function makeReadable(): self
	{
		return new self($this->name(), $this->body());
	}

	public function __toString(): string
	{
		return sprintf("%s: %s\r\n", $this->name(), $this->body());
	}

	public function count(): int
	{
		return count($this->ids);
	}

	public function getIterator(): Generator
	{
		yield from $this->ids;
	}
}
