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
        $proxy = new HyphenationSource($hyphenationPrimary);
        $hyphenatedWord = $proxy->findHyphenatedWord($word);
        return $hyphenatedWord;
    }

    public function paragraphHyphenation(): array
    {
        $this->hyphenateParagraph = new HyphenateParagraph();
        return $this->hyphenateParagraph->hyphenateParagraph();
    }
}
