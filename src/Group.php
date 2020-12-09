<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\Address;
use Meraki\Email\DisplayName;
use Meraki\Email\MailboxList;

final class Group implements Address
{
	private $displayName;
	private $mailboxList;

	public function __construct(DisplayName $displayName, MailboxList $mailboxList = null)
	{
		$this->displayName = $displayName;
		$this->mailboxList = $mailboxList;
	}

	public function getDisplayName(): ?DisplayName
	{
		return $this->displayName;
	}

	public function getMailboxList(): ?MailboxList
	{
		return $this->mailboxList;
	}

	public function equals(self $other): bool
	{
		if ($this->displayName->equals($other->displayName)) {
			if ($this->mailboxList !== null && $other->mailboxList !== null) {
				return $this->mailboxList->equals($other->mailboxList);
			}

			return $this->mailboxList === null && $other->mailboxList === null;
		}

		return false;
	}

	public function __toString(): string
	{
		if ($this->mailboxList !== null) {
			return sprintf('%s:%s;', $this->displayName, $this->mailboxList);
		}
		
		return sprintf('%s:;', $this->displayName);
	}
}
