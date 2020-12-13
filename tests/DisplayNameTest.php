<?php
declare(strict_types=1);

namespace Meraki\Email;

use Meraki\Email\DisplayName;
use Meraki\Email\Exception\DisplayName as DisplayNameException;
use Meraki\TestSuite\TestCase;

final class DisplayNameTest extends TestCase
{
	/**
	 * @test
	 */
	public function can_get_phrase(): void
	{
		$expectedPhrase = 'No-Reply';
		$displayName = new DisplayName($expectedPhrase);

		$actualPhrase = $displayName->getPhrase();

		$this->assertEquals($expectedPhrase, $actualPhrase);
	}

	/**
	 * @test
	 */
	public function using_as_value_object_returns_phrase(): void
	{
		$expectedPhrase = 'No-Reply';
		$displayName = new DisplayName($expectedPhrase);

		$actualPhrase = (string) $displayName;

		$this->assertEquals($expectedPhrase, $actualPhrase);
	}

	/**
	 * @test
	 */
	public function two_display_names_are_equal_if_have_same_value_regardless_of_case(): void
	{
		$expectedPhrase = 'No-Reply';
		$mixedCase = new DisplayName($expectedPhrase);
		$lowerCase = new DisplayName(strtolower($expectedPhrase));
		$upperCase = new DisplayName(strtoupper($expectedPhrase));

		$this->assertTrue($mixedCase->equals($lowerCase));
		$this->assertTrue($mixedCase->equals($upperCase));
		$this->assertTrue($lowerCase->equals($upperCase));
	}

	/**
	 * @test
	 */
	public function two_display_names_are_not_equal_if_they_have_different_phrases(): void
	{
		$displayName1 = new DisplayName('Test Account 1');
		$displayName2 = new DisplayName('Test Account 2');

		$this->assertFalse($displayName1->equals($displayName2));
	}

	/**
	 * @test
	 */
	public function phrase_is_not_quoted_if_valid_atom_text(): void
	{
		$expectedPhrase = 'Jane.Doe';
		$displayName = new DisplayName($expectedPhrase);

		$this->assertEquals($expectedPhrase, $displayName->getPhrase());
		$this->assertEquals($expectedPhrase, (string) $displayName);
	}

	/**
	 * @test
	 */
	public function phrase_is_quoted_if_contains_invalid_atom_character(): void
	{
		$expectedPhrase = '"Jane Doe"';
		$displayName = new DisplayName('Jane Doe');

		$this->assertEquals($expectedPhrase, $displayName->getPhrase());
		$this->assertEquals($expectedPhrase, (string) $displayName);
	}

	/**
	 * @test
	 */
	public function throws_exception_if_no_phrase_provided(): void
	{
		$exception = DisplayNameException::containsInvalidCharacters();

		$this->expectExceptionObject($exception);

		$displayName = new DisplayName('');
	}

	/**
	 * @test
	 */
	public function throws_exception_if_contains_invalid_quoted_string_character(): void
	{
		$exception = DisplayNameException::containsInvalidCharacters();

		$this->expectExceptionObject($exception);

		$displayName = new DisplayName('J."Doe"');
	}
}
