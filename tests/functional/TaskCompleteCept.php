<?php

use Vreasy\Models\Task;

$I = new TestGuy($scenario);
$I->wantTo("Simulate a Twilio request to accept a pending task");
$task = $I->haveTask([
	"status" => Task::STATUS_ACCEPTED,
	"assigned_name" => "Jane Doe",
	"assigned_phone" => "+34123456789",
	"created_at" => gmdate(DATE_FORMAT),
	"updated_at" => gmdate(DATE_FORMAT)
	]);

$I->haveHttpHeader("Content-Type","application/json");
$I->sendPOST("/twilio/message", array("Body" => "Yes", "From" => "+34123456789"));
$I->seeResponseCodeIs(200);
$I->seeInDatabase("tasks", [
	"id" => $task->id,
	"assigned_name" => $task->assigned_name,
	"assigned_phone" => $task->assigned_phone,
	"status" => Task::STATUS_COMPLETED
	]);