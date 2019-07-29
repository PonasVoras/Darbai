<?php

use Codeception\Util\HttpCode;

class ApiCest
{

    public function postNewWord(ApiTester $I)
    {
        $I->wantToTest('Add new word and get all words');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/', ['word' => 'testcase']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseContains('{"word":"testcase"}');
    }

    public function getWords(ApiTester $I)
    {
        $I->wantToTest('Get all words');
        $I->sendGET('/words');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function putUpdateWord(ApiTester $I)
    {
       $I->wantToTest('Update word and get all words');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPUT('/', ['word' => 'testcase', 'wordToUpdate' => 'testcases']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
        $I->seeResponseContains('{"word":"testcases"}');
    }

    public function deleteDeleteWord(ApiTester $I)
    {
        $I->wantToTest('Delete word and get all words');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendDELETE('/', ['word' => 'testcases']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(200);
    }

    public function badRequest(ApiTester $I)
    {
        $I->wantToTest('Bad request');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/words?id=9999');
        $I->seeResponseCodeIs(404);
    }
}