<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/19/2015
 * Time: 10:05 PM
 */

namespace Blog\EventListener;


use Silex\Application;
use Silex\ServiceProviderInterface;

class UserListenerProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
    }

    public function boot(Application $app)
    {
        $app['dispatcher']->addSubscriber(
            new UserListener()
        );
    }
}