<?php
namespace App\Domain\Models\RBAC\Traits;

use App\Domain\Models\RBAC\Resource;
use App\Domain\Models\RBAC\Utilities\ExtractNameUtility;
use App\Domain\OptionalApi\Option;
use App\Domain\OptionalApi\Option\None;
use App\Domain\OptionalApi\Option\Some;
use Exception;

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

        return $exists
            ? new Some($this->resources[$nameUtility])
            : new None();
    }

    public function createResource(string $name, string $description): Resource
    {
        $resource = new Resource($name, $description);

        if (!key_exists($name, $this->resources)) {
            $this->resources[$name] = $resource;
        }

        return $resource;
    }
}