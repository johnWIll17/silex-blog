<?php
namespace Blog\Model;

use Doctrine\DBAL\Connection;

class ArticleModel extends Model
{

    public function __construct(Connection $con)
    {
        parent::__construct($con, 'articles');
        $this->foreignKey = [
            'users' => [
                'local_c' => 'user_id',
                'foreign_c' =>'id'
            ]

        ];
        $this->foreignKeyOf = [
            'comments' => [
                'local_c' => 'id',
                'foreign_c' =>'article_id'
            ]
        ];
    }
    public function sampleData()
    {
        for($i = 1; $i <= 10; $i++) {
            $this->insert(['id' => $i, 'name' => "Test title $i", 'author' => '1', 'content' => "Testing content $i"]);
        }
    }
    public function getArticleById($id)
    {
        $query = $this->con
            ->createQueryBuilder()
            ->select('a.*, u.username')
            ->from($this->table, 'a')
            ->innerJoin('a', 'users', 'u', 'a.author = u.id')
            ->where('a.id = ?')
        ;
        $result = $this->con->executeQuery($query, array($id))->fetchAll();
        if(count($result))
            return $result[0];
        else
            return $result;
    }
    public function getAllArticle()
    {
        $query = $this->con
            ->createQueryBuilder()
            ->select('a.*, u.username')
            ->from($this->table, 'a')
            ->innerJoin('a', 'users', 'u', 'a.author = u.id')
        ;
        $result = $this->con->executeQuery($query)->fetchAll();
        return $result;
    }
    public function getArticleFromIndex($index, $length)
    {
        $query = $this->con
            ->createQueryBuilder()
            ->select('a.*, u.username')
            ->from($this->table, 'a')
            ->innerJoin('a', 'users', 'u', 'a.author = u.id')
            ->setFirstResult($index)
            ->setMaxResults($length)
        ;
        $result = $this->con->executeQuery($query)->fetchAll();
        return $result;
    }
    public function getComments($articleId)
    {
        $comments = new CommentModel($this->con);
        return $comments->getCommentsByArticleId($articleId);
    }

}