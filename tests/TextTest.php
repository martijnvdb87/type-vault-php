<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{
    /** @var string[] */
    private array $values = [
        'foo',
        'bar',
        'baz',
        'Lorem ipsum',
        '',
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $text = new Text($value);
            $this->assertEquals($value, $text->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            1,
            [],
            true,
            false,
            null,
        ];

        $this->expectException(TypeVaultValidationError::class);

        foreach ($values as $value) {
            new Text($value);
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $text = new Text(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($text->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Text(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $text = new Text('foo', new TypeOptionsDTO(immutable: false));

        $newText = 'bar';

        $text->value = $newText;
        $this->assertEquals($newText, $text->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $text = new Text('foo', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $text->value = 'bar';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $text = Text::nullable();
        $this->assertNull($text->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Text::immutable('foo')->value = 'bar';
    }
}
