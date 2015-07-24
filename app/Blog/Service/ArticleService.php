<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/14/2015
 * Time: 2:08 PM
 */

namespace Blog\Service;


use Blog\Model\ArticleModel;

class ArticleService
{
    private $articleModel = null;

    public function __construct(ArticleModel $articleModel)
    {
        $this->articleModel = $articleModel;
    }

    public function getAll()
    {
        return $this->articleModel->all();
    }

    public function getById($id)
    {
        return $this->articleModel->getById($id);
    }

    public function deleteById($id)
    {
        return $this->articleModel->deleteById($id);
    }

    public function insert($data)
    {
        return $this->articleModel->insert($data);
    }

    public function updateById($data, $id)
    {
        return $this->articleModel->updateById($data, $id);
    }

    public function getAllArticle()
    {
        return $this->articleModel->getAllArticle();
    }

    public function getArticleById($id)
    {
        return $this->articleModel->getArticleById($id);
    }
    public function getArticleFromIndex($index, $step)
    {
        return $this->articleModel->getArticleFromIndex($index, $step);
    }
    public function getComments($id)
    {
        return $this->articleModel->getComments($id);
    }
}