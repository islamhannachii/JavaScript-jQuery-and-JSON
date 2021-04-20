<?php
session_start();
require_once 'pdo.php';
if(isset($_REQUEST['cancel'])) header('Location: index.php');
    if(isset($_POST['add']))
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
                        function validatePos()
                        {
                            for($i=1; $i<=9; $i++)
                            {
                                if ( ! isset($_POST['year'.$i]) ) continue;
                                if ( ! isset($_POST['desc'.$i]) ) continue;
                                $year = $_POST['year'.$i];
                                $desc = $_POST['desc'.$i];
                                if ( strlen($year) == 0 || strlen($desc) == 0 )
                                {
                                    return "All fields are required";
                                }                        
                                if ( ! is_numeric($year) )
                                {
                                    return "Position year must be numeric";
                                }
                            }
                            return 1;
                        }
                        if( validatePos() == 1)
                            {
                                        try 
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
                                            $profile_id = $pdo->lastInsertId();                                        
                                            $rank = 1;
                                            while((isset($_POST['year'.$rank])) && ($rank <= 9))
                                                {
                                                $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid,:rank, :year, :desc)');
                                                $stmt->execute(array(
                                                ':pid' => $profile_id,
                                                ':rank' => $rank,
                                                ':year' => htmlentities($_POST['year'.$rank]),
                                                ':desc' => htmlentities($_POST['desc'.$rank]))
                                                );
                                                    $rank++;
                                                }
                                                $_SESSION['success'] = "Profile added";
                                                header("Location:index.php");
                                                return;
                                            }
                                            catch (EXCEPTION $ex)
                                            {
                                                echo "Error: ". $ex->getMessage();
                                            }
                            }
                        else
                            {
                                $_SESSION['error'] = validatePos();
                                header("Location:add.php");
                                return;
                            }
                    }
            }
    }
?>

<html><head>
<title>Profile Add</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
</head>

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
Position: <input type="submit" id="addPos" value="+">

</p>
<div id="position_fields">
</div>
<p></p>
<p>
<input type="submit" value="Add" name="add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

</div>
<script>
countPos = 0;
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append('<div id="position'+countPos+'"> \<p>Year: <input type="text" name="year'+countPos+'" value="" /> \<input type="button" value="-" \onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \ <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\</div>');
    });
});
</script>


</body>
</html>