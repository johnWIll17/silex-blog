<?php

namespace Blog\Controller;

use Silex\Application;

class BaseController
{
    protected $app;

    protected $currentUser;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->currentUser = $app['session']->get('user')['username'];
    }
}