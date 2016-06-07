<?php
// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

use Gregwar\Captcha\CaptchaBuilder;
use Ramsey\Uuid\Uuid;

$app = new Silex\Application();
$builder = new CaptchaBuilder;
$uuid = Uuid::uuid4();

$url = parse_url(getenv("DATABASE_URL"));

$host = $url["host"];
$user = $url["user"];
$password = $url["pass"];
$dbname = substr($url["path"], 1);

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_pgsql',
        'host'     => $host,
        'dbname'   => $dbname,
        'user'     => $user,
        'password' => $password,
    ),
));

// definitions
$app->get('/', function () {
    return 'Hello!';
});

$app->get('/captcha/', function () use ($app,$builder,$uuid) {
    $builder->build();
    password_hash($builder->getPhrase(), PASSWORD_DEFAULT)
    return $app->json(array('uuid' => $uuid->toString(), 'image' => $builder->inline()));
});

$app->post('/captcha/', function () use ($app) {
    return $app->json(array('match' => rand(0,1) == 1));
});

$app->get('/version/', function () use ($app) {
    return $app->json(array('hash' => exec('git log --pretty="%H" -n1 HEAD')));
});

$app->get('/blog/{id}', function ($id) use ($app) {
    $sql = "SELECT * FROM posts WHERE id = ?";
    $post = $app['db']->fetchAssoc($sql, array((int) $id));

    return  "<h1>{$post['title']}</h1>".
            "<p>{$post['body']}</p>";
});

$app['debug'] = true;
$app->run();
