<?php

use PHPUnit\Framework\TestCase;

use Algorithm\SortPattern;
use Algorithm\Utils\Remove;

// TODO mock classes, add expects thing

class SortPatternTest extends TestCase
{

    private $possiblePatterns;
    private $sortPattern;

    private const PATTERNS =[
        '.mis1',
        'a2n',
        'm2is',
        '2n1s2',
        'n2sl',
        's1l2',
        's3lat',
        'st4r',
        '1tra'
    ];

    protected function setUp(): void
    {
        $this->sortPattern =  new SortPattern(new Remove());
    }

    /**
     * @param $word
     * @param $result
     *
     * @dataProvider dataProvider
     */
    public function testSorting($word, $result){
        $this->sortPattern->setWord($word);
        $this->sortPattern->possiblePatterns = self::PATTERNS;
        $this->assertSame($this->sortPattern->sortPattern(),$result);
    }

    public function dataProvider()
    {
        return [
            'mistranslate' => ['mistranslate', 'm0i0s1t4r0a2n2s3l2a0te0']
        ];
    }

    protected function tearDown(): void
    {
        unset($this->possiblePatterns);
    }



}