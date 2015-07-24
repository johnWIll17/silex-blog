<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/17/2015
 * Time: 2:31 PM
 */

namespace Blog\EventListener;
use Silex\Application;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
class UserListener implements EventSubscriberInterface
{
    public function onKernetView(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($request->get('_route') != 'login') {

            $user = $request->getSession()->get('user');
            if (!isset($user)) {
//                return new RedirectResponse('user/login', 302);
            }
        }
    }
    public static function getSubscribedEvents()
    {
        return array(KernelEvents::REQUEST => array('onKernetView', 8));
    }
}