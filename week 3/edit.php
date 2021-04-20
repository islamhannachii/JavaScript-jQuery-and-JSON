<?php
session_start();
require_once 'pdo.php';
if(!$_GET['profile_id']) {$_SESSION['error'] = 'Could not load profile'; header("Location:index.php"); return; }
$stmt = $pdo->query("select * from profile where profile_id=".htmlentities($_GET["profile_id"]));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt2 = $pdo->query("select * from position where profile_id=".htmlentities($_GET["profile_id"]));
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
                                                    $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
                                                    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
                                                    $profile_id = $_REQUEST['profile_id'];                                        
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
                                                        $_SESSION['success'] = "Profile updated";
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
                                header("Location:edit.php?profile_id=".htmlentities($_GET['profile_id']));                                return;
                            }
                    }
            }
        }
?>


<html><head>
<title> Profile Edit</title>
<!-- head.php -->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>


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
<form method="POST">
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
            </p>
                <?php 
                    $countPos = 0;
                    if($stmt2)
                    {   ?>
                        <p>        
                        Position: <input type="submit" id="addPos" value="+">
                        </p>
                        <div id="position_fields">
                        <?php
                    while( $row2 = $stmt2->fetch(PDO::FETCH_ASSOC))
                    {?>
                        <div id="position<?php echo $row2['rank'];?>">
                            <p>Year: <input type="text" name="year<?php echo $row2['rank'];?>" value="<?php echo $row2['year'];?>">
                                <input type="button" value="-" onclick="$('#position<?php echo $row2['rank'];?>').remove();return false;">
                            </p>
                            <textarea name="desc1" rows="8" cols="80"><?php echo $row2['description'];?></textarea>
                            <?php 
                            $countPos = $row2['rank'];
                    }echo "</div>"; print("<p></p><p><input type=\"submit\" value=\"Save\" name=\"add\"><input type=\"submit\" name=\"cancel\" value=\"Cancel\"></p></div>");}
                    ?>            
</form>
<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.11.4.js"></script>
<script>
countPos = <?php echo $countPos;?>;

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
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
            onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>
</div>


</body></html>