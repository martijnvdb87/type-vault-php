<?php

namespace Martijnvdb\TypeVault\Tests;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use Martijnvdb\TypeVault\Types\Uuid;
use PHPUnit\Framework\TestCase;

class UuidTest extends TestCase
{
    /** @var string[] */
    private array $values = [
        '00000000-0000-0000-0000-000000000000',
        '194d4a10-7e01-11f0-8de9-0242ac120002',
        '194d4aba-7e01-11f0-8de9-0242ac120002',
        '32c7906b-3f58-317d-8350-446432d7ce70',
        '16ce7098-b557-3507-a28f-f039e1380096',
        '8943183f-9736-4411-b5fa-38da29c77b39',
        'e094963a-f6e7-4474-8bbc-4be9dbfc9e2a',
        '218de376-6e6e-504a-8a2c-c0253a2dd7ce',
        '337cedfe-415e-52e6-ad74-9fc25d1006a2',
        '1f07e023-3db2-6682-a819-8623a0d8b14e',
        '1f07e023-3db2-6683-84ee-4d1df0497dcb',
        '0198c917-ef4f-7caa-a35a-2b31a672d12e',
        '0198c917-ef4f-7bdc-bcc8-af4bb5cdeadd',
    ];

    public function testItSetsValueCorrectly(): void
    {
        foreach ($this->values as $value) {
            $uuid = new Uuid($value);
            $this->assertEquals($value, $uuid->value);
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
            new Uuid($value);
        }
    }

    public function testItShouldAllowNullIfNullableIsSetToTrue(): void
    {
        $uuid = new Uuid(null, new TypeOptionsDTO(nullable: true));
        $this->assertNull($uuid->value);
    }

    public function testItShouldThrowExceptionWhenValueIsNullAndNullableIsFalse(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        new Uuid(null, new TypeOptionsDTO(nullable: false));
    }

    public function testItShouldAllowValueChangeWhenImmutableIsFalse(): void
    {
        $uuid = new Uuid($this->values[0], new TypeOptionsDTO(immutable: false));

        $newUuid = $this->values[1];

        $uuid->value = $newUuid;
        $this->assertEquals($newUuid, $uuid->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedAndImmutableIsTrue(): void
    {
        $uuid = new Uuid($this->values[0], new TypeOptionsDTO(immutable: true));

        $this->expectException(TypeVaultValidationError::class);
        $uuid->value = $this->values[1];
    }

    public function testItShouldAllowNullIfNullableMethodIsUsed(): void
    {
        $uuid = Uuid::nullable(null);
        $this->assertNull($uuid->value);
    }

    public function testItShouldThrowExceptionWhenValueIsChangedWhenImmutableMethodIsUsed(): void
    {
        $this->expectException(TypeVaultValidationError::class);
        Uuid::immutable($this->values[0])->value = $this->values[1];
    }

    public function testItShouldGenerateValidUuids(): void
    {
        $uuid = Uuid::random();
        $this->assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9][0-9a-f]{3}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid->value);
    }
}
