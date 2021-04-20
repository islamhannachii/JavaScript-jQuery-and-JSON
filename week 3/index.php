<?php
require_once 'pdo.php';
session_start();

?>




<html><head>
<title>Home</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
</head>
<body>
<div class="container">
<h1>Resume Registry </h1>
    <?php 
        if(isset($_SESSION['user_id'])){?> <p><a href="logout.php">Logout</a></p><?php }
        else {?>  <p><a href="login.php">Please log in</a></p>
        <?php }
        if(isset($_SESSION['success'])){?><p style="color:green"><?php echo $_SESSION['success'];?></p> <? }unset($_SESSION['success']);
   ?>
<?php

if(isset($_SESSION['error'])) {?><p style="color:red"><?php echo $_SESSION['error'];?></p> <?php 
unset($_SESSION['error']); }?>

<?php
    $stmt = $pdo->query("select * from Profile");
    $stmt2 = $pdo->query("select * from Profile");
    $rows = $stmt2->fetch(PDO::FETCH_ASSOC);
if($rows)
{?>
    <table border="1">
    <tbody><tr><th>Name</th><th>Headline</th><?php if(isset($_SESSION['user_id'])) {?> <th>Action</th> <?php } ?></tr>
    <?php
        while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
            ?>
                <tr><td><a href="view.php?profile_id=<?php echo $row["profile_id"]?>"><?php echo $row['first_name'].$row['last_name']?></a></td><td><?php echo $row["headline"]?></td> <?php if(isset($_SESSION['user_id'])){?><td><a href="edit.php?profile_id=<?php echo $row["profile_id"]?>">Edit</a>  | <a href="delete.php?profile_id=<?php echo $row["profile_id"]?>">Delete</a></td><?php }?></tr>

            <?php } ?>
    </tbody></table>
<?php
}

    if(isset($_SESSION['user_id'])){?><p><a href="add.php">Add New Entry</a></p><?php }?>

</div>

</body></html>