<?php

namespace App\Presentation\Helpers\Validation\Rules;

use Respect\Validation\Rules\AbstractRule;

class FileRule extends AbstractRule
{
    private string $gourpAllowedChars = '([a-zA-Z0-9_\\.\-\(\):%&$#]+)';

    private array $allowedFormats = ['obj', 'fbx', 'png', 'jpg', 'dae', '3ds', 'dxf', 'bpm', 'tif', 'tga', 'jpg', 'psd'];

    public function validate($input): bool
    {
        $groupAllowedFormats = implode('|', $this->allowedFormats);
        $fullRegex = '^'.$this->gourpAllowedChars.'\\.'.$groupAllowedFormats.'$';

        return preg_match($fullRegex, $input, $output_array);
    }

    public function addFormat(string $fileFormat)
    {
        $this->allowedFormats[] = $fileFormat;
    }
}
