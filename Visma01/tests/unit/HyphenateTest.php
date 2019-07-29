<?php
declare(strict_types=1);

use Algorithm\Hyphenate;
use Algorithm\ManagePattern;
use Cache\CacheItem;
use Database\Database;
use PHPUnit\Framework\TestCase;

//Class to test

//Classes to mock

// TODO make a provider, assert things

class HyphenateTest extends TestCase
{

    protected $databaseStub;
    protected $manageStub;
    protected $cacheStub;
    protected $hyphenate;

    protected function setUp(): void
    {
        //Mocking
        $this->databaseStub = $this->createMock(Database::class);
        $this->cacheStub = $this->createMock(CacheItem::class);
        $this->manageStub = $this->createMock(ManagePattern::class);
        $this->hyphenate = new Hyphenate($this->manageStub, $this->databaseStub, $this->cacheStub);
    }

    /**
     * @param $word
     * @param $hyphenatedWord
     *
     * @dataProvider dataProvider
     */
    public function testWordWithNumbersToHyphenatedWord(string $word, string $hyphenatedWord)
    {
        $this->hyphenate->wordWithNumbers = $word;
        $this->assertSame($this->hyphenate->getHyphenatedWord($word), $hyphenatedWord);
    }


    public function dataProvider()
    {
        return [
            'mistranslate' => ['m0i2s1t2r2a2n2s1l2a2t2e', 'mis-trans-late'],
            'network' => ['n2e2t1w2o2r2k', 'net-work']
        ];
    }

    protected function tearDown(): void
    {
        unset($this->manageStub);
        unset($this->cacheStub);
        unset($this->databaseStub);
        unset($this->hyphenate);
    }
}
