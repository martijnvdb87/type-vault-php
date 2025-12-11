<?php

namespace Martijnvdb\TypeVault\DTOs;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;
use ReflectionClass;

readonly class DTO
{
    /**
     * @param array<string, mixed> $overrides
     */
    public function copyWith(array $overrides): static
    {
        $reflection = new ReflectionClass($this);
        $props = [];

        foreach ($reflection->getProperties() as $prop) {
            $prop->setAccessible(true);
            $props[$prop->getName()] = $prop->getValue($this);
        }

        foreach ($overrides as $property => $value) {
            if (!property_exists($this, $property)) {
                throw new TypeVaultValidationError("Property '{$property}' does not exist in " . static::class);
            }

            $props[$property] = $value;
        }

        /** @phpstan-ignore-next-line */
        return new static(...$props);
    }

    public function clone(): static
    {
        return $this->copyWith([]);
    }
}
