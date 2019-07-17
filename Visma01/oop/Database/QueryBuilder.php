<?php


namespace Database;


class QueryBuilder
{
    private $query;

    public function insertInto($table)
    {
        $this->query = 'INSERT INTO ' . $table;
    }
}