<?php


namespace Operations\Interfaces;


interface HyphenationSourceInterface
{
    public function findHyphenatedWord(string $word):string;
}