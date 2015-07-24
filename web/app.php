<?php
require __DIR__.'/../vendor/autoload.php';
$app = new Silex\Application();
require __DIR__ . '/../app/Blog/app.php';
require __DIR__ . '/../app/config/routes.php';
return $app;



