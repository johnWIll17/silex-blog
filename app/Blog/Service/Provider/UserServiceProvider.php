<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/16/2015
 * Time: 10:12 AM
 */

namespace Blog\Service\Provider;

use Blog\Model\UserModel;
use Blog\Service\UserService;
use Silex\ServiceProviderInterface;
use Silex\Application;

class UserServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['service.user'] = $app->share(function($app) {
            $userModel = new UserModel($app['db']);
            return new UserService($userModel);
        });
    }

    public function boot(Application $app)
    {

    }
}