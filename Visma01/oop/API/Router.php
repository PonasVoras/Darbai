<?php

namespace API;

use API\Controller\Controller;

//include "View/MainView.php";

class Router
{
    private $controller;

    public function __construct()
    {
        $this->controller = new Controller();

    }

    //picks the right controller and provides data to them
    public function useController()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestURI = $_SERVER['REQUEST_URI'];
        $word = !empty(strrpos($requestURI, 'word')) ?? true;
        $hyphenatedWord = !empty(strrpos($requestURI, 'hyphenatedWord')) ?? true;
        $pattern = !empty(strrpos($requestURI, 'pattern')) ?? true;
        $id = preg_replace('/[^0-9]/', '', $requestURI);
        $requestParameters = array(
            'method' => $method,
            'word' => $word,
            'hyphenatedWord' => $hyphenatedWord,
            'pattern' => $pattern,
            'id' => $id);
        $this->controller->handleRequest($requestParameters);
    }
}
