<?php
namespace Blog\Service;

use Blog\Model\UserModel;
class UserService
{
    private $userModel = null;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    public function getAll()
    {
        return $this->userModel->all();
    }

    public function getById($id)
    {
        return $this->userModel->getById($id);
    }

    public function deleteById($id)
    {
        return $this->userModel->deleteById($id);
    }

    public function insert($data)
    {
        return $this->userModel->insert($data);
    }

    public function updateById($data, $id)
    {
        return $this->userModel->updateById($data, $id);
    }

    public function getByColumns($val)
    {
        return $this->userModel->getByColumns($val);
    }
}