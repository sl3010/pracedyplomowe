<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors',1);
error_reporting(-1);
$mssqldriver = '{SQL Server}';
$hostname='172.16.3.101';
$dbname='prace';
$username=''; // usuniete
$password=''; // usuniete
$pdo = new PDO("odbc:Driver=$mssqldriver;Server=$hostname;Database=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$error= $pdo->errorInfo();
if (empty($error[2]) != NULL)
echo $error[2];
?>
