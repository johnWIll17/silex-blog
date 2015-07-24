<?php
use Blog\Controller\BlogController;
use Blog\Controller\UserController;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Silex\Application;
$app['blog.Controller'] = $app->share(function() use ($app) {
    return new BlogController($app);
});
$app['user.Controller'] = $app->share(function() use ($app) {
    return new UserController($app);
});

$app->before(function(Request $request, Application $app) {
    $route_name = $request->get('_route');
    if ($route_name != 'login' ) {
        if($route_name != 'register'){
            $user = $app['session']->get('user');
            if (!isset($user)) {
                return $app->redirect($app['url_generator']->generate('login'));
            }
        }
    } else {
        $user = $app['session']->get('user');
        if (isset($user)) {
            return $app->redirect($app['url_generator']->generate('blog'));
        }
    }
});

$app->match('user/register', "user.Controller:register")
    ->method('get|post')
    ->bind('register');

$app->match('user/login', "user.Controller:login")
    ->method('get|post')
    ->bind('login');

$app->get('user/logout', "user.Controller:logout")
    ->bind('logout');

$app->match('blog/{id}', "blog.Controller:show")
    ->assert('id', '\d+')
    ->method('get|post')
    ->bind('blogid');

$app->match('blog/{id}/edit', "blog.Controller:edit")
    ->assert('id', '\d+')
    ->method('get|post')
    ->bind('editid');

$app->post('blog/{id}/delete', "blog.Controller:delete")
    ->assert('id', '\d+');
$app->post('blog/{id}/update', "blog.Controller:update")
    ->assert('id', '\d+');


$app->match('blog/create', "blog.Controller:create")
    ->method('get|post')
    ->bind('createarticle');

$app->get('blog/', "blog.Controller:index")
    ->bind('blog');

$app->get('test', 'user.Controller:Test');
$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('login.html', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
});
$app->get('admin/show', function(){
    return new \Symfony\Component\HttpFoundation\Response('ascascas');
});
