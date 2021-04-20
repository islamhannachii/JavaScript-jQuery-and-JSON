<?php
require_once 'pdo.php';
if(!isset($_REQUEST['term'])) {die("Messing same parameter");}
$stmt = $pdo->prepare('SELECT name FROM Institution
    WHERE name LIKE :prefix');
$stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
$retval = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['name'];
}

?>
<html>
<body><pre style="word-wrap: break-word; white-space: pre-wrap;">
<?php echo(json_encode($retval, JSON_PRETTY_PRINT));?>   
</pre></body>
</html>