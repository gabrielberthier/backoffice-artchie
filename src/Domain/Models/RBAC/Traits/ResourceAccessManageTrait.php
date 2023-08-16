<?php
namespace App\Domain\Models\RBAC\Traits;

use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Utilities\ExtractNameUtility;

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
    public function getResource(Resource|string $resource): Resource
    {
        return $this->resources[ExtractNameUtility::extractName($resource)];
    }

    public function createResource(string $name, string $description): Resource
    {
        if (!in_array($name, $this->resources, true)) {
            $resource = new Resource($name, $description);
            $this->resources[$name] = $resource;
        }

        return $resource;
    }
}