<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as UnstructuredField;
use DateTimeImmutable as DateTime;

final class Date implements UnstructuredField
{
	private $dateTime;

	public function __construct(DateTime $dateTime)
	{
		$this->dateTime = $dateTime;
	}

	public function required(): bool
	{
		return true;
	}

	public function occurrenceCount(): int
	{
		return 1;
	}

	public function name(): string
	{
		return 'Date';
	}

	public function body(): string
	{
		return $this->dateTime->format('D, d M Y H:i:s O');
	}

	public function dateTime(): DateTime
	{
		return $this->dateTime;
	}

	public function normalise(): self
	{
		return $this->makeReadable()->fold();
	}

	public function fold(): self
	{
		return new self($this->dateTime());
	}

	public function unfold(): self
	{
		return new self($this->dateTime());
	}

	public function makeReadable(): self
	{
		return new self($this->dateTime());
	}

	public function __toString(): string
	{
		return sprintf("%s: %s\r\n", $this->name(), $this->body());
	}
}
