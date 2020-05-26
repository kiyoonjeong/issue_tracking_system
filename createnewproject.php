<?php
ob_start();
include "include.php";

$userid = $_SESSION["userid"];

if ($userid == 0){
    header("refresh: 0; login.php");
}

if(isset($_POST["pname"]) && isset($_POST["pdescription"])) {
    if(!empty($_POST["pname"]) && !empty($_POST["pdescription"])) {
        $stmt = $mysqli->prepare("insert into project (pname, pdescription) values (?,?)");
        $stmt->bind_param("ss", $_POST["pname"],  $_POST["pdescription"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Create New Project')");
        $stmt->bind_param("d", $_SESSION["userid"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("select pid from project where pname = ? and pdescription = ?");
        $stmt->bind_param("ss", $_POST["pname"],  $_POST["pdescription"]);
        $stmt->execute();
        $stmt->bind_result($pid);
        if ($stmt->fetch()){
            $_SESSION["pid"]=$pid;
        }
        $stmt->close();
        $pid = $_SESSION["pid"];
        $userid = $_SESSION["userid"];
        $stmt = $mysqli->prepare("insert into leads (userid, pid) values (?,?)");
        $stmt->bind_param("dd", $userid,  $pid);
        $stmt->execute();
        $stmt->close();


        header("refresh: 0; leadsworkflow.php?pid=$pid");
        exit();


    }		  	 
    else {
        echo '<script type="text/javascript">';
        echo ' alert("Enter all infomation")';
        echo '</script>';
    }
}

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Create a New Project </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo '<form action="createnewproject.php" method="POST">';
echo "<br>";	
echo 'Title : <input type="text" name="pname" /><br />';
echo "\n";
echo "<br>";
echo 'Description : ';
echo "<br>";
echo '<textarea name="pdescription" rows = "5" cols="40"></textarea>';
echo "\n";
echo "<br>";
echo "<br>";
echo '<input type="submit" value="Create" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px"/>';
echo "\n";
echo '</form>';

echo "\n";
echo '<br /><a href="main.php">Go back</a>';

echo "</div>";
?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>Create New Project</title>
</html>