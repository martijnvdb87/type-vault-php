<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\TypeVaultValidationError;

/**
 * @property mixed $value
 */
abstract class Type
{
    private mixed $state;
    private bool $immutable = false;
    private bool $nullable = false;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function __get(string $key): mixed
    {
        if ($key === 'value') {
            return $this->getValue();
        }

        throw new \Exception('Undefined property: ' . $key);
    }

    public function __set(string $key, mixed $value): void
    {
        if ($key === 'value') {
            $this->setValue($value);

            return;
        }

        throw new \Exception('Undefined property: ' . $key);
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function isImmutable(): bool
    {
        return $this->immutable;
    }

    protected function assertMutable(): void
    {
        if ($this->isImmutable()) {
            throw new TypeVaultValidationError();
        }
    }

    protected function assertNullable(): void
    {
        if ($this->isNullable()) {
            throw new TypeVaultValidationError();
        }
    }

    protected function dangerouslyModifyGetValue(mixed $value): mixed
    {
        return $value;
    }

    protected function dangerouslyModifySetValue(mixed $value): mixed
    {
        return $value;
    }

    protected function modifier(mixed $value): mixed
    {
        return $value;
    }

    private function getValue(): mixed
    {
        return $this->dangerouslyModifyGetValue($this->state);
    }

    private function setValue(mixed $value): void
    {
        $this->assertMutable();

        if ($value === null) {
            $this->assertNullable();

            $this->state = $this->dangerouslyModifySetValue(null);

            return;
        }

        $modifiedValue = $this->modifier($value);

        if (!$this->validate($modifiedValue)) {
            throw new TypeVaultValidationError();
        }

        $this->state = $this->dangerouslyModifySetValue($value);
    }

    abstract protected function validate(mixed $value): bool;
}
