<?php session_start();
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'bookstore';
function connectDb($host,$user,$pass,$db){
    try{
        $conn = new PDO("mysql:host=$host;dbname=$db",$user,$pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
    catch (PDOException $e){
        die($e);
        go("http://localhost/bookstore/404.php");
    }
}