<?php 
try {
    // Database Information
    // Change the port, dbname, username and password
    $pdo = new PDO('mysql: host=localhost; port=3306; dbname= DATABASENAME', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch (PDOEXCEPTION $ex)
{
    die( "DATA BASE ERROR\n".$ex->getMessage());
}
    
    ?>