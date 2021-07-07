<?php 

// namespace Source\Livro\Log;
// use Source\Livro\Log\Logger;
namespace Source\Lib\Livro\Log;
use Source\Lib\Livro\Log\Logger;

class LoggerTXT extends Logger
{
    public function write($message)
    {
        $text = date('Y-m-d H:i:s') . ' ' . $message;
        $handler = fopen($this->filename,'a');
        fwrite($handler,$text);
        fclose($handler);
    }
}