<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Integer;
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
    public function testItSetsValueCorrectly(): void
    {
        $values = [
            PHP_INT_MIN,
            -99 - 1,
            0,
            1,
            99,
            PHP_INT_MAX,
            -2,
            -1,
            2,
        ];

        foreach ($values as $value) {
            $integer = new Integer($value);
            $this->assertEquals($value, $integer->value);
        }
    }

    public function testItShouldThrowExceptionWhenValueIsInvalid(): void
    {
        $values = [
            'foo',
            'foo@example',
            'foo@example.',
            '1',
            [],
            true,
            false,
            null,
        ];

        $this->expectException(TypeVaultValidationError::class);

        foreach ($values as $value) {
            new Integer($value);
        }
    }

    public function testItFloorsTheVale(): void
    {
        $values = [
            -2,
            -1.9,
            -1.1,
            -1,
            -0.9,
            -0.1,
            0,
            0.1,
            0.9,
            1,
            1.1,
            1.9,
            2,
        ];

        foreach ($values as $value) {
            $integer = new Integer($value);
            $this->assertEquals(intval($value), $integer->value);
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $integer = new Integer(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($integer->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Integer(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $integer = new Integer(1, new TypeOptionsDTO(immutable: false));

        $newInteger = 2;

        $integer->value = $newInteger;
        $this->assertEquals($newInteger, $integer->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $integer = new Integer(1, new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $integer->value = 2;
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $integer = Integer::nullable();
        $this->assertNull($integer->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Integer::immutable(1)->value = 2;
    }
}
