<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\{Response, Request};

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(),[
    'db.options' => [
        'driver' => 'pdo_sqlite',
        'path'     => __DIR__.'/app.db',
    ]
]);

$app->get('/', function (Request $request, Silex\Application $app){

    $image = $app['db']->fetchAssoc("SELECT * FROM images WHERE id=2");

    return $app->json($image);

    return new Response($image,200);
});

$app->run();