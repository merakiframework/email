<?php
declare(strict_types=1);

namespace Meraki\Email\Message;

use Meraki\Email\Message\Field as HeaderField;
use Meraki\TestSuite\TestCase;

abstract class FieldTest extends TestCase
{
	/**
	 * @test
	 */
	public function is_a_message_header_field(): void
	{
		$field = $this->createField();

		$this->assertInstanceOf(HeaderField::class, $field);
	}

	/**
	 * @test
	 */
	public function can_get_as_field_line(): void
	{
		$field = $this->createField();
		$expectedFieldLine = sprintf("%s: %s\r\n", $field->name(), $field->body());

		$actualFieldLine = (string) $field;

		$this->assertEquals($expectedFieldLine, $actualFieldLine);
	}

	abstract protected function createField(): HeaderField;
}
