<?php

use Source\Lib\Livro\Log\LoggerTXT;
use Source\Lib\Livro\Database\Transaction;
use Source\App\Model\Pessoa;
use Source\Lib\Livro\Database\Criteria;
use Source\Lib\Livro\Database\Repository;

require_once './vendor/autoload.php';

try 
{
    Transaction::open('livro');
    Transaction::setLogger(new LoggerTXT('./get_collection.txt'));

    // $pessoa = new Pessoa();
    // print '<pre>';
    // print_r($pessoa->load(1));
    // print_r($pessoas::TABLENAME);
    // print '<pre>';
    // print_r($pessoa->load(1));

    $criteria = new Criteria();
    $criteria->add('id_cidade','=',1);

    $pessoa = new Repository('Pessoa');
    print_r($pessoa->load($criteria));


    // Assim a leitura é realizada com sucesso.
    // print Pessoa::TABLENAME;

    
    // $pessoa = new Repository('Pessoa');
    // print '<pre>';
    // print_r($pessoa->load($criteria));

    // $pessoa = new Pessoa();
    // print '<pre>';
    // print_r($pessoa->all());
    // print_r($pessoa->getLastId());
    // $pessoa->nome = 'João William';
    // $pessoa->endereco = 'Av. Jornalista costa porto';
    // $pessoa->bairro = 'Ibura';
    // $pessoa->telefone = '81988339922';
    // $pessoa->email = 'joaowilliam@gmail.com';
    // $pessoa->id_cidade = 1;
    // $pessoa->store();
    // $pessoa->delete(2);
    // print '<pre>';

    // $pessoas = ['nome'=>'rafael','idade'=>31];
    // print_r($pessoa->prepare($pessoas));
    // print $record->getEntity();
    // $record->nome = "Rafael";
    // $record->endereco = "rafael@gmail.com";
    // $record->bairro = "Ibura";

    // print_r($record->nome);


    // print_r(Transaction::get());

    Transaction::close();
}catch(Exception $e)
{
    print $e->getMessage();
    Transaction::rollback();
}