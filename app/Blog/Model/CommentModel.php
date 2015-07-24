<?php
namespace Blog\Model;

use Doctrine\DBAL\Connection;

class CommentModel extends Model
{

    public function __construct(Connection $con)
    {
        parent::__construct($con, 'comments');
        $this->foreignKey = [
            'users' => [
                'local_c' => 'user_id',
                'foreign_c' => 'id'
            ],
            'articles' => [
                'local_c' => 'article_id',
                'foreign_c' =>'id'
            ]
        ];
    }

    public function sampleData()
    {
        for($i = 1; $i <= 10; $i++) {
            $this->insert(['id' => $i, 'article_id' => "1", 'user_id' => '1', 'content' => "Testing comment $i"]);
        }
    }

    public function getCommentsByArticleId($id)
    {
        $query = $this->con
            ->createQueryBuilder()
            ->select('c.*, u.username')
            ->from($this->table, 'c')
            ->innerJoin('c', 'users', 'u', 'c.user_id = u.id')
            ->where('c.article_id = ?')
        ;
        $result = $this->con->executeQuery($query, array($id))->fetchAll();
        return $result;
    }

    public function getCommentsByUserId($userId)
    {
        return $this->getByColumns(['user_id' => $userId]);
    }

}