<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/19/2015
 * Time: 6:40 AM
 */

namespace Blog\Service\Provider;


use Blog\Model\CommentModel;
use Blog\Service\CommentService;
use Silex\ServiceProviderInterface;
use Silex\Application;

class CommentServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['service.comment'] = $app->share(function($app) {
            $userModel = new CommentModel($app['db']);
            return new CommentService($userModel);
        });
    }

    public function boot(Application $app)
    {

    }
}