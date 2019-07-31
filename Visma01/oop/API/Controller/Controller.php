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
        $word = $requestData['word'];
        switch ($requestData['method']) {
            case 'GET':
                if (empty($word)) {
                    $this->view->mainView();
                } else {
                    if (empty($id)) {
                        $this->getRequestReturnMany($requestData);
                    } else {
                        $this->getRequestReturnSingle($id, $requestData);
                    }
                }
                break;
            case 'PUT':
                $this->putRequest($id, $requestData);

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

        $word = $this->retrieveMethodData('word');
        $this->model->postWord($word);
        $requestData['word'] = true;
        $this->responseData['method'] = 'POST';
        $this->responseData['data'] = $word;
        $this->getRequestReturnMany($requestData);
    }

    private function retrieveMethodData(string $name): string
    {
        $data = file_get_contents('php://input');
        $data = json_decode($data, true) ?? $_POST[$name];
        return $data;
    }

    public function putRequest(string $id, array $requestData)
    {
        $word = $this->retrieveMethodData('word');
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
        $this->responseData['method'] = 'GET';
        $dbData = $this->model->getWordByID($id);

        if ($dbData !== '404') {
            $this->responseData['data'] = $dbData;
            $this->returnToView();
        } else {
            $this->view->returnError($dbData);
        }
    }

    public function getRequestReturnMany(array $requestData)
    {
        $this->responseData['method'] = 'GET';
        $this->responseData['data'] = $this->model->getWords();
        $this->returnToView();
    }

    private function returnToView()
    {
        $this->view->returnResponse($this->responseData);
    }
}