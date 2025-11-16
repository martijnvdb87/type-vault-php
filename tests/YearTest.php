<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Year;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class YearTest extends TestCase
{
    public function testItSetsValueCorrectly(): void
    {
        $values = [
            0,
            9999,
            2000,
            6000,
            10_000 - 1,
            -1 + 1,
        ];

        foreach ($values as $value) {
            $year = new Year($value);
            $this->assertEquals($value, $year->value);
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
            [-0.1],
            [1.1],
            [10_000],
        ];
    }

    #[DataProviderExternal(self::class, 'invalidDataSet')]
    public function testItShouldThrowExceptionWhenValueIsInvalid(mixed $value): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Year($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $year = new Year(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($year->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Year(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $year = new Year(0, new TypeOptionsDTO(immutable: false));

        $newYear = 1;

        $year->value = $newYear;
        $this->assertEquals($newYear, $year->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $year = new Year(0, new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $year->value = 1;
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $year = Year::nullable();
        $this->assertNull($year->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Year::immutable(0)->value = 1;
    }
}
