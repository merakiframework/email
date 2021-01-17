<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Message\Field\Subject as SubjectField;
use Meraki\Email\Message\FieldTest;

/**
 * @covers SubjectField::
 */
final class SubjectTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_the_correct_name(): void
	{
		$field = $this->createField();

		$fieldName = $field->name();

		$this->assertEquals('Subject', $fieldName);
	}

	/**
	 * @test
	 */
	public function can_get_body(): void
	{
		$expectedText = 'This is a test message!';
		$field = new SubjectField($expectedText);

		$actualText = $field->body();

		$this->assertSame($expectedText, $actualText);
	}

	/**
	 * @test
	 */
	public function is_not_required_to_appear_in_message_header(): void
	{
		$field = $this->createField();

		$isRequired = $field->required();

		$this->assertFalse($isRequired);
	}

	/**
	 * @test
	 */
	public function can_only_occur_once_in_message_header(): void
	{
		$field = $this->createField();

		$occurrenceCount = $field->occurrenceCount();

		$this->assertEquals(1, $occurrenceCount);
	}

	protected function createField(): HeaderField
	{
		return new SubjectField('This is a test message!');
	}
}
