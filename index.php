<?php

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\HttpFoundation\{Response, Request};

use Illuminate\Validation;
use Illuminate\Filesystem;
use Illuminate\Translation;

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

// https://stackoverflow.com/questions/28573889/illuminate-validator-in-stand-alone-non-laravel-application

    $filesystem = new Filesystem\Filesystem();
    $fileloader = new Translation\FileLoader($filesystem,'');
    $translator = new Translation\Translator($fileloader,'en_US');
    $factory = new Validation\Factory($translator);




    $messages = [
        'required' => 'The :attribute field is required.',
    ];

    $dataToValidate = ['title' => 'Some title'];
    $rules = [
        'title' => 'required',
        'body' => 'required'
    ];

    $validator = $factory->make($dataToValidate, $rules, $messages);

    if($validator->fails()){
        $errors = $validator->errors();
        foreach($errors->all() as $message){
//            var_dump($message);
        }
    }



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