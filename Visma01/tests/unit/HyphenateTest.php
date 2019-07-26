<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

//Class to test
use Algorithm\Hyphenate;

//Classes to mock
use Database\Database;
use Cache\CacheItem;
use Algorithm\ManagePattern;

// TODO make a provider, assert things

class HyphenateTest extends TestCase
{

    private $databaseStub;
    private $manageStub;
    private $cacheStub;
    private $hyphenate;

    public function mocking()
    {
        //Mock database
        $this->databaseStub = $this->createMock(Database::class);
        $this->cacheStub = $this->createMock(CacheItem::class);
        $this->manageStub = $this->createMock(ManagePattern::class);
    }

    /**
     * @dataProvider dataProvider
     */
    public function testHyphenate(){
        $this->hyphenate = new Hyphenate($this->manageStub, $this->databaseStub, $this->cacheStub);
        $this->assertTrue(true);

    }


    public function dataProvider()
    {
        return array(
            array('m0i2s1t2r2a2n2s1l2a2t2e', 'mis-trans-late'),
            array('n2e2t1w2o2r2k', 'net-work'),
        );
    }
}
