<?php
declare(strict_types=1);

namespace Meraki\Email\Exception;

use Meraki\Email\Exception\DisplayName as DisplayNameException;
use Meraki\TestSuite\TestCase;
use InvalidArgumentException;

final class DisplayNameTest extends TestCase
{
	/**
	 * @test
	 */
	public function is_an_invalid_argument_exception(): void
	{
		$exception = new DisplayNameException();

		$this->assertInstanceOf(InvalidArgumentException::class, $exception);
	}

	/**
	 * @test
	 */
	public function can_create_for_invalid_characters(): void
	{
		$expectedMessage = 'Display name contains invalid characters.';
		$exception = DisplayNameException::containsInvalidCharacters();

		$actualMessage = $exception->getMessage();

		$this->assertEquals($expectedMessage, $actualMessage);
	}
}
