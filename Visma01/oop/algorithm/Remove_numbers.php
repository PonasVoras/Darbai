<?php

namespace algorithm;

class RemoveNumbers{
    public function removeNumbers(string $allPatterns): string {
        $patternWithoutNumbers = preg_replace('/\d/', '', $allPatterns);
        return $patternWithoutNumbers;
    }
}