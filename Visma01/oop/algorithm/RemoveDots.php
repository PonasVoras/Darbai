<?php

namespace algorithm;

class RemoveDots
{
    public function removeDots(array $allPatterns): array
    {
        $patternWithoutDots = str_replace('.', '', $allPatterns);
        return $patternWithoutDots;
    }
}