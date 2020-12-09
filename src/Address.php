<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\DisplayName;

interface Address
{
	public function getDisplayName(): ?DisplayName;
}
