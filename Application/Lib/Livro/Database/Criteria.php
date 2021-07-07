<?php 

// namespace Source\Livro\Database;


namespace Source\Lib\Livro\Database;

class Criteria 
{
    private $filters;
    private $properties;
 
    public function __construct()
    {
        $this->filters = [];
        $this->properties = [];
    }
    public function add($variable,$operator,$value,$logic_op = 'and')
    {
        if(empty($this->filters)){
            $logic_op = null;
        }   

        $this->filters[] = [$variable,$operator,$this->transform($value),$logic_op];
    }

    public function dump()
    {
        $results = '';

        foreach($this->filters as $filter)
        {
            $results .= $filter[3] . ' ' . $filter[0] . ' ' . $filter[1] . ' ' . $filter[2] . ' ';
        }

        return "({$results})";
    }

    public function transform($value)
    {
        if(is_array($value))
        {
            foreach($value as $x)
            {
                if(is_string($x))
                {
                    $foo[] = "'$x'";
                }else if(is_integer($x))
                {
                    $foo[] = $x;
                }
            }

            $result = "(" . implode(', ', $foo) . ")";
        }else if(is_string($value))
        {
            $result = "'$value'";
        }else if(is_bool($value))
        {
            $result = $value ? 'TRUE' : 'FALSE';
        }else if(is_integer($value)){
            $result = $value;
        }else if(is_null($value))
        {
            $result = 'NULL';
        }else if($value)
        {
            $result = $value;
        }

        return $result;
    }

    public function setProperty($prop,$value)
    {
        if($value == null)
        {
            unset($this->properties[$prop]);
        }else 
        {
            $this->properties[$prop] = $value;
        }
    }

    public function getProperty($prop)
    {
        if(isset($this->properties[$prop]))
        {
            return $this->properties[$prop];
        }       
    }
    
}