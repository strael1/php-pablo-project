<?php 

// namespace Source\Livro\Database;
// use Source\Livro\Log\Logger;


namespace Source\Lib\Livro\Database;
use Source\Lib\Livro\Database\Connection;
use Source\Lib\Livro\Log\Logger;

class Transaction 
{
    private static $conn;
    private static $logger;

    public static function open($database)
    {
        self::$conn = Connection::open($database);
        self::$conn->beginTransaction();
    }

    public static function close()
    {
        if(self::$conn)
        {
            self::$conn->commit();
            self::$conn = null;
        }
    }

    public static function rollback()
    {
        if(self::$conn)
        {
            self::$conn->rollback();
            self::$conn = null;
        }
    }

    public static function get()
    {
        return self::$conn;
    }

    public static function setLogger(Logger $logger)
    {
        if($logger instanceof Logger)
        {
            self::$logger = $logger;
        }
    }

    public static function log($message)
    {
        self::$logger->write($message);
    }

}