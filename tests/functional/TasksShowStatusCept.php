<?php
use Vreasy\Models\Task;

$I = new TestGuy($scenario);

$I->wantTo('Show a pending task');
$task = $I->haveTask(['assigned_name' => 'Jane Doe', 'status' => Task::STATUS_PENDING]);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET("/task/{$task->id}?format=json");
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"status":"pending"');

$I->wantTo('Show an accepted task');
$task = $I->haveTask(['assigned_name' => 'Jane Doe', 'status' => Task::STATUS_ACCEPTED]);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET("/task/{$task->id}?format=json");
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"status":"accepted"');

$I->wantTo('Show a refused task');
$task = $I->haveTask(['assigned_name' => 'Jane Doe', 'status' => Task::STATUS_REFUSED]);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET("/task/{$task->id}?format=json");
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"status":"refused"');

$I->wantTo('Show a completed task');
$task = $I->haveTask(['assigned_name' => 'Jane Doe', 'status' => Task::STATUS_COMPLETED]);
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGET("/task/{$task->id}?format=json");
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"status":"completed"');