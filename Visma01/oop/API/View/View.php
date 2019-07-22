<?php


namespace API\View;


class View
{
    public function returnError(int $statusCode = 404)
    {
        header('X-PHP-Response-Code: 404', true, $statusCode);
    }

    public function returnResponse(array $responseData, int $statusCode = 202)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
        header("Access-Control-Max-Age: 3600");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        header('Content-Type: text/json;charset=utf-8', true);
        echo json_encode($responseData);
    }
}