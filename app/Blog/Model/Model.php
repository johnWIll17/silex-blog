<?php
namespace Blog\Model;

use Doctrine\DBAL\Connection;

class Model
{
    protected $table = NULL;
    protected $con = NULL;
    protected $foreignKeyOf = NULL;
    // $foreignKeyOf = array(['id', 'user', 'user_id']);
    protected $foreignKey = NULL;
    // $foreignKey = array(['user_id', 'user',  'id']);

    public function __construct(Connection $con, $table)
    {
        $this->con = $con;
        $this->table = $table;
    }

    public function all($column = '*')
    {
        $query = $this->con
            ->createQueryBuilder()
            ->select($column)
            ->from($this->table)
        ;
        $result = $this->con->executeQuery($query)->fetchAll();
        return $result;
    }

    public function getById($id, $column = '*')
    {
        $query = $this->con
            ->createQueryBuilder()
            ->select($column)
            ->from($this->table)
            ->where('id = ?')
        ;
        $result = $this->con->executeQuery($query, array($id))->fetchAll();
        if(count($result)){
            return $result[0];
        } else {
            return $result;
        }
    }

    public function deleteById($id)
    {
        $query = $this->con
            ->createQueryBuilder()
            ->delete($this->table)
            ->where('id = ?')
        ;
        $result = $this->con->executeUpdate($query, array($id));
        if($result)
            return $result;
        else
            return false;
    }

    public function insert($val = array())
    {
        $quote = function($value) { return '\'' . $value . '\'';};
        $val = (array_map($quote, $val));
        $query = $this->con
            ->createQueryBuilder()
            ->insert($this->table)
            ->values($val)
        ;
        $result = $this->con->executeUpdate($query);
        if($result)
            return $result;
        else
            return false;
    }

    public function updateById($val = array(), $id)
    {

        $query = $this->con->createQueryBuilder();
        foreach($val as $key=>$value){
            $query->set($key, $this->quotes($value));
        }
        $query->update($this->table)
            ->where("id = '$id'")
        ;
        $result = $this->con->executeUpdate($query);
        if($result)
            return $result;
        else
            return false;
    }

    public function getByColumns($val = array())
    {
        $query = $this->con->createQueryBuilder();
        $query
            ->select('*')
            ->from($this->table)
        ;
        foreach($val as $k => $v){
            $query->andWhere("$k = '$v'");
        }
        $result = $this->con->executeQuery($query)->fetchAll();
        return $result;
    }

    public function deleteByColumns($val = array())
    {
        $query = $this->con->createQueryBuilder();
        $query
            ->delete($this->table)
        ;
        foreach($val as $k => $v){
            $query->andWhere("$k = '$v'");
        }
        $result = $this->con->executeUpdate($query);
        return $result;
    }

    public function deleteAllData()
    {
        $query = $this->con->createQueryBuilder();
        $query->delete($this->table);
        $result = $this->con->executeQuery($query);
        return $result;
    }

    public function quotes($value)
    {
        return '\'' . $value . '\'';
    }


}