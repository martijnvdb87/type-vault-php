<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\DTOs\TypeOptionsDTO;
use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

abstract class Type
{
    private TypeOptionsDTO $options;
    private bool $isInitialized = false;

    public mixed $value {
        get => $this->dangerouslyModifyGetValue($this->value);

        set(mixed $value) {
            if ($this->isInitialized) {
                $this->assertMutable();
            }

            if ($value === null) {
                $this->assertNullable();

                $this->value = $this->dangerouslyModifySetValue(null);

                return;
            }

            $modifiedValue = $this->modifier($value);

            if (!$this->validate($modifiedValue)) {
                throw new TypeVaultValidationError();
            }

            $this->value = $this->dangerouslyModifySetValue($modifiedValue);
        }
    }

    final public function __construct(mixed $value, TypeOptionsDTO | null $options = null)
    {
        $this->options = $options ?? new TypeOptionsDTO();
        $this->value = $value;

        $this->isInitialized = true;
    }

    public function isNullable(): bool
    {
        return $this->options->nullable;
    }

    public function isImmutable(): bool
    {
        return $this->options->immutable;
    }

    protected function assertMutable(): void
    {
        if ($this->isImmutable()) {
            throw new TypeVaultValidationError();
        }
    }

    protected function assertNullable(): void
    {
        if (!$this->isNullable()) {
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

    public static function nullable(mixed $value = null): static
    {
        return new static($value, new TypeOptionsDTO(nullable: true));
    }

    public static function immutable(mixed $value): static
    {
        return new static($value, new TypeOptionsDTO(immutable: true));
    }
}
