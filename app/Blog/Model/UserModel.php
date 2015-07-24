<?php
namespace Blog\Model;

use Doctrine\DBAL\Connection;

class UserModel extends Model
{

    public function __construct(Connection $con)
    {
        parent::__construct($con, 'users');
        $this->foreignKeyOf = [
            'comments' => [
                'local_c' => 'id',
                'foreign_c' => 'user_id'
            ],
            'articles' => [
                'local_c' => 'id',
                'foreign_c' => 'author'
            ],
        ];
    }

    public function sampleData()
    {
        for($i = 1; $i <= 10; $i++) {
            $this->insert(['id' => $i, 'username' => "test$i@gmail.com", 'password' => '123456', 'role' => 'ROLE_ADMIN']);
        }
    }

}