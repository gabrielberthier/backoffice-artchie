<?php

namespace App\Presentation\Helpers\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

/**
 * @author Gabriel Berthier <gabrielnberthier@gmail.com>
 */
class FileRule extends AbstractRule
{
    private string $gourpAllowedChars = '([a-zA-Z0-9_\\.\-\(\):%&$#\/]+)';

    private array $allowedFormats = [
        "obj",
        "fbx",
        "png",
        "jpg",
        "dae",
        "3ds",
        "dxf",
        "bpm",
        "tif",
        "tga",
        "jpg",
        "psd",
        "glb",
        "gltf",
    ];

    public function validate($input): bool
    {
        $joinedFormats = implode("|", $this->allowedFormats);
        $groupAllowedFormats = sprintf('(%s)', $joinedFormats);
        $fullRegex =
            "/^" .
            $this->gourpAllowedChars .
            "\." .
            $groupAllowedFormats .
            '$/m';

        return preg_match($fullRegex, $input, $output_array);
    }

    public function addFormat(string $fileFormat)
    {
        $this->allowedFormats[] = $fileFormat;
    }
}
