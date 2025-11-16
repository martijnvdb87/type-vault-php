<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Percentage;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class PercentageTest extends TestCase
{
    public function testItSetsValueCorrectly(): void
    {
        $values = [
            0,
            1,
            0.5,
            0.1,
            0.01,
            0.001,
            0.0001,
            0.00001,
            0.000001,
            0.0000001,
            0.9,
            0.99,
            0.999,
            0.9999,
            0.99999,
            0.999999,
        ];

        foreach ($values as $value) {
            $percentage = new Percentage($value);
            $this->assertEquals($value, $percentage->value);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function invalidDataSet(): array
    {
        return [
            ['foo'],
            ['foo@example'],
            ['foo@example.'],
            [[]],
            [true],
            [false],
            [null],
            [-1],
            [2],
            [-0.1],
            [1.1],
        ];
    }

    #[DataProviderExternal(self::class, 'invalidDataSet')]
    public function testItShouldThrowExceptionWhenValueIsInvalid(mixed $value): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Percentage($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $percentage = new Percentage(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($percentage->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Percentage(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $percentage = new Percentage(0, new TypeOptionsDTO(immutable: false));

        $newPercentage = 1;

        $percentage->value = $newPercentage;
        $this->assertEquals($newPercentage, $percentage->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $percentage = new Percentage(0, new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $percentage->value = 1;
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $percentage = Percentage::nullable();
        $this->assertNull($percentage->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Percentage::immutable(0)->value = 1;
    }
}
