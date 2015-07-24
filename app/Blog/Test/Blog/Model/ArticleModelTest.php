<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/19/2015
 * Time: 11:00 AM
 */

namespace Test\Blog\Model;
use Blog\Model\ArticleModel;
use Blog\Model\CommentModel;
use Blog\Model\UserModel;

class ArticleModelTest extends \PHPUnit_Framework_TestCase
{
    private $con;
    private $model;
    private $comment;
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
        $this->model = new ArticleModel($this->con);

        $user = new UserModel($this->con);
        $this->comment = new CommentModel($this->con);
        $user->deleteAllData();
        $user->sampleData();

    }

    protected  function setUp()
    {
        $this->model->deleteAllData();
        $this->model->sampleData();
        $this->comment->sampleData();
    }

    public function testGetArticleById()
    {
        $article = $this->model->getArticleById(1);
        $this->assertEquals(
            $article['id'],
            1,
            'Does Not return id 1'
        );
    }

    public function testGetAllArticle()
    {
        $articles = $this->model->getAllArticle();
        $this->assertEquals(
            count($articles),
            10,
            'This does NOT return 10 articles'
        );
    }

    public function testGetComments()
    {
        $comments  = $this->model->getComments('1');
        $this->assertEquals(
            count($comments),
            10,
            'This does NOT return 10 comments'
        );
    }

    public function testGetAllWithNumbersColumns()
    {
        $articles_num = count($this->model->all('content, author, id'));
        $this->assertEquals(
            $articles_num,
            10,
            'testGetAllWithNumbersColumns: This does NOT return 10 articles'
        );
    }

    public function testGetByIdExistedInDB()
    {
        $article = $this->model->getById(1);
        $this->assertEquals(
            $article['id'],
            1,
            'testGetByIdExistedInDB: This does NOT return article id = 1'
        );
    }

    public function testGetByIdNotInDB()
    {
        $article = $this->model->getById(100);
        $this->assertEquals(
            count($article),
            0,
            'testGetByIdNotInDB: Error in return data'
        );
    }

    public function testDeleteById()
    {
        $article = $this->model->deleteById(1);
        $this->assertEquals(
            $article,
            1,
            'testDeleteById: No column affected'
        );
    }

    public function testInsert()
    {
        $data = ['name'=> 'Kingdz', 'author' => '5', 'content'=> 'abc'];
        $article = $this->model->insert($data);
        $this->assertEquals(
            $article,
            1,
            'testInsert: No row was added!!!'
        );
    }

    public function testUpdateById()
    {
        $data = ['name'=> 'Kingdz1231231', 'author' => '5', 'content'=> 'abc'];
        $id = 1;
        $article = $this->model->updateById($data, $id);
        $this->assertEquals(
            $article,
            1,
            'testUpdateById: None of rows was updated'
        );
    }

    public function testGetByColumns()
    {
        $data = array('author' => '1', 'id' => 1);
        $article = $this->model->getByColumns($data);
        $this->assertEquals(
            count($article),
            1,
            'testGetByColumns: Error in return data'
        );
    }

}