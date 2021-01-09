<?php
declare(strict_types=1);

namespace Meraki\Email\Exception;

use Meraki\Email\Exception\InvalidBodyText;
use Meraki\TestSuite\TestCase;
use InvalidArgumentException;

final class InvalidBodyTextTest extends TestCase
{
	/**
	 * @test
	 */
	public function is_an_invalid_argument_exception(): void
	{
		$exception = new InvalidBodyText('An error message!');

		$this->assertInstanceOf(InvalidArgumentException::class, $exception);
	}

	/**
	 * @test
	 */
	public function can_create_for_incorrect_line_endings(): void
	{
		$expectedMessage = 'Body text cannot have carriage return or linefeed characters appear independently!';
		$exception = InvalidBodyText::incorrectLineEndings();

		$actualMessage = $exception->getMessage();

		$this->assertEquals($expectedMessage, $actualMessage);
	}

	/**
	 * @test
	 */
	public function can_create_for_nul_character_found(): void
	{
		$expectedMessage = 'Body text cannot contain a NUL character!';
		$exception = InvalidBodyText::nulCharacterFound();

		$actualMessage = $exception->getMessage();

		$this->assertEquals($expectedMessage, $actualMessage);
	}

	/**
	 * @test
	 */
	public function can_create_for_8bit_character_found(): void
	{
		$expectedMessage = 'Body text cannot contain 8 bit characters!';
		$exception = InvalidBodyText::eightBitCharacterFound();

		$actualMessage = $exception->getMessage();

		$this->assertEquals($expectedMessage, $actualMessage);
	}

	/**
	 * @test
	 */
	public function can_create_for_line_too_long(): void
	{
		$expectedMessage = 'Lines must be limited to 998 characters excluding CRLF!';
		$exception = InvalidBodyText::lineTooLong();

		$actualMessage = $exception->getMessage();

		$this->assertEquals($expectedMessage, $actualMessage);
	}
}
