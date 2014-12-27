<?php
$I = new AcceptanceGuy($scenario);
$I->wantTo('go to homepage');
$I->amOnPage('/');
$I->expect('a title with the number of available tasks');
$I->see('Listing 6 Tasks', 'h1');
$I->expect('a table of available tasks');
$I->seeElement('.tasks', 'table');
$I->expect('pending, accepted, refused and completed tasks');
$I->see('pending');
$I->see('accepted', '.info');
$I->see('refused', '.danger');
$I->see('completed', '.success');
$I->expect('copyright information inside the footer');
$I->see('© Vreasy - Task confirmation ™ 2014 version 0.1 development', 'footer');