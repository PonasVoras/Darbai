<?php

namespace algorithm;

class RemoveNumbers{
    public function removeNumbers(string $pattern): string {
        $patternWithoutNumbers = preg_replace('/\d/', '', $pattern);
        return $patternWithoutNumbers;
    }
}