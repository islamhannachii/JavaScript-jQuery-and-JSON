<?php
session_start();
require_once 'pdo.php';
include 'functions.php';
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
                        
                        if( (validatePos() == 1) && (validateEdu()==1))
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
                                            $rank = 1;
                                            while((isset($_POST['edu_school'.$rank])) && ($rank <= 9))
                                                {
                                                $stmt = $pdo->query("SELECT * from Institution WHERE name="."'".htmlentities($_POST['edu_school'.$rank])."'");
                                                if($row = $stmt->fetch(PDO::FETCH_ASSOC))
                                                    {
                                                        $institution_id = $row['institution_id'] ;
                                                    }
                                                else
                                                    {
                                                    $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
                                                    $stmt->execute(array(
                                                    ':name' => htmlentities($_POST['edu_school'.$rank])
                                                    ));
                                                $institution_id = $pdo->lastInsertId();}
                                                $stmt = $pdo->prepare('INSERT INTO Education (profile_id,institution_id,rank,year) VALUES (:pid,:iid,:rank, :year)');    
                                                $stmt->execute(array(
                                                    ':pid' => $profile_id,
                                                    ':rank' => $rank,
                                                    ':iid' => $institution_id,
                                                    ':year' => htmlentities($_POST['edu_year'.$rank])));
                                                    
                                                $rank++;
                                                

                                                }
                                                $_SESSION['success'] = "Profile added";
                                                header("Location:index.php");
                                                return;


                                            }
                                            catch (EXCEPTION $ex)
                                            {
                                                die("Error: ". $ex->getMessage());
                                            }
                            }
                        else
                            {
                                if((validatePos() != 1))
                               { $_SESSION['error'] = validatePos();
                                header("Location:add.php");
                                return;}
                                else {
                                    $_SESSION['error'] = validateEdu();
                                header("Location:add.php");
                                return;
                                }
                            }
                    }
            }
    }
?>

<html><head>
<title>Profile Add Islam Hannachi 4ce8f9c0</title>

<head>

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

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
    Education: <input type="submit" id="addEdu" value="+">
    </p><div id="edu_fields">
    </div>
    <p></p>
    <p>
    Position: <input type="submit" id="addPos" value="+">
    </p><div id="position_fields">
    </div>
    <p></p>
    <p>
    <input type="submit" value="Add" name= "add">
    <input type="submit" name="cancel" value="Cancel">
    </p>
</form>
</div>
<script>

countPos = 0;
countEdu = 0;
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
            <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });

});

</script>


</body>
</html>