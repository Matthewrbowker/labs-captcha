<?php
// web/index.php
require_once __DIR__.'/../../captcha/vendor/autoload.php';

use Gregwar\Captcha\CaptchaBuilder;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();
$builder = new CaptchaBuilder;
$uuid = Uuid::uuid4();

if (isset(SOURCE_VERSION) != True)
    putenv("SOURCE_VERSION=".exec('git log -1 --format="%H"'));

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'url' => getenv("DATABASE_URL"),
    ),
));

// definitions

$app->before(function (Request $request, Application $app) {
    $authorizationHeader = $request->headers->get('Authorization');

    if ($authorizationHeader == null) {
        return new Response('No authorisation header sent.', 401);
    }

    // validate the token
    $userworking = $app['db']->fetchAssoc('SELECT `activated` FROM `keys` WHERE `key` = ?;', array($app->escape($authorizationHeader)));
    if ($userworking['activated'] != true) {
        return new Response('Invalid/deactivated API key.', 401);
    }
    
});

$app->get('/', function () {
    return 'Hello!';
});

$app->get('/captcha/', function () use ($app,$builder,$uuid) {
    $builder->build();
    $actualuuid = $uuid->toString();
    $hash = password_hash($builder->getPhrase(), PASSWORD_DEFAULT);
    $app['db']->executeUpdate('INSERT INTO `captchas` (`uuid`,`hashed`) VALUES (?,?);', array($actualuuid, password_hash($builder->getPhrase(), PASSWORD_DEFAULT)));
    return $app->json(array('uuid'  => $actualuuid, 'image' => $builder->inline()), 201);
});

$app->post('/captcha/{uuid}/{text}/', function ($uuid,$text) use ($app) {
    $hashed = $app['db']->fetchAssoc('SELECT `hashed` FROM `captchas` WHERE `uuid` = ?;', array($app->escape($uuid)));
    return $app->json(array('match' => password_verify($app->escape($text), $hashed['hashed'])));
});

$app->get('/version/', function () use ($app) {
    return $app->json(array('hash' => getenv("SOURCE_VERSION")));
});

$app['debug'] = getenv('DEBUG');
$app->run();
