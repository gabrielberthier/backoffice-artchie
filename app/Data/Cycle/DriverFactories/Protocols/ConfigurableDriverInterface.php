<?php

namespace Core\Data\Cycle\DriverFactories\Protocols;

interface ConfigurableDriverInterface
{
    public function withSchema(string $schema): static;
    public function withTimezone(string $tz): static;
    public function withQueryCache(bool $queryCache): static;
    public function withReadonlySchema(bool $readonlySchema): static;
    public function readonly(): static;

    public function factory(): DriverFactoryInterface;
}