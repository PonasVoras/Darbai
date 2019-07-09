<?php

namespace algorithm;

require "oop/algorithm/RemoveInterface.php";


class RemoveSpaces implements RemoveInterface
{
    public function removeSpaces(array $allPatterns): array
    {
        $patternWithoutSpaces = preg_replace('/\s/', '', $allPatterns);
        return $patternWithoutSpaces;
    }
}