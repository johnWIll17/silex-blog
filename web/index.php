<?php

require __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
require ('/../app/Blog/app.php');
require ('/../app/config/routes.php');
$app['debug'] = true;

$app->run();
