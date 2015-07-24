<?php
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'dbname' => 'silex_blog',
        'user' => 'root',
        'password' => '',
        'host' => 'localhost',
        'driver' => 'pdo_mysql',
    ),
));

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => dirname(__DIR__)  . '\views',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());



$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));

$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Blog\Service\Provider\ArticleServiceProvider());
$app->register(new Blog\Service\Provider\UserServiceProvider());
$app->register(new Blog\Service\Provider\CommentServiceProvider());

$app->register(new \Blog\EventListener\UserListenerProvider());