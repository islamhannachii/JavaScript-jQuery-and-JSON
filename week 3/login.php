<?php
require_once 'pdo.php';
session_start();
if(isset($_SESSION['user_id']) || isset($_POST['cancel'])) header('Location: index.php');
      else 
        {
            if(isset($_POST['login']))
            {   $salt= "XyZzy12*_";
                $check = hash('md5', $salt.$_POST['pass']);
                $email = htmlentities($_POST['email']);
                $stmt = $pdo->query("SELECT * FROM users WHERE email ='$email'");
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(!$row)
                    {
                        $_SESSION['error'] = "Incorrect email";
                        header("Location: login.php");
                        return;
                    }
                    else{
                        if(($row['email'] == htmlentities($_POST['email'])) && ($row['password'] == $check))
                            { $_SESSION['user_id'] = $row['user_id'];
                                $_SESSION['name'] = $row['name'];
                              header("Location: index.php");
                              return;}
                        else 
                        {
                            $_SESSION['error'] = "Incorrect password";
                            header("Location: login.php");
                            return;
                        }
                        
                }
            }

        }
?>
<html><head>
<title>Login Page Islam Hannachi 652901eb</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<body>
<div class="container">
<h1>Please Log In Islam Hannachi 4ce8f9c0</h1>
<?php
if(isset($_SESSION['error'])) 
{
    ?> <p style="color:red"><?php echo $_SESSION['error'];?></p> <?php
    unset($_SESSION['error']);
}
?>
<form method="POST" >
    <label for="email">Email</label>
    <input type="text" name="email" id="email"><br>
    <label for="id_1723">Password</label>
    <input type="password" name="pass" id="id_1723"><br>
    <input type="submit" name="login" onclick="return doValidate();" value="Log In">
    <input type="submit" name="cancel" value="Cancel">
</form>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>

</div>

</body></html>