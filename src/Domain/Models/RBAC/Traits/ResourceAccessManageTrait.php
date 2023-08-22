<?php
namespace App\Domain\Models\RBAC\Traits;

use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Utilities\ExtractNameUtility;
use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;

trait ResourceAccessManageTrait
{
    /** @var array<string, Resource> */
    public array $resources = [];

    /**
     * @return Resource[]
     */
    public function getResources(): array
    {
        return array_values($this->roles);
    }
    /** @return Option<Resource> */
    public function getResource(Resource|string $resource): Option
    {
        $nameUtility = ExtractNameUtility::extractName($resource);

        $exists = key_exists($nameUtility, $this->resources);

        return $exists ? new Some($this->resources[$nameUtility]) : None::create();
    }

    public function createResource(string $name, string $description): Resource
    {
        $resource = new Resource($name, $description);

        if (!key_exists($name, $this->resources)) {
            $this->resources[$name] = $resource;
        }

        return $resource;
    }

    public function appendResource(Resource $resource): self
    {
        if (!key_exists($resource->name, $this->resources)) {
            $this->resources[$resource->name] = $resource;
        }

        return $this;
    }
}