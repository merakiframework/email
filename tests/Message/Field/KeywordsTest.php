<?php
declare(strict_types=1);

namespace Meraki\Email\Message\Field;

use Meraki\Email\Message\Field as HeaderField;
use Meraki\Email\Message\Field\Keywords as KeywordsField;
use Meraki\Email\Message\FieldTest;

/**
 * @covers KeywordsField::
 */
final class KeywordsTest extends FieldTest
{
	/**
	 * @test
	 */
	public function has_the_correct_name(): void
	{
		$field = $this->createField();

		$fieldName = $field->name();

		$this->assertEquals('Keywords', $fieldName);
	}

	/**
	 * @test
	 */
	public function can_add_phrase(): void
	{
		$field = new KeywordsField('test', 'email');

		$field = $field->add('message');

		$this->assertCount(3, $field->phrases());
		$this->assertContains('message', $field->phrases());
	}

	/**
	 * @test
	 */
	public function adding_a_phrase_to_field_is_immutable(): void
	{
		$createdField = new KeywordsField('test', 'email');

		$addedField = $createdField->add('message');

		$this->assertNotSame($createdField, $addedField);
		$this->assertEquals(['test', 'email'], $createdField->phrases());
		$this->assertCount(2, $createdField->phrases());
		$this->assertEquals(['test', 'email', 'message'], $addedField->phrases());
		$this->assertCount(3, $addedField->phrases());
	}

	/**
	 * @test
	 */
	public function can_get_body(): void
	{
		$field = $this->createField();

		$actualText = $field->body();

		$this->assertSame('test,email,message', $actualText);
	}

	/**
	 * @test
	 */
	public function can_get_phrases(): void
	{
		$expectedPhrases = ['test', 'email', 'message'];
		$field = new KeywordsField(...$expectedPhrases);

		$actualPhrases = $field->phrases();

		$this->assertEquals($expectedPhrases, $actualPhrases);
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
		return new KeywordsField('test', 'email', 'message');
	}
}
