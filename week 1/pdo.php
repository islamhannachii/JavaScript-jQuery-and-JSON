<?php 
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=new', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch (EXCEPTION $ex)
{
    echo "DATA BASE ERROR\n";
    echo $ex->getMessage();
    }
    
    ?>