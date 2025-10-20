<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\TypeVaultValidationError;

abstract class Type
{
    private bool $immutable = false;
    private bool $nullable = false;

    public mixed $value {
        get => $this->dangerouslyModifyGetValue($this->value);

        set(mixed $value) {
            $this->assertMutable();

            if ($value === null) {
                $this->assertNullable();

                $this->value = $this->dangerouslyModifySetValue(null);

                return;
            }

            $modifiedValue = $this->modifier($value);

            if (!$this->validate($modifiedValue)) {
                throw new TypeVaultValidationError();
            }

            $this->value = $this->dangerouslyModifySetValue($value);
        }
    }

    public function __construct(mixed $value)
    {
        $this->value = $value;
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

    abstract protected function validate(mixed $value): bool;
}
