<?php
declare(strict_types=1);

namespace Meraki\Email\Exception;

use InvalidArgumentException;

final class DisplayName extends InvalidArgumentException
{
	public static function containsInvalidCharacters(): self
	{
		return new self('Display name contains invalid characters.');
	}
}
