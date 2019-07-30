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
                $this->putRequest($id,$requestData);
                break;
            case 'POST':
                $this->postRequest($requestData);
                break;
            case 'DELETE':
                $this->deleteRequest($id);
                break;
            default :
                $this->view->returnError(405);
                break;
        }
    }

    public function postRequest(array $requestData)
    {
        $postData = file_get_contents('php://input');
        $postData = json_decode($postData, true);
        $wordToInsert = $postData['word'];
        $this->model->postWord($wordToInsert);
        $requestData['word'] = true;
        $this->getRequestReturnMany($requestData);
    }

    public function putRequest(string $id,array $requestData)
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true);
        $word = $data['word'];
        $this->model->updateWordById($id, $word);
        $requestData['word'] = true;
        $this->getRequestReturnMany($requestData);
    }

    public function deleteRequest(string $id)
    {
        $this->model->deleteWordById($id);
        $requestData['word'] = true;
        $this->getRequestReturnMany($requestData);
    }

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