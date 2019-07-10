<?php

namespace algorithm;

use algorithm\interfaces\RemoveInterface;

class RemoveDots implements RemoveInterface
{
    public function remove(array $allPatterns): array
    {
        $patternWithoutDots = str_replace('.', '', $allPatterns);
        return $patternWithoutDots;
    }
}