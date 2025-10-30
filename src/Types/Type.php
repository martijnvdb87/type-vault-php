<?php

namespace Martijnvdb\TypeVault\Types;

use Martijnvdb\TypeVault\Errors\TypeVaultValidationError;

class TypeOptions
{
    public function __construct(
        public bool $nullable = false,
        public bool $immutable = false
    ) {
        //
    }
}

abstract class Type
{
    private TypeOptions $options;
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

            $this->value = $this->dangerouslyModifySetValue($value);
        }
    }

    final public function __construct(mixed $value, TypeOptions | null $options = null)
    {
        $this->options = $options ?? new TypeOptions();
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

    public static function nullable(mixed $value): static
    {
        return new static($value, new TypeOptions(nullable: true));
    }

    public static function immutable(mixed $value): static
    {
        return new static($value, new TypeOptions(immutable: true));
    }
}
