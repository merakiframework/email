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

	abstract protected function createField(): HeaderField;
}
