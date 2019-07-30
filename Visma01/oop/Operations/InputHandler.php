<?php

namespace Operations;

use Algorithm\Hyphenate;
use Algorithm\HyphenateParagraph;

class InputHandler
{
    private $hyphenateParagraph;

    public function wordHyphenation(string $word, Hyphenate $hyphenate): string
    {
        $hyphenationPrimary = new HyphenationPrimary($hyphenate);
        $proxy = new HyphenationProxy($hyphenationPrimary);
        $hyphenatedWord = $proxy->findHyphenatedWord($word);
        return $hyphenatedWord;
    }

    public function paragraphHyphenation(Hyphenate $hyphenate): array
    {
        $this->hyphenateParagraph = new HyphenateParagraph($hyphenate);
        return $this->hyphenateParagraph->hyphenateParagraph();
    }
}
