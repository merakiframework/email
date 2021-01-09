<?php
declare(strict_types=1);

namespace Meraki\Email\Exception;

use InvalidArgumentException;

final class InvalidBodyText extends InvalidArgumentException
{
	public static function nulCharacterFound(): self
	{
		return new self('Body text cannot contain a NUL character!');
	}

	public static function incorrectLineEndings(): self
	{
		return new self('Body text cannot have carriage return or linefeed characters appear independently!');
	}

	public static function eightBitCharacterFound(): self
	{
		return new self('Body text cannot contain 8 bit characters!');
	}

	public static function lineTooLong(): self
	{
		return new self('Lines must be limited to 998 characters excluding CRLF!');
	}
}
