<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\PhoneNumber;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    public function testItSetsValueCorrectly(): void
    {
        $values = [
            '+123456789',
            '+12',
            '+2468',
            '+97531',
        ];

        foreach ($values as $value) {
            $phoneNumber = new PhoneNumber($value);
            $this->assertEquals($value, $phoneNumber->value);
        }
    }

    /**
     * @return array<mixed>
     */
    public static function invalidDataSet(): array
    {
        return [
            [1],
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
            ['+0987654321'],
            ['+1234567891234567'],
            ['1234567890'],
            ['00123456789'],
        ];
    }

    #[DataProviderExternal(self::class, 'invalidDataSet')]
    public function testItShouldThrowExceptionWhenValueIsInvalid(mixed $value): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new PhoneNumber($value);
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $phoneNumber = new PhoneNumber(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($phoneNumber->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new PhoneNumber(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $phoneNumber = new PhoneNumber('+123456789', new TypeOptionsDTO(immutable: false));

        $newPhoneNumber = '+12';

        $phoneNumber->value = $newPhoneNumber;
        $this->assertEquals($newPhoneNumber, $phoneNumber->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $phoneNumber = new PhoneNumber('+123456789', new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $phoneNumber->value = '+12';
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $phoneNumber = PhoneNumber::nullable();
        $this->assertNull($phoneNumber->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        PhoneNumber::immutable('+123456789')->value = '+12';
    }
}
