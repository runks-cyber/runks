<?php

$host= "localhost";
$dbname= "userdb";
$username= "root";
$password= "";

try {
    $PDO= new PDO("mysql:host=$host;dbname=$dbname",$username, $password);
    $PDO-> setAttribute(PDO::ATTR_ERRMODE, PDO:: ERRMODE_EXCEPTION);
  
} catch (PDOException $e) {
    echo "Connection Failed".$e->getMessage();
}


