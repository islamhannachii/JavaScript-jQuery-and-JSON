<?php
session_start();
require_once 'pdo.php';
if(!$_GET['profile_id']) {$_SESSION['error'] = 'Could not load profile'; header("Location:index.php"); return; }
$stmt = $pdo->query("select * from profile where profile_id=".htmlentities($_GET["profile_id"]));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$row) {$_SESSION['error'] = 'Could not load profile'; header("Location:index.php"); return; }
if(isset($_POST['cancel'])) {header("Location:index.php"); return;}
if((isset($_POST['delete'])))
    {   
        try
        {
            $stmt = $pdo->prepare("delete from Profile where profile_id=".htmlentities($_GET["profile_id"]));
            $stmt ->execute();
            $stmt = $pdo->prepare("delete from Position where profile_id=".htmlentities($_GET["profile_id"]));
            $stmt ->execute();
            $_SESSION["success"] = "Profile deleted";
            header("Location:index.php");
            return;
        }
        catch (EXCEPTION $ex)
        {
            echo $ex->getMessage();
        }
        
    }
?>
<html><head>
<title>Delete Profile</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>

<div class="container">
<h1>Deleteing Profile</h1>
<form method="post">
    <p>First Name:<?php echo $row["first_name"]?></p>
    <p>Last Name:<?php echo $row["last_name"]?></p>
    <input type="hidden" name="profile_id" value="<?php echo htmlentities($_GET["profile_id"])?>">
    <input type="submit" name="delete" value="Delete">
    <input type="submit" name="cancel" value="Cancel">
    <p></p>
</form>
</div>


</body></html>