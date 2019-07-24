<?php

namespace API\Controller;

use API\Model\Model;
use API\View\View;

class Controller
{
    //used classes
    private $model;
    private $view;


    //response view json
    private $responseData = array(
        'statusCode' => 200,
        'method' => "",
        'data' => ""
    );

    public function __construct()
    {
        $this->model = new Model();
        $this->view = new View();
    }

    // true is needed for json object to be converted to an array
    // {"method":"GET","word":true,"hyphenatedWord":false,"pattern":false,"id":"1"}
    public function handleRequest(array $requestData)
    {
        $id = $requestData['id'];
        switch ($requestData['method']) {
            case 'GET':
                if (empty($id)) {
                    $this->getRequestReturnMany($requestData);
                } else {
                    $this->getRequestReturnSingle($id, $requestData);
                }
                break;
            case 'PUT':
                $this->putRequest($requestData);
                break;
            case 'POST':
                $this->postRequest($requestData);
                break;
            case 'DELETE':
                $this->deleteRequest($id, $requestData);
                break;
            default :
                //405 - method not allowed
                $this->view->returnError(405);
                break;
        }
    }

    //add item : word, hyphenatedWord, pattern
    //$post = file_get_contents('php://input');
    /*{
    "statusCode": 200,
    "method": "GET",
    "data": [
    {
    "word": "frame"
    },
    {
        "word": "framed"
            },
    {
        "word": "framework"
            },
    {
        "word": "hyphenate"
            },
    {
        "word": "mistranslate"
            }
    ]
    }*/
    public function postRequest(array $requestData)
    {
        $postData = file_get_contents('php://input');
        $postData = json_decode($postData, true);
        $wordToInsert = $postData['word'];

        $this->model->postWord($wordToInsert);
        $requestData['word'] = true;
        $this->getRequestReturnMany($requestData);
    }

    //update item : word
    public function putRequest(array $requestData)
    {
        $putData = file_get_contents('php://input');
        $putData = json_decode($putData, true);
        $wordToUpdate = $putData['wordToUpdate'];
        $word = $putData['word'];

        $this->model->updateWord($word, $wordToUpdate);
        $requestData['word'] = true;
        $this->getRequestReturnMany($requestData);
    }

    //erase an item : word, hyphenatedWord
    public function deleteRequest(string $id, array $requestData)
    {
        $deleteData = file_get_contents('php://input');
        $deleteData = json_decode($deleteData, true);
        $wordToDelete = $deleteData['word'];

        $this->model->deleteWord($wordToDelete);
        $requestData['word'] = true;
        $this->getRequestReturnMany($requestData);
    }

    //http://127.0.0.1:8888/words?id=19
    //display an item : word, hyphenatedWord, pattern
    public function getRequestReturnSingle(string $id, array $requestData)
    {
        if (!empty($requestData['word'])) {
            $this->responseData['method'] = 'GET';
            $dbData = $this->model->getWordByID($id);

            // Validation can be a different method
            if ($dbData !== '404') {
                $this->responseData['data'] = $dbData;
                $this->returnToView();
            } else {
                $this->view->returnError($dbData);
            }
        } else {
            $this->view->returnError(400);
        }

    }

    //http://127.0.0.1:8888/words
    //display an item : word, hyphenatedWord, pattern
    public function getRequestReturnMany(array $requestData)
    {
        if (!empty($requestData['word'])) {
            $this->responseData['method'] = 'GET';
            $this->responseData['data'] = $this->model->getWords();
            $this->returnToView();
        } else {
            $this->view->returnError(400);
        }
        echo "\n";
    }

    private function returnToView()
    {
        $this->view->returnResponse($this->responseData);
    }
}