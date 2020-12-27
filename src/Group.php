<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\Address;
use Meraki\Email\DisplayName;
use Meraki\Email\GroupList;

final class Group implements Address
{
	private $displayName;
	private $groupList;

	public function __construct(DisplayName $displayName, GroupList $groupList = null)
	{
		$this->displayName = $displayName;
		$this->groupList = $groupList;
	}

	public function getDisplayName(): ?DisplayName
	{
		return $this->displayName;
	}

	public function groupList(): ?GroupList
	{
		return $this->groupList;
	}

	public function equals(self $other): bool
	{
		if ($this->displayName->equals($other->displayName)) {
			if ($this->groupList !== null && $other->groupList !== null) {
				return $this->groupList->equals($other->groupList);
			}

			return $this->groupList === null && $other->groupList === null;
		}

		return false;
	}

	public function __toString(): string
	{
		if ($this->groupList !== null) {
			return sprintf('%s:%s;', $this->displayName, $this->groupList);
		}
		
		return sprintf('%s:;', $this->displayName);
	}
}
