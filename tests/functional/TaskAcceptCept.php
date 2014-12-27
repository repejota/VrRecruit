<?php

use Vreasy\Models\Task;

$I = new TestGuy($scenario);
$I->wantTo("Simulate a Twilio request to accept a pending task");
$task = $I->haveTask([
    "status" => Task::STATUS_PENDING,
    "assigned_name" => "Jane Doe",
    "created_at" => gmdate(DATE_FORMAT),
    "updated_at" => gmdate(DATE_FORMAT)
]);

$sms_body = "Yes";
$sms_phone = "+34123456789";

$I->haveHttpHeader("Content-Type", "application/json");
$I->sendPOST("/twilio/message", array("Body" => "Yes", "From" => $sms_phone));
$I->seeResponseCodeIs(200);
$I->seeInDatabase("tasks", [
    "assigned_name" => $task->assigned_name,
    "assigned_phone" => $sms_phone,
    "status" => Task::STATUS_ACCEPTED
]);