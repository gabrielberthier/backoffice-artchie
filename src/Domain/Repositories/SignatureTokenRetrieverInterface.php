<?php

namespace App\Domain\Repositories;

use App\Domain\Models\Museum;
use App\Domain\Models\Security\SignatureToken;

interface SignatureTokenRetrieverInterface
{
    public function findFromMuseum(Museum $museum): ?SignatureToken;
}
