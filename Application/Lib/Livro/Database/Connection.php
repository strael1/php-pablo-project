<?php 

// namespace Source\Livro\Database;
// use PDO;

namespace Source\Lib\Livro\Database;
use PDO;

class Connection 
{
    public static function open($database)
    {
        if(file_exists("./Application/App/Config/{$database}.ini"))
        {
            $db = parse_ini_file("./Application/App/Config/{$database}.ini");
        }

        $host = isset($db['host']) ? $db['host'] : null;
        $user = isset($db['user']) ? $db['user'] : null;
        $pass = isset($db['pass']) ? $db['pass'] : null;
        $dbname = isset($db['dbname']) ? $db['dbname'] : null;
        $type = isset($db['type']) ? $db['type'] : null;

        switch($type)
        {
            case 'sqlite':
                $conn = new PDO("sqlite:database/livro.db");
                break;
            case 'mysql': 
                $port = isset($db['port']) ? $db['port'] : null;
                $conn = new PDO("mysql:host={$host};port={$port};dbname={$dbname}",$user,$pass);
                break;
            case 'pgsql': 
                $port = isset($db['port']) ? $db['port'] : null;
                $conn = new PDO("pgsql:host={$host};port={$port};user={$user};password={$pass};dbname={$dbname}");
                break;
        }

        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        return $conn;
    }
}