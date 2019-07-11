<?php

namespace Cache;

use Cache\Interfaces\CacheItemPoolInterface;
use Cache\Interfaces\CacheItemInterface;

class CacheItemPool implements  CacheItemPoolInterface
{

    public function getItem($key){}


    public function getItems(array $keys = array()){}


    public function hasItem($key){}


    public function clear(){}


    public function deleteItem($key){}


    public function deleteItems(array $keys){}


    public function save(CacheItemInterface $item){}


    public function saveDeferred(CacheItemInterface $item){}


    public function commit(){}
}
