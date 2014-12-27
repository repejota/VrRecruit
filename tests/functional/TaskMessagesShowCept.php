<?php
$I = new TestGuy($scenario);

$I->wantTo('Show task messages');
$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET("/message/6?format=json");
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();