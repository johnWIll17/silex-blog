<?php
/**
 * Created by PhpStorm.
 * User: Kingdz
 * Date: 7/19/2015
 * Time: 6:39 AM
 */

namespace Blog\Service;


use Blog\Model\CommentModel;

class CommentService
{
    private $commentModel = null;

    public function __construct(CommentModel $commentModel)
    {
        $this->commentModel = $commentModel;
    }

    public function getAll()
    {
        return $this->commentModel->all();
    }

    public function getById($id)
    {
        return $this->commentModel->getById($id);
    }

    public function deleteById($id)
    {
        return $this->commentModel->deleteById($id);
    }

    public function insert($data)
    {
        return $this->commentModel->insert($data);
    }

    public function updateById($data, $id)
    {
        return $this->commentModel->updateById($data, $id);
    }
}