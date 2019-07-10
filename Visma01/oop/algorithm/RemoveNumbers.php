<?php

namespace algorithm;

use algorithm\interfaces\RemoveInterface;

class RemoveNumbers implements RemoveInterface
{
    public function remove(array $allPatterns): array
    {
        $patternWithoutNumbers = preg_replace('/\d/', '', $allPatterns);
        $patternWithoutNumbers = preg_replace('/\s/', '', $patternWithoutNumbers);
        return $patternWithoutNumbers;
    }
}