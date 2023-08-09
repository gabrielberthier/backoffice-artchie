<?php
namespace App\Domain\Models\RBAC;

use DateTimeImmutable;
use DateTimeInterface;

readonly class Permission
{
    public DateTimeInterface $createdAt;
    public DateTimeInterface $updatedAt;
    public function __construct(
        public string $name,
        public ContextIntent $intent,
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function satisfies(Permission|ContextIntent $constraint): bool
    {
        $intent = $constraint;
        if ($constraint instanceof Permission) {
            $intent = $constraint->intent;
        }
        return $intent === $this->intent || $this->intent === ContextIntent::FREEPASS;
    }
}