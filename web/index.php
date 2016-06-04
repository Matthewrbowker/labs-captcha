<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

// definitions
$app->get('/', function () {
    return 'Hello!';
});

$app->post('/captcha-dev/generate/', function () {
    return 'Hello, this is the (future) generator!';
});

$app->get('/captcha-dev/verify/', function () {
    return 'Hello, this is the (future) verifier!';
});

$app->get('/captcha-dev/version/', function () {
    return ;
});

$app['debug'] = true;
$app->run();
