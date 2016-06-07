<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

use Gregwar\Captcha\CaptchaBuilder;
use Ramsey\Uuid\Uuid;

$app = new Silex\Application();
$builder = new CaptchaBuilder;
$uuid = Uuid::uuid4();

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'url' => getenv("DATABASE_URL"),
    ),
));

// definitions
$app->get('/', function () {
    return 'Hello!';
});

$app->get('/captcha/', function () use ($app,$builder,$uuid) {
    $builder->build();
    $actualuuid = $uuid->toString();
    $hash = password_hash($builder->getPhrase(), PASSWORD_DEFAULT);
    $app['db']->executeUpdate('INSERT INTO captchas (uuid,hashed) VALUES (?,?);', array($actualuuid, password_hash($builder->getPhrase(), PASSWORD_DEFAULT)));
    return $app->json(array('uuid'  => $actualuuid, 'image' => $builder->inline()), 201);
});

$app->post('/captcha/{uuid}/{text}', function ($uuid,$text) use ($app) {
    $hashed = $app['db']->fetchAssoc('SELECT hashed FROM captchas WHERE uuid = ?;', array($uuid));
    return $app->json(array('match' => password_verify($text, $hashed['hashed'])));
});

//$app->get('/version/', function () use ($app) {
//    return $app->json(array('hash' => exec('git log --pretty="%H" -n1 HEAD')));
//});

//$app->get('/blog/{id}', function ($id) use ($app) {
//    $sql = "SELECT * FROM posts WHERE id = ?";
//    $post = $app['db']->fetchAssoc($sql, array((int) $id));
//
//    return  "<h1>{$post['title']}</h1>".
//            "<p>{$post['body']}</p>";
//});

$app['debug'] = true;
$app->run();
