<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('ensure that authentication works');
$I->amOnPage('/');
$I->see('Hello!');
$I->dontSee('No authorisation header sent.');
$I->dontSee('Invalid/deactivated API key.');
