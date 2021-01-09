<?php
declare(strict_types=1);

namespace Meraki\Email\Message;

interface Field
{
	public function required(): bool;
	public function occurrenceCount(): int;
	public function name(): string;
	public function body(): string;
	public function normalise(): self;
	public function fold(): self;
	public function unfold(): self;
	public function makeReadable(): self;
	public function __toString(): string;
}
