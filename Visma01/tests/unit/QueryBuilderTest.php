<?php

use PHPUnit\Framework\TestCase;

use Database\QueryBuilder;

class QueryBuilderTest extends TestCase
{
    protected $queryBuilder;

    protected function setUp(): void
    {
        $this->queryBuilder = new QueryBuilder();
    }

    public function testQueries()
    {
        $this->assertSame($this->queryBuilder
             ->insert('patterns (pattern)')
             ->values('( :value )')
             ->build(),
            'INSERT INTO patterns (pattern) VALUES ( :value )');
    }

    public function tearDown(): void
    {
        unset($this->queryBuilder);
    }

}