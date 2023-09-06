<?php

namespace App\Infrastructure\Uuid;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidEncoder
{
    public static function encode(UuidInterface $uuid): string
    {
        return gmp_strval(
            gmp_init(
                str_replace('-', '', $uuid->toString()),
                16
            ),
            62
        );
    }

    public static function decode(string $encoded): ?UuidInterface
    {
        try {
            return Uuid::fromString(array_reduce(
                [20, 16, 12, 8],
                static function ($uuid, $offset) {
                    return substr_replace($uuid, '-', $offset, 0);
                },
                str_pad(
                    gmp_strval(
                        gmp_init($encoded, 62),
                        16
                    ),
                    32,
                    '0',
                    STR_PAD_LEFT
                )
            ));
        } catch (\Throwable $throwable) {
            return null;
        }
    }
}
