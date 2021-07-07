<?php 

// namespace Source\Livro\Log;
namespace Source\Lib\Livro\Log;

abstract class Logger 
{
    protected $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
        file_put_contents($filename,'');
    }

    abstract public function write($message);
}