<?php

namespace Blog\Service\Provider;

use Blog\Model\ArticleModel;
use Blog\Service\ArticleService;
use Silex\Application;
use Silex\ServiceProviderInterface;

class ArticleServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['service.article'] = $app->share(function($app) {
            $articleModel = new ArticleModel($app['db']);
           return new ArticleService($articleModel);
        });
    }

    public function boot(Application $app)
    {

    }
}