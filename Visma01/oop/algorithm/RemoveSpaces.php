<?php

namespace algorithm;

use algorithm\interfaces\RemoveInterface;

class RemoveSpaces implements RemoveInterface
{
    public function remove(array $allPatterns): array
    {
        $patternWithoutSpaces = preg_replace('/\s/', '', $allPatterns);
        return $patternWithoutSpaces;
    }
}