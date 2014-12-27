<?php
$I = new AcceptanceGuy($scenario);
$I->wantTo('go to detailed view of a task');
$I->amOnPage('/#/task/6');
$I->expect('a title with the id of the task');
$I->see('View Task 6', 'h1');
$I->expect('a title with the number of messages of the task');
$I->see('Task 6 Messages', 'h4');
$I->expect('copyright information inside the footer');
$I->see('© Vreasy - Task confirmation ™ 2014 version 0.1 development', 'footer');
