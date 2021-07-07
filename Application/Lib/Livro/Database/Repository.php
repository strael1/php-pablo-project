<?php 
// Namespace
// namespace Source\Livro\Database;

// use Source\Livro\Database\Criteria;
// use Exception;

namespace Source\Lib\Livro\Database;
use Exception;

class Repository
{
    private $activeRecord;

    public function __construct($class)
    {
        $this->activeRecord = $class;
    }

    public function load(Criteria $criteria)
    {
        if($criteria)
        {

            $sql = "SELECT * FROM " . constant($this->activeRecord . '::TABLENAME');
            $expression = $criteria->dump();

            if($expression)
            {
                $sql .= " WHERE " . $expression;
            
                $order  = $criteria->getProperty('order');
                $limit  = $criteria->getProperty('limit');
                $offset = $criteria->getProperty('offset');

                if($order)
                {
                    $sql .= " ORDER BY " . $order;
                }

                if($limit)
                {
                    $sql .= " LIMIT " . $limit;
                }

                if($offset)
                {
                    $sql .= " OFFSET " . $offset;
                }
            }
        }

        if($conn = Transaction::get())
        {
            $result = $conn->prepare($sql);
            // Transaction::log($sql);
            print "$sql";
            $result->execute();
            $results = [];

            while($row = $result->fetchObject($this->activeRecord))
            {
                $results[] = $row;
            }

            return $results;
        }
    }

    public function delete(Criteria $criteria)
    {
        if($criteria)
        {
            $sql = "DELETE FROM " . constant($this->activeRecord . '::TABLENAME');
            $expression = $criteria->dump();

            if($expression)
            {
                $sql .= " WHERE " . $expression;
            }
        }

        if($conn = Transaction::get())
        {
            $result = $conn->prepare($sql);
            $result->execute();
        }else 
        {
            throw new Exception('Não há transação ativa.');
        }
    }

    public function count(Criteria $criteria)
    {
        if($criteria)
        {
            $sql = "SELECT COUNT(*) FROM " . constant($this->activeRecord . '::TABLENAME'); 
            $expression = $criteria->dump();

            if($expression)
            {
                $sql .= " WHERE " . $expression;
            }
        }

        if($conn = Transaction::get())
        {
            $result = $conn->prepare($sql);
            $result->execute();

            if($result)
            {
                $row = $result->fetch();
                return $row[0];
            }
        }else 
        {
            throw new Exception('Não há transação ativa.');
        }
    }

}