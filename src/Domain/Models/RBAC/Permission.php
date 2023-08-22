<?php
namespace App\Domain\Models\RBAC;

use App\Domain\Models\RBAC\Utilities\ExtractNameUtility as extractName;
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

    public static function makeWithPreferableName(
        ContextIntent $contextIntent,
        Resource|string $resource
    ) {
        $resourceName = extractName::extractName($resource);
        $name = implode(':', [
            'can',
            strtolower($contextIntent->value),
            strtolower($resourceName)
        ]);

        return new self($name, $contextIntent);
    }

    public function satisfies(Permission|ContextIntent $constraint): bool
    {
        $intent = $constraint;
        if ($constraint instanceof Permission) {
            // Special Case `Custom`
            if ($constraint->intent === ContextIntent::CUSTOM) {
                return $constraint->name === $constraint->name;
            }
            $intent = $constraint->intent;
        }
        return $intent === $this->intent || $this->intent === ContextIntent::FREEPASS;
    }
}