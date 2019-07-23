<?php


namespace Database;


class QueryBuilder
{
    private $query = "";

    public function table(string $tableOptions)
    {
        $queryPart = " TABLE ". $tableOptions;
        $this->query .= $queryPart;
        return $this;
    }

    public function from(string $fromOptions)
    {
        $queryPart = " FROM ". $fromOptions;
        $this->query .= $queryPart;
        return $this;
    }

    public function where(string $whereOptions)
    {
        $queryPart = " WHERE ". $whereOptions;
        $this->query .= $queryPart;
        return $this;
    }

    public function set(string $setOptions)
    {
        $queryPart = " SET ". $setOptions;
        $this->query .= $queryPart;
        return $this;
    }

    public function values(string $valueOptions)
    {
        $queryPart = " VALUES ". $valueOptions;
        $this->query .= $queryPart;
        return $this;
    }

    public function insert(string $insertOptions)
    {
        $queryPart = "INSERT INTO ". $insertOptions;
        $this->query = $queryPart;
        return $this;
    }

    public function select(string $whereOptions)
    {
        $queryPart = "SELECT ". $whereOptions;
        $this->query = $queryPart;
        return $this;
    }

    public function delete(string $deleteOptions)
    {
        $queryPart = "DELETE FROM ". $deleteOptions;
        $this->query = $queryPart;
        return $this;
    }

    public function update(string $updateOptions)
    {
        $queryPart = "UPDATE ". $updateOptions;
        $this->query = $queryPart;
        return $this;
    }

    public function build(){
        return $this->query;
    }
}