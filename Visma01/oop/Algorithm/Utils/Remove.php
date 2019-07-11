<?php

namespace Algorithm\Utils;

use Algorithm\Interfaces\RemoveInterface;

class Remove implements RemoveInterface
{
    public function removeDots(array $allPatterns): array
    {
        $patternWithoutDots = str_replace('.', '', $allPatterns);
        return $patternWithoutDots;
    }

    public function removeNumbers(array $allPatterns): array
    {
        $patternWithoutNumbers = preg_replace('/\d/', '', $allPatterns);
        $patternWithoutNumbers = preg_replace('/\s/', '', $patternWithoutNumbers);
        return $patternWithoutNumbers;
    }

    public function removeSpaces(array $allPatterns): array
    {
        $patternWithoutSpaces = preg_replace('/\s/', '', $allPatterns);
        return $patternWithoutSpaces;
    }

}