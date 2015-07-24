<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/19/2015
 * Time: 11:02 AM
 */

namespace Test\Blog\Model;
use Blog\Model\ArticleModel;
use Blog\Model\CommentModel;
use Blog\Model\UserModel;

class CommentModelTest extends \PHPUnit_Framework_TestCase
{
    protected $con ;
    protected $model;
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
        $this->model = new CommentModel($this->con);

        $user = new UserModel($this->con);
        $article = new ArticleModel($this->con);
        $user->deleteAllData();
        $user->sampleData();
        $article->sampleData();
        $this->model = new CommentModel($this->con);


    }

    protected  function setUp()
    {
        $this->model->deleteAllData();
        $this->model->sampleData();
    }


    public function testGetCommentsByArticleId()
    {
        $comments = $this->model->getCommentsByArticleId(1);
        $this->assertEquals(
            count($comments),
            10,
            'testGetCommentsByArticleId: It does return all comments'
        );
    }

}