<?php
ob_start();
include ("include.php");

$_SESSION["userid"] = 0;
$userid = $_SESSION["userid"];
if(isset($_POST["email"]) && isset($_POST["password"])) {
    if ($stmt = $mysqli->prepare("select userid from user where email = ? and upassword = ?")) {
      $cryptpw = crypt($_POST["password"], 'issuetracking');
      $stmt->bind_param("ss", $_POST["email"], $cryptpw);
      $stmt->execute();
      $stmt->bind_result($userid);
      if ($stmt->fetch()) {
        session_start();
        $_SESSION["userid"] = $userid;
        $stmt->close();
        header("refresh: 0; main.php");
        exit();
      
      }
		  else {
        $stmt->close();
        echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Welcome to the Issue Tracking System </div>";
        echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #FF8B8B;'> Enter valid email and password.</div>";
        echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
        echo "\n";
        echo '<form action = "login.php" method="POST">';
        echo "\n";
        echo 'Email: <input type="text" name="email" /><br />';
        echo "\n";
        echo 'Password: <input type="password" name="password" /><br />';
        echo "\n";
        echo "<br>";
        echo '<input type="submit" value="Login" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px"/>';
        echo '</form>';
        echo '<form action = "signup.php">';
	      echo '<input type="submit" value="Signup" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px"/>';
        echo '</form>';
        echo '</div>';
    		  
      }
    }	 
}

else {
    echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Welcome to the Issue Tracking System </div>";
    echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
    echo "<br>";
    echo '<form action = "login.php" method="POST">';
    echo "\n";
    echo 'Email: <input type="text" name="email" /><br />';
    echo "\n";
	  echo 'Password: <input type="password" name="password" /><br />';
    echo "\n";
    echo "<br>";
	  echo '<input type="submit" value="Login" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px"/>';
    echo '</form>';
    echo '<form action = "signup.php">';
	  echo '<input type="submit" value="Signup" style="font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px"/>';
    echo '</form>';
}

?>

<!DOCTYPE html>

<html>
<title>Log-in</title>
<body style = "background-color:#F8EBEE">
<style>
input[type=text]{
  color : grey;
}
input[type=text]:hover
{
  background : aliceblue;
}
</style>
</body>
</html>
