<?php

namespace Algorithm\Interfaces;

interface RemoveInterface
{
    public function removeDots(array $allPatterns): array;

    public function removeNumbers(array $allPatterns): array;

    public function removeSpaces(array $allPatterns): array;

}