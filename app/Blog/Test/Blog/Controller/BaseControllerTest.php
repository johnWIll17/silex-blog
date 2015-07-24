<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/22/2015
 * Time: 3:17 PM
 */

namespace Test\Blog\Controller;


use Silex\WebTestCase;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

abstract class BaseControllerTest extends WebTestCase
{
    protected $client;
    protected $model;
    protected $con;
    public function __construct()
    {
        $config = new \Doctrine\DBAL\Configuration();
        $connectionParams = array(
            'dbname' => 'silex_blog',
            'user' => 'root',
            'password' => '',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        );
        $this->con = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);
    }
    public function setUp()
    {
        parent::setUp();

        if ($this->client === null) {
            $this->client = $this->createClient();
        }
        $this->client->followRedirects();
    }
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../../web/app.php';
        $app['debug'] = true;
        unset($app['exception_handler']);
        $app['session.storage'] = new MockArraySessionStorage();
        $app['session.test'] = true;
        return $app;
    }
    public function simulation_login()
    {
        $this->app['session']->set('user', array(
            'id' => 57,
            'username' => 'kingdz@gmail.com',
            'password' => '123456',
        ));
    }


}