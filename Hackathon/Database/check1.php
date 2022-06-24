<?php
include_once("vendor/autoload.php");
$config = new \Doctrine\DBAL\Configuration();
//..
$connectionParams = array(
    'dbname' => 'custom',
    'user' => 'root',
    'password' => '',
    'host' => 'localhost',
    'driver' => 'pdo_mysql',
);
$conn = \Doctrine\DBAL\DriverManager::getConnection($connectionParams, $config);


$sql = "SELECT * FROM user WHERE username  = ? OR username = ? ";
//$stmt = $conn->prepare($sql);
$name = array('John','John1');
$stmt=$conn->executeQuery($sql,$name);
//$stmt->execute();
$users = $stmt->fetchAll();
print_r($users);
die;
$sql = "SELECT username FROM user WHERE id = ? ";
$stmt = $conn->prepare($sql);
$id=1;
$stmt->bindValue(1, $id);

$stmt->execute();
$users = $stmt->fetch();
print_r($users);

//$stmt = $conn->executeQuery('SELECT * FROM articles WHERE id IN (?)',
    //array(array(1, 2, 3, 4, 5, 6)),
    //array(\Doctrine\DBAL\Connection::PARAM_INT_ARRAY)
?>