<?php

namespace Test\Blog\Controller;


use Blog\Model\ArticleModel;
use Blog\Model\CommentModel;
use Blog\Model\Model;

class BlogControllerTest extends BaseControllerTest
{
    protected $client;
    protected $comment;
    public function __construct()
    {
        parent::__construct();
        parent::setUp();
        $this->simulation_login();
        $this->model = new ArticleModel($this->con);
        $this->comment = new CommentModel($this->con);

    }

    public function setUp()
    {
        parent::setUp();

        $article57 = $this->model->getById('57');
        if(count($article57)=== 0) {
            $this->model->insert(
                [
                    'id' => 57,
                    'name' => 'Tesing from UnitTest',
                    'content' => 'This is testing content',
                    'author' => '57'
                ]
            );
        }
        $comments = $this->comment->getCommentsByArticleId('57');
        if(count($comments) === 0){
            $this->comment->insert(
                [
                    'content' => 'This is first testing comment',
                    'article_id' => 57,
                    'user_id' => 57
                ]
            );
            $this->comment->insert(
                [
                    'content' => 'This is second testing comment',
                    'article_id' => 57,
                    'user_id' => 57
                ]
            );
            $this->comment->insert(
                [
                    'content' => 'This is third testing comment',
                    'article_id' => 57,
                    'user_id' => 57
                ]
            );
        }
    }

    public function testIndexIsSuccess()
    {
        $crawler = $this->client->request('GET','/blog');
        $this->assertTrue(
            $this->client->getResponse()->isOk(),
            'INDEX: Request to homepage is NOT SUCCESS'
        );

    }

    public function testIndexGUI()
    {
        $crawler = $this->client->request('GET','/blog');

        $this->assertEquals(
            1,
            $crawler->filter('body.homepage')->count(),
            'INDEX: This does NOT contain class homepage'
        );

        $this->assertEquals(
            1,
            $crawler->filter('#page-title')->count(),
            'INDEX: This does NOT contain Page title area'
        );

        $this->assertEquals(
            1,
            $crawler->filter('.list-articles')->count(),
            'INDEX: This does NOT contain List articles area'
        );

        $this->assertEquals(
            1,
            $crawler->filter('#page-title')->count(),
            'INDEX: This does NOT contain Page title area'
        );

        $this->assertEquals(
            1,
            $crawler->filter('div.pagination')->count(),
            'INDEX: This does NOT contain Pagination area'
        );

        $this->assertEquals(
            1,
            $crawler->filter('a.create-article')->count(),
            'INDEX: This does NOT contain Pagination area'
        );
    }

    public function testIndexPaginationWithValidPageIndex()
    {
        // article per page : 3
        $crawler = $this->client->request('GET','/blog', array('pageIndex' => 1));

        $this->assertGreaterThanOrEqual(
            1,
            $crawler->filter('article')->count()
        );
        $this->assertLessThanOrEqual(
            3,
            $crawler->filter('article')->count()
        );

    }

    public function testIndexWithNegativePageIndex()
    {
        $crawler = $this->client->request('GET','/blog', array('pageIndex' => -1));

        $this->assertContains(
            'You should not change pageIndex to ',
            $this->client->getResponse()->getContent()
        );
    }

    public function testIndexWithOutSizePageIndex()
    {
        $crawler = $this->client->request('GET','/blog', array('pageIndex' => 100000000000));

        $this->assertContains(
            'You should not change pageIndex to ',
            $this->client->getResponse()->getContent()
        );
    }

    public function testShowPageIsSuccess()
    {
        $crawler = $this->client->request('GET','/blog/57');
        $this->assertTrue(
            $this->client->getResponse()->isOk(),
            'SHOWPAGE: Request to blog 57 is NOT SUCCESS'
        );

    }

    public function testShowPageContent()
    {
        $crawler = $this->client->request('GET', '/blog/57');

        $this->assertEquals(
            1,
            $crawler->filter('body.single-page')->count(),
            'SHOWPAGE: This does NOT contain class homepage'
        );

        $this->assertEquals(
            1,
            $crawler->filter('a.edit-article')->count(),
            'SHOWPAGE: This page does NOT contain edit article button'
        );
        $this->assertEquals(
            1,
            $crawler->filter('div.article-title')->count(),
            'SHOWPAGE: This page does NOT contain article title div'
        );

        $this->assertEquals(
            2,
            $crawler->filter('article > .article-author, article > .article-content')->count(),
            'SHOWPAGE: This page does NOT contain article content button'
        );
        $this->assertEquals(
            1,
            $crawler->filter('#comment')->count(),
            'SHOWPAGE: This page does NOT contain comments area button'
        );
        $this->assertEquals(
            1,
            $crawler->filter('#createcomment_submit')->count(),
            'SHOWPAGE: This page does NOT contain submit comment button'
        );
    }

    public function testCreateVaildComment()
    {
        $crawler = $this->client->request('GET', 'blog/57');
        $form = $crawler->selectButton('createcomment_submit')->form();
        $form['createcomment[content]'] = 'testing comment';
        $crawler = $this->client->submit($form);

        $this->assertContains(
            'testing comment',
            $this->client->getResponse()->getContent(),
            'COMMENT: New comment is not found!!!'
        );
    }

    public function testCreateInvaildComment()
    {
        $crawler = $this->client->request('GET', 'blog/57');
        $form = $crawler->selectButton('createcomment_submit')->form();
        $form['createcomment[content]'] = '';
        $crawler = $this->client->submit($form);

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent(),
            'COMMENT: Problem in display error'
        );
    }

    public function testEditPageIsSuccess()
    {
        $crawler = $this->client->request('GET','/blog/57/edit');
        $this->assertTrue(
            $this->client->getResponse()->isOk(),
            'EDITPAGE: Request to blog 57 is NOT SUCCESS'
        );
        $this->assertEquals(
            1,
            $crawler->filter('body.edit-single-page')->count(),
            'EDITPAGE: This does NOT contain class homepage'
        );
    }

    public function testEditPageContent()
    {
        $crawler = $this->client->request('GET','/blog/57/edit');
        $this->assertContains(
            '<p>Edit</p>',
            $this->client->getResponse()->getContent(),
            'EDITPAGE: This does NOT contain page header'
        );
        $this->assertEquals(
            3,
            $crawler->filter('input')->count(),
            'EDITPAGE: This does NOT enough input fields'
        );
        $this->assertEquals(
            1,
            $crawler->filter("#updatearticle_name")->count(),
            'EDITPAGE: This does NOT contain name field'
        );
        $this->assertEquals(
            1,
            $crawler->filter("#updatearticle_content")->count(),
            'EDITPAGE: This does NOT contain content field'
        );$this->assertEquals(
            1,
            $crawler->filter("#updatearticle_update")->count(),
            'EDITPAGE: This does NOT contain update button'
        );$this->assertEquals(
            1,
            $crawler->filter("#deletearticle_delete")->count(),
            'EDITPAGE: This does NOT contain delete button'
        );
    }

    public function testUpdateArticleWithoutMakingChanges()
    {
        $crawler = $this->client->request('GET','/blog/57/edit');
        $form = $crawler->selectButton('updatearticle_update')->form();
        $form['updatearticle[name]'] = 'Tesing from UnitTest';
        $form['updatearticle[content]'] = 'This is testing content';
        $crawler = $this->client->submit($form);
        $this->assertContains(
            'You should make changes before update!!!',
            $this->client->getResponse()->getContent(),
            'UPDATE: Flash message is not displayed!!!'
        );
    }

    public function testUpdateArticleWithInvalidInputs()
    {
        $crawler = $this->client->request('GET','/blog/57/edit');
        $form = $crawler->selectButton('updatearticle_update')->form();
        $form['updatearticle[name]'] = '';
        $form['updatearticle[content]'] = '';
        $crawler = $this->client->submit($form);
        $this->assertContains(
            'Your input is not valid!!!',
            $this->client->getResponse()->getContent(),
            'UPDATE : Flash message is not displayed!!!'
        );
    }

    public function testUpdateArticleWithValidInputs()
    {
        $crawler = $this->client->request('GET','/blog/57/edit');
        $form = $crawler->selectButton('updatearticle_update')->form();
        $form['updatearticle[name]'] = 'New Title';
        $form['updatearticle[content]'] = 'New Content';
        $crawler = $this->client->submit($form);
        $this->assertContains(
            'Updated Successfully!',
            $this->client->getResponse()->getContent(),
            'UPDATE : Flash message is not displayed!!!'
        );
    }

    public function testDeleteArticle()
    {
        $crawler = $this->client->request('POST', 'blog/57/delete');
        $this->assertContains(
            'You&#039;ve deleted an article successfully',
            $this->client->getResponse()->getContent(),
            'DELETE -> INDEX: Flash message is not displayed!!!'
        );
    }

    public function testCreateNewArticlePageIsSuccess()
    {
        $crawler = $this->client->request('GET', 'blog/create');
        $this->assertTrue($this->client->getResponse()->isOk());

    }

    public function testCreateNewArticlePageContent()
    {
        $crawler = $this->client->request('GET', 'blog/create');

        $this->assertEquals(
            1,
            $crawler->filter('body.create-article-page')->count(),
            'CREATE NEW ARTICLE: This page does NOT contain create-article-page class'
        );

        $this->assertContains(
            '<p>Create An Article</p>',
            $this->client->getResponse()->getContent(),
            'CREATE NEW ARTICLE: This page does NOT contain title-page'
        );

        $this->assertEquals(
            1,
            $crawler->filter('#createarticle_name')->count(),
            'CREATE NEW ARTICLE: This page does NOT contain article_name input'
        );

        $this->assertEquals(
            1,
            $crawler->filter('#createarticle_content')->count(),
            'CREATE NEW ARTICLE: This page does NOT contain article_content input'
        );

        $this->assertEquals(
            1,
            $crawler->filter('#createarticle_create')->count(),
            'CREATE NEW ARTICLE: This page does NOT contain submit button'
        );
    }

    public function testCreateNewArticleWithInvaildInput()
    {
        $crawler = $this->client->request('GET', 'blog/create');
        $form = $crawler->selectButton('createarticle_create')->form();
        $form['createarticle[name]'] = '';
        $form['createarticle[content]'] = 'Testing form Testcase';
        $crawler = $this->client->submit($form);

        $this->assertContains(
            'This value should not be blank.',
            $this->client->getResponse()->getContent()
        );

    }

    public function testCreateNewArticleWithVaildInput()
    {
        $crawler = $this->client->request('GET', 'blog/create');
        $form = $crawler->selectButton('createarticle_create')->form();
        $form['createarticle[name]'] = 'Testing for testcase';
        $form['createarticle[content]'] = 'Testing form Testcase';
        $crawler = $this->client->submit($form);

        $this->assertContains(
            'You&#039;ve created an article successfully!',
            $this->client->getResponse()->getContent()
        );
        $this->model->deleteByColumns(['name' => 'Testing for testcase', 'content' => 'Testing for testcase']);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->model->deleteById('57');
    }

}