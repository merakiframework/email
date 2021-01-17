<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Message\Field\Comments as CommentsField;
use Meraki\Email\Message\FieldTest;

/**
 * @covers CommentsField::
 */
final class CommentsTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_the_correct_name(): void
	{
		$field = $this->createField();

		$fieldName = $field->name();

		$this->assertEquals('Comments', $fieldName);
	}

	/**
	 * @test
	 */
	public function can_get_body(): void
	{
		$expectedText = 'Testing with plain text messages!';
		$field = new CommentsField($expectedText);

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
		return new CommentsField('Testing with plain text messages!');
	}
}
