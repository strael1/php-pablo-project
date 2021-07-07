<?php 

// namespace Source\Livro\Database;
// use Source\Livro\Database\Transaction;
// use PDO;
// use Exception;

namespace Source\Lib\Livro\Database;
use Source\Lib\Livro\Database\Transaction;
use PDO;
USE Exception;

class Record 
{
    private $data;

    public function __construct($id = null)
    {
        if($id)
        {
            $object = $this->load($id);

            if($object)
            {
                $this->fromArray($object->toArray());
            }
        }
    }

    public function __set($prop,$value)
    {
        if($value == null)
        {
            unset($this->data[$prop]);
        }else 
        {
            $this->data[$prop] = $value;
        }
    }

    public function __get($prop)
    {
        if(isset($this->data[$prop]))
        {
            return $this->data[$prop];
        }
    }

    public function __isset($prop)
    {
        return isset($this->data[$prop]);
    }

    public function __clone()
    {
        unset($this->data['id']);
    }


    public function fromArray($datas)
    {
        $this->data = $datas;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function getEntity()
    {
        $class = get_class($this);
        return constant("{$class}::TABLENAME");
    }

    public function all($filter = '')
    {
        $sql = "SELECT * FROM {$this->getEntity()}";

        if($filter)
        {
            $sql.= " WHERE {$filter}"; 
        }

        if($conn = Transaction::get())
        {
            $result = $conn->prepare($sql);
            Transaction::log($sql);
            $result->execute();

            if($result)
            {
                return $result->fetchAll(PDO::FETCH_CLASS,get_class($this));
            }
        }else 
        {
            throw new Exception('Não há transação ativa.');
        }
    }

    public function load($id)
    {
        if($id)
        {
            $sql = "SELECT * FROM {$this->getEntity()} WHERE id = " . (int) $id;

            if($conn = Transaction::get())
            {
                $result = $conn->prepare($sql);
                Transaction::log($sql);
                $result->execute();

                if($result)
                {
                    return $result->fetchObject(get_class($this));
                }
            }else 
            {
                throw new Exception('Não há transação ativa.');
            }
        }
    }


    public function store()
    {
        if(empty($this->data['id']) || (!$this->load($this->data['id'])))
        {
            $prepared = $this->prepare($this->data);
            if(empty($this->data['id']))
            {
                $this->data['id'] = $this->getLastId() + 1;
                $prepared['id'] = $this->data['id'];
            }

            $sql = "INSERT INTO {$this->getEntity()}" . 
            "(" . implode(', ', array_keys($prepared)) . ")" . 
            " values" . 
            "(" . implode(', ', array_values($prepared)).")";
        }else 
        {
            $prepared = $this->prepare($this->data);
            $set = [];

            foreach($prepared as $column => $value)
            {
                $set[] = "$column = $value";
            }

            $sql = "UPDATE {$this->getEntity()}";
            $sql.= " SET " . implode(', ', $set);
            $sql.= " WHERE id = " . (int) $this->data['id'];
        }

        if($conn = Transaction::get())
        {
            print "$sql";
            $result = $conn->prepare($sql);
            Transaction::log($sql);
            $result->execute();
        }else 
        {
            throw new Exception('Não há transação ativa.');
        }
    }

    public function delete($id = null)
    {
        $id = $id ? $id : $this->data['id'];

        if($id)
        {
            $sql = "DELETE FROM {$this->getEntity()} WHERE id = " . (int) $id;

            if($conn = Transaction::get())
            {
                $result = $conn->prepare($sql);
                Transaction::log($sql);
                $result->execute();
            }else 
            {
                throw new Exception('Não há transação ativa.');
            }
        }else 
        {
            throw new Exception('Não possível excluir dados da tabela ' . $this->getEntity());
        }
    }

    public function getLastId()
    {
        $sql = "SELECT MAX(id) FROM {$this->getEntity()}";

        if($conn = Transaction::get())
        {
            $result = $conn->prepare($sql);
            Transaction::log($sql);
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


    public function prepare($datas)
    {
        $prepared = [];

        foreach($datas as $key => $value)
        {
            if(is_scalar($value))
            {
                $prepared[$key] = $this->escape($value);
            }
        }

        return $prepared;
    }

    public function escape($value)
    {
        if(is_string($value))
        {
            $value = addslashes($value);
            return "'$value'";
        }else if(is_bool($value))
        {
            return $value ? 'TRUE' : 'FALSE'; 
        }else if(is_integer($value))
        {
            return $value;
        }else if(is_null($value))
        {
            return 'NULL';
        }else 
        {
            return $value;
        }
    }


}