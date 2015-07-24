<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/21/2015
 * Time: 11:07 AM
 */

namespace Test\Blog\Controller;

use Blog\Model\Model;
use Blog\Model\UserModel;

class UserControllerTest extends BaseControllerTest
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel($this->con);
    }

    public function setUp()
    {
        parent::setUp();

        $user57 = $this->model->getById('57');
        if(count($user57)=== 0) {
            $this->model->insert(
                [
                    'id' => 57,
                    'username' => 'kingdz@gmail.com',
                    'password' => '123456',
                    'role' => 'ROLE_ADMIN'
                ]
            );
        }
    }

    public function testLoginPageIsSuccess()
    {
        $crawler = $this->client->request('GET','user/login');
        $this->assertTrue(
            $this->client->getResponse()->isOk(),
            'LOGIN: Request to login page is NOT SUCCESS'
        );
        $this->assertEquals(
            1,
            $crawler->filter('body.login-page')->count(),
            'LOGIN: This is NOT contain class login-page'
        );
    }

    public function testLoginFormView()
    {
        $crawler = $this->client->request('GET','user/login');
        $this->assertEquals(
            3,
            $crawler->filter('input')->count(),
            'LOGIN: NOT ENOUGH fields'
        );
    }

    public function testLoginWithInvalidAccount()
    {
        $crawler = $this->client->request('GET','user/login');
        $form = $crawler->selectButton('login_login')->form();
        $form['login[username]'] = 'admin@admin.com';
        $form['login[password]'] = '1234561';
        $crawler = $this->client->submit($form);
        $this->assertContains(
            'Invalid username and password!',
            $this->client->getResponse()->getContent(),
            'Wrong error message when login fail'
        );
    }

    public function testLoginWithValidAccount()
    {
        // Redirect to homepage
        $crawler = $this->client->request('GET','user/login');
        $form = $crawler->selectButton('login_login')->form();
        $form['login[username]'] = 'kingdz@gmail.com';
        $form['login[password]'] = '123456';
        $crawler = $this->client->submit($form);
        $this->assertEquals(1,
            $crawler->filter('body.homepage')->count(),
            'LOGIN: This is NOT redirected to Homepage'
        );
    }

    public function testRedirectToLoginIfNotLoggedIn()
    {
        $urls = [
            '/blog',
            '/blog/create',
        ];

        foreach($urls as $url){
            $crawler = $this->client->request('GET', "$url");
            $this->assertEquals(
                1,
                $crawler->filter('body.login-page')->count(),
                'This page is not redirect to login page'
            );
        }
    }

    public function testRegisterPageIsSuccess()
    {
        $crawler = $this->client->request('GET','user/register');
        $this->assertTrue(
            $this->client->getResponse()->isOk(),
            'REGISTER: Request to register page is NOT SUCCESS'
        );
        $this->assertEquals(
            1,
            $crawler->filter('body.register-page')->count(),
            'REGISTER: This is NOT contain class register-page'
        );
    }

    public function testRegisterFormView()
    {
        $crawler = $this->client->request('GET','user/register');
        $this->assertEquals(
            3,
            $crawler->filter('input')->count(),
            'REGISTER: NOT ENOUGH INPUT fields'
        );
        $this->assertEquals(
            1,
            $crawler->filter('select')->count(),
            'REGISTER: NOT ENOUGH INPUT fields'
        );
    }

    public function testRegisterWithInvaildFields()
    {
        $crawler = $this->client->request('GET','user/register');
        $form = $crawler->selectButton('register_register')->form();
        $form['register[username]'] = 'Kingdz'; // Not is Email
        $form['register[password]'] = '123456';
        $form['register[role]'] = 'ROLE_ADMIN';
        $this->client->submit($form);
        $this->assertContains(
            'This value is not a valid email address',
            $this->client->getResponse()->getContent(),
            'REGISTER: Error in display error messages'
        );
    }

    public function testRegisterWithVaildFieldsButAvailableInDB()
    {
        $crawler = $this->client->request('GET','user/register');
        $form = $crawler->selectButton('register_register')->form();
        $form['register[username]'] = 'kingdz@gmail.com'; // Not is Email
        $form['register[password]'] = '123456';
        $form['register[role]'] = 'ROLE_ADMIN';
        $this->client->submit($form);
        $this->assertContains(
            'User existed! Please try another usename',
            $this->client->getResponse()->getContent(),
            'REGISTER: Error in display error messages'
        );
    }

    public function testRegisterWithVaildFieldsButNotAvailableInDB()
    {
        $crawler = $this->client->request('GET','user/register');
        $form = $crawler->selectButton('register_register')->form();
        $form['register[username]'] = 'kingdz1@gmail.com'; // Not is Email
        $form['register[password]'] = '123456';
        $form['register[role]'] = 'ROLE_ADMIN';
        $crawler = $this->client->submit($form);
        $this->assertEquals(
            1,
            $crawler->filter('body.login-page')->count(),
            'REGISTER -> LOGIN: This is NOT contain class login-page'
        );
        $this->model->deleteByColumns(['username' => 'kingdz1@gmail.com']);
    }
}