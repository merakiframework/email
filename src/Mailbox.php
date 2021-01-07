<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\Address;
use Meraki\Email\DisplayName;

final class Mailbox implements Address
{
	private $displayName;
	private $address;

	public function getDisplayName(): ?DisplayName
	{
		return $this->displayName;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	public function equals(self $other): bool
	{
		// Testing if emails are the same must be done in a case-insensitive manner.
		return strcasecmp($this->address, $other->address) === 0;
	}

	public function __toString(): string
	{
		if ($this->displayName !== null) {
			return sprintf('%s<%s>', $this->displayName, $this->address);
		}

		return sprintf('<%s>', $this->address);
	}

	public static function withDisplayName(string $displayName, string $address): self
	{
		$mailbox = new self();
		$mailbox->displayName = new DisplayName($displayName);
		$mailbox->address = $address;

		return $mailbox;
	}

	public static function withoutDisplayName(string $address): self
	{
		$mailbox = new self();
		$mailbox->address = $address;

		return $mailbox;
	}
}
