<?php

namespace algorithm;

class RemoveNumbers{
    public function removeNumbers(array $allPatterns): array {
        $patternWithoutNumbers = preg_replace('/\d/', '', $allPatterns);
        $patternWithoutNumbers = preg_replace('/\s/', '', $patternWithoutNumbers);
        return $patternWithoutNumbers;
    }
}