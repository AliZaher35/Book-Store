<?php
$dsn='mysql:host=localhost;dbname=book_store';
$user='root';
$pass='';
$option=
[
PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'

];
try
{
$db= new PDO($dsn,$user,$pass,$option);
$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e)
{
 echo 'faild'.$e->getMessage();
}

