<?php

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->sets([
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::CODE_QUALITY,
        SetList::CODING_STYLE,
    ]);
};