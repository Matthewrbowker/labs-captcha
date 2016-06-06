<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

use Gregwar\Captcha\CaptchaBuilder;

$app = new Silex\Application();
$builder = new CaptchaBuilder;

// definitions
$app->get('/', function () {
    return 'Hello!';
});

$app->post('/captcha/', function () use ($app,$builder) {
    $builder->build();
    return $app->json(array('image' => $builder->output()));
});

$app->post('/captcha/', function () use ($app) {
    return $app->json(array('match' => rand(0,1) == 1));
});

$app->get('/version/', function () use ($app) {
    return $app->json(array('hash' => exec('git log --pretty="%H" -n1 HEAD')));
});

$app['debug'] = true;
$app->run();
