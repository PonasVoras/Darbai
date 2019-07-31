<?php


namespace API\View;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View
{
    private $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/templates');
        $this->twig = new Environment($loader);
    }

    public function returnError(int $statusCode = 404)
    {
        header('X-PHP-Response-Code: 404', true, $statusCode);
    }

    public function returnResponse(array $responseData, int $statusCode = 202)
    {
        //return json_encode($responseData);
        $this->wordsView($responseData);

    }

    public function wordsView(array $data){
        echo $this->twig->render('words.html.twig', array('Words' => $data));
    }


    public function mainView(){
        echo $this->twig->render('main.html.twig');
    }
}