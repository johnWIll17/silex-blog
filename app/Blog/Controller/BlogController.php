<?php
namespace Blog\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Blog\Form\Article;
use Blog\Form\Comment;
class BlogController extends BaseController
{
    private $current_page;
    private $max_page;
    public function index(Request $request)
    {
        $articles = $this->app['service.article']->getAllArticle();
        $step = 3;
        $index =  $request->get('pageIndex');
        $this->getMaxPage($articles, $step);
        if(isset($index)) {
            if($index <= $this->max_page && $index > 0) {
                $index = ($request->get('pageIndex')-1)*$step;
            } else {
                $this->app['session']->getFlashBag()->add('message', array(
                    'type' => 'danger',
                    'content' => 'You should not change pageIndex to ' . $request->get('pageIndex')
                ));
                $this->app->redirect($this->app['url_generator']->generate('blog'));
            }
        } else {
            $index = 0;
        }

        $articlesCurrentPage = array_slice($articles, $index, $step);
        $paginationPanel = $this->paginationPanel($articles, $step);
        return $this->app['twig']->render('Blog/index.html.twig',
            array(
                'username' => $this->currentUser,
                'articlesCurrentPage' => $articlesCurrentPage,
                'pages' => $paginationPanel
            ));
    }

    public function create(Request $request)
    {
        $error = '';
        $form = $this->app['form.factory']
            ->createBuilder(new Article\CreateType())
            ->getForm();

        if($request->getMethod() === 'POST') {

            $form->handleRequest($request);
            $userid = $this->app['session']->get('user')['id'];

            if ($form->isValid()) {
                $data = $form->getData() + ['author' => $userid];
                $count = $this->app['service.article']->insert($data);
                if ($count) {
                    $this->app['session']->getFlashBag()->add('message', array(
                        'type' => 'success',
                        'content' => 'You\'ve created an article successfully!'
                    ));
                    return $this->app->redirect($this->app['url_generator']->generate('blog'));
                } else {
                    $this->app['session']->getFlashBag()->add('message', array(
                        'type' => 'success',
                        'content' => 'Can not add this article, please try again!'
                    ));
                }

            }
        }

        return $this->app['twig']->render('Blog/createblog.html.twig',
            array(
                'form' => $form->createView(),
                'username' => $this->currentUser
            )
        );
    }

    public function edit($id, Request $request)
    {
        $username = $this->app['session']->get('user');
        $article = $this->app['service.article']->getById($id);
        if(!empty($article)) {
            $form1 = $this->app['form.factory']->create(new Article\EditType(), $article);
            $form2 = $this->app['form.factory']->create(new Article\DeleteType(), $article);
        } else {
            return new Response('Not found', 404);
        }
        return $this->app['twig']->render('Blog/editblog.html.twig', array(
            'username' => $this->currentUser,
            'form1' => $form1->createView(),
            'form2' => $form2->createView(),
        ));
    }

    public function update($id, Request $request)
    {
        $form = $this->getEditForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $count = $this->app['service.article']->updateById($data, $id);

            if($count) {
                $this->app['session']->getFlashBag()->add('message',
                    array(
                        'type' => 'success',
                        'content' => 'Updated Successfully!'
                    )
                );
                return $this->app->redirect($this->app['url_generator']->generate('blog'));
            }
            else{
                $this->app['session']->getFlashBag()->add('message',
                    array(
                        'type' => 'danger',
                        'content' => 'You should make changes before update!!!'
                    )
                );
                return  $this->app->redirect($this->app['url_generator']->generate('editid', array('id' => $id)));
            }
        } else {
            $this->app['session']->getFlashBag()->add('message',
                array(
                    'type' => 'danger',
                    'content' => 'Your input is not valid!!!'
                )
            );
            return  $this->app->redirect($this->app['url_generator']->generate('editid', array('id' => $id)));
        }

    }

    public function delete($id, Request $request)
    {
        $this->app['service.article']->deleteById($id);
        $this->app['session']->getFlashBag()->add('message', array(
            'type' => 'success',
            'content' => 'You\'ve deleted an article successfully'
        ));
        return $this->app->redirect($this->app['url_generator']->generate('blog'));
    }

    public function show($id, Request $request)
    {
        $article = $this->app['service.article']->getArticleById($id);
        if(!empty($article)) {
            $comments = $this->app['service.article']->getComments($id);
            $form_comment = $this->app['form.factory']->create(new Comment\CreateType());
            if($request->getMethod() === 'POST') {
                $form_comment->handleRequest($request);
                if($form_comment->isValid()) {
                    $data = $form_comment->getData();
                    $userid = $this->app['session']->get('user')['id'];
                    $data = ['user_id' => $userid] + ['article_id' => $id] + $data;
                    $count = $this->app['service.comment']->insert($data);
                    if($count) {
                        return $this->app->redirect($this->app['url_generator']->generate('blogid', array('id' => $id)));
                    }
                }
            }

            return $this->app['twig']->render('Blog/showblog.html.twig',
                array(
                    'username' => $this->currentUser,
                    'article' => $article,
                    'form' => $form_comment->createView(),
                    'comments' => $comments
                )
            );
        } else {
            return new Response('Not found', 404);
        }
    }

    private function getEditForm()
    {
        return $this->app['form.factory']->create(new Article\EditType());
    }

    private function getMaxPage($data = array(), $step)
    {
       $this->max_page = ceil(count($data) / $step);
    }

    private function paginationPanel()
    {
        $list_pages = range(1, $this->max_page);
        return $list_pages;
    }

}