<?php
session_start();
require_once 'pdo.php';
if(!$_GET['profile_id']) {$_SESSION['error'] = 'Could not load profile'; header("Location:index.php"); return; }
$stmt = $pdo->query("select * from profile where profile_id=".htmlentities($_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$row) {$_SESSION['error'] = 'Could not load profile'; header("Location:index.php"); return; }

?>
<html><head>
<title>Profile View</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">


<body>
<div class="container">
    <h1>Profile information</h1>
        <p>First Name: <?php echo $row['first_name']?></p>
        <p>Last Name: <?php echo $row['last_name']?></p>
        <p>Email:<?php echo $row['email']?></p>
        <p>Headline:<br><?php echo $row['headline']?></p>
        <p>Summary:<br><?php echo $row['summary']?></p>
        <?php 
            $stmt = $pdo->query("select * from Position where profile_id=".htmlentities($_GET['profile_id']));
            if($stmt)
            {   ?>
                <p>Position:</p>
                <ul>
              <?php
                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {
                   echo "<li>".$row['year'].": " .$row['description']."</li>";
                }
            }
                ?>
                </ul>
        
                <?php 
            $stmt = $pdo->query("select * from education where profile_id=".htmlentities($_GET['profile_id']));
            if($stmt)
            {   ?>
                <p>Education:</p>
                <ul>
              <?php
                while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                {   
                    $school = $pdo->query("select * from Institution where institution_id= '".$row['institution_id']."'");  $r_school=$school->fetch(PDO::FETCH_ASSOC); 
                   echo "<li>".$row['year'].": " . $r_school['name'] ."</li>";
                }
            }
                ?>
                </ul>
<a href="index.php">Done</a>
</div>


</body></html>