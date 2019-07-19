<?php

namespace API\Controller;

use VI;

class Controller
{
    private $responseData;

    public function __construct()
    {

    }

    public function handleRequest(array $requestData)
    {

    }

    //add item : word, hyphenatedWord, pattern
    public function postRequest(string $word)
    {

    }

    //update item : word
    public function putRequest()
    {

    }

    //erase an item : word, hyphenatedWord
    public function deleteRequest()
    {

    }

    //display an item : word, hyphenatedWord, pattern
    public function getRequest()
    {

    }

    public function returnError(string $data, int $statusCode = 404){

    }

    public function returnResponse(string $data, int $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: text/json;charset=utf-8', true);
        echo json_encode($this->responseData);
    }
}