<?php
session_start();
require_once 'pdo.php';
if(!$_GET['profile_id']) {$_SESSION['error'] = 'Could not load profile'; header("Location:index.php"); return; }
$stmt = $pdo->query("select * from profile where profile_id=".htmlentities($_GET["profile_id"]));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$row) {$_SESSION['error'] = 'Could not load profile'; header("Location:index.php"); return; }
if(isset($_REQUEST['cancel'])) header('Location: index.php');
    if(isset($_REQUEST['add']))
        {   
            if( !$_POST['first_name'] || !$_POST['last_name'] || !$_POST['email'] || !$_POST['headline'] || !$_POST['summary'])
                {
                    $_SESSION['error']= "All fields are required";
                    header("Location:edit.php?profile_id=".htmlentities($_GET['profile_id']));
                     return;
                } 
            else 
            {
                if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
                    {   $_SESSION['error']= "Email address must contain @";
                        header("Location:edit.php?profile_id=".htmlentities($_GET['profile_id']));
                        return;
                    }
                else
                    {
                        $stmt = $pdo->prepare("update  Profile set user_id =:uid, first_name=:fn, last_name=:ln, email=:em, headline=:he, summary=:su ");
                        $stmt->execute(
                        array(
                            ':uid' => $_SESSION['user_id'],
                            ':fn' => htmlentities($_POST['first_name']),
                            ':ln' => htmlentities($_POST['last_name']),
                            ':em' => htmlentities($_POST['email']),
                            ':he' => htmlentities($_POST['headline']),
                            ':su' => htmlentities($_POST['summary'])
                            
                        ));
                        $_SESSION['success'] = "Profile updated";
                        header("Location:index.php");
                        return;
                    }
            }
        }
?>


<html><head>
<title>Add Profile</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<body>
<div class="container">
<h1>Editing Profile for UMSI</h1>
<?php
if(isset($_SESSION['error'])) 
{
    ?> <p style="color:red"><?php echo $_SESSION['error'];?></p> <?php
    unset($_SESSION['error']);
}
?>
<?php
   
    
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" value = "<?php echo $row['first_name']?>" size="60"></p>
<p>Last Name:
<input type="text" name="last_name" value = "<?php echo $row['last_name']?>" size="60"></p>
<p>Email:
<input type="text" name="email" value = "<?php echo $row['email']?>" size="30"></p>
<p>Headline:<br>
<input type="text" name="headline" value = "<?php echo $row['headline']?>" size="80"></p>
<p>Summary:<br>
<textarea name="summary" rows="8" cols="80"><?php echo $row['summary']?></textarea>
</p><p>
<input type="submit" value="Save"  name="add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>


</body></html>