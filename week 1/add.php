<?php
session_start();
require_once 'pdo.php';
if(isset($_REQUEST['cancel'])) header('Location: index.php');
    if(isset($_REQUEST['add']))
        {   
            if( !$_POST['first_name'] || !$_POST['last_name'] || !$_POST['email'] || !$_POST['headline'] || !$_POST['summary'])
                {
                    $_SESSION['error']= "All fields are required";
                    header("Location:add.php");
                     return;
                } 
            else 
            {
                if(!filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
                    {   $_SESSION['error']= "Email address must contain @";
                        header("Location:add.php");
                        return;
                    }
                else
                    {
                        $stmt = $pdo->prepare("INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)");
                        $stmt->execute(
                        array(
                            ':uid' => $_SESSION['user_id'],
                            ':fn' => htmlentities($_POST['first_name']),
                            ':ln' => htmlentities($_POST['last_name']),
                            ':em' => htmlentities($_POST['email']),
                            ':he' => htmlentities($_POST['headline']),
                            ':su' => htmlentities($_POST['summary'])
                            
                        ));
                        $_SESSION['success'] = "Profile added";
                        header("Location:index.php");
                        return;
                    }
            }
        }
?>


<html><head>
<title>652901eb Add Profile</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<body>
<div class="container">
<h1>Adding Profile for UMSI</h1>
<?php
if(isset($_SESSION['error'])) 
{
    ?> <p style="color:red"><?php echo $_SESSION['error'];?></p> <?php
    unset($_SESSION['error']);
}
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"></p>
<p>Last Name:
<input type="text" name="last_name" size="60"></p>
<p>Email:
<input type="text" name="email" size="30"></p>
<p>Headline:<br>
<input type="text" name="headline" size="80"></p>
<p>Summary:<br>
<textarea name="summary" rows="8" cols="80"></textarea>
</p><p>
<input type="submit" value="Add"  name="add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>


</body></html>