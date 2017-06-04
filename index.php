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

$app->register(new App\Providers\ImageServiceProvider);

$app->get('/', function (Request $request, Silex\Application $app){

    $image = $app['db']->fetchAssoc("SELECT * FROM images WHERE id=2");

    $placeholder = $app['image']
        ->make(__DIR__ . '/images/docker.png')
        ->fit(800,600)
        ->greyscale()
        ->response('png');

    return new Response($placeholder,200,[
        'Content-Type' => 'image/png'
    ]);

});

$app->run();