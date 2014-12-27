<?php
use Vreasy\Models\Task;

$I = new TestGuy($scenario);

$I->wantTo('Show a task assigned to Jane');
$task = $I->haveTask(['assigned_name' => 'Jane Doe', 'status' => Task::STATUS_PENDING]);
$I->haveHttpHeader('Content-Type','application/json');
$I->sendGET("/task/{$task->id}?format=json");
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContains('"assigned_name":"Jane Doe"');
$I->seeResponseContains('"status":"pending"');