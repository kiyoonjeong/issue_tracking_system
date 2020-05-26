<?php

include "include.php";


$userid = $_SESSION["userid"];

if($userid==0) {
    header("refresh: 0; login.php");
}
$stmt = $mysqli->prepare("select dpname from user where userid = ?");
$stmt->bind_param("d", $userid);
$stmt->execute();
$stmt->bind_result($dpname);
while ($stmt->fetch()){
    $dpname = htmlspecialchars($dpname);
    echo "<div style = 'font-size:50px; font-family: Comic Sans MS, cursive, serif; color: #F9F7E8;'> $dpname's main page</div>";
    echo "<br>";
    echo "<br>";
}
echo "<br>";
echo "<br>";
echo '<form action = "createnewproject.php">';
echo '<input type="submit" value="Create a new project" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#61BFAD;border:2px solid #F9F7E8;padding:3px"/>';
echo '</form>';
echo '<form action = "project.php">';
echo '<input type="submit" value="View all projects" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#61BFAD;border:2px solid #F9F7E8;padding:3px"/>';
echo '</form>';
echo '<form action = "myproject.php">';
echo '<input type="submit" value="Manage my projects" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#61BFAD;border:2px solid #F9F7E8;padding:3px"/>';
echo '</form>';
echo '<form action = "myissue.php">';
echo '<input type="submit" value="Manage my issues" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#61BFAD;border:2px solid #F9F7E8;padding:3px"/>';
echo '</form>';
echo '<form action = "myactivity.php">';
echo '<input type="submit" value="View my activities" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#61BFAD;border:2px solid #F9F7E8;padding:3px"/>';
echo '</form>';
echo "<br>";

echo "\n";
echo "\n";
echo "\n";
echo '<br /><a href="login.php">Log out</a>';

?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#FF8B8B ; background-image:url('paw.png'); background-repeat: no-repeat; background-size: 500px 500px; background-position: center">
</body>
<title>Main Page</title>
</html>