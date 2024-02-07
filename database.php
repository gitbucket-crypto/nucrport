<?php 
function connect()
{
    $host= '192.168.210.40';
    $db = 'IOTDatabase';
    $user = 'postgres';
    $password = "2Smx'P?8[#RA\#9Z";
    
    // $host= 'localhost';
    // $db = 'iotDatabase';
    // $user = 'postgres';
    // $password = "fast9002";
    $pdo = null;
    try
    {
        $dsn = "pgsql:host=$host;port=5432;dbname=$db;";
        // make a database connection
        return new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);        
    }
    catch (PDOException $e) 
    {
        die($e->getMessage());
    }
   
}


?>