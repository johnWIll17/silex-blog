<?php

namespace Blog\Controller;


use Blog\Model;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints as Assert;
use Blog\Form\User;

class UserController extends BaseController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function login(Request $request)
    {
        $form = $this->app['form.factory']->create(new User\LoginType());
        if($request->getMethod() === 'POST'){
            $form->handleRequest($request);
            $data = $form->getData();

            if ($form->isValid()){
                $user = $this->app['service.user']->getByColumns($data);
                if(empty($user)) {
                    $this->app['session']->getFlashBag()->add('message', array(
                        'type' => 'danger',
                        'content' => 'Invalid username and password!'
                    ));
                } else {
                    $this->app['session']->set('user', array(
                        'username' => $data['username'],
                        'password' => $data['password'],
                        'id' => $user[0]['id']));
                    return $this->app->redirect($this->app['url_generator']->generate('blog'));
                }
            }
        }
        return $this->app['twig']->render('User/login.html.twig',
            array('form' => $form->createView()
            )
        );
    }

    public function register(Request $request)
    {
        $form = $this->app['form.factory']->create(new User\RegisterType());

        if($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if($form->isValid()) {
                $data =  $form->getData();
                $username = (array_slice($data,0,1));
                $user = $this->app['service.user']->getByColumns($username);
                if(empty($user)) {
                    $count = $this->app['service.user']->insert($data);
                    if($count)
                        return $this->app->redirect($this->app['url_generator']->generate('login'));
                    else
                        return $error = 'Error in registing. Please try again';
                } else {
                    $this->app['session']->getFlashBag()->add('message',array(
                        'type' => 'danger',
                        'content' => 'User existed! Please try another usename'
                    ));
                }

            }
        }

        return $this->app['twig']->render('User/register.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    public function logout()
    {
        $this->app['session']->remove('user');
        return $this->app->redirect('login');
    }

    public function test()
    {
        $a = new Model\UserModel($this->app['db']);
        echo $a->deleteAllData();
    }
}

