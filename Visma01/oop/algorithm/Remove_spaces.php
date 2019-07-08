<?php

namespace algorithm;

class RemoveSpaces{
    public function removeSpaces(array $allPatterns): array {
        $patternWithoutSpaces = preg_replace('/\s/', '', $allPatterns);
        return $patternWithoutSpaces;
    }
}