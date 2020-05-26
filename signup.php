<?php
ob_start();
include "include.php";

if(isset($_POST["email"]) && isset($_POST["username"])&& isset($_POST["dpname"])&& isset($_POST["password"])) {

    if(!empty($_POST["email"]) && !empty($_POST["username"])&& !empty($_POST["dpname"])&& !empty($_POST["password"])) {
        $stmt = $mysqli->prepare("select userid from user where email = ?");
        $stmt->bind_param("s", $_POST["email"]);
        $stmt->execute();
        if ($stmt->fetch()) {
          echo '<script type="text/javascript">';
          echo ' alert("This email is already registered.")';
          echo '</script>';
          $stmt->close();
        }
		//if not then insert the entry into database, note that user_id is set by auto_increment
		else {
            $cryptpw = crypt($_POST["password"],'issuetracking');
            echo "add val";
		    $stmt->close();
		    $stmt = $mysqli->prepare("insert into user (email,username, dpname, upassword) values (?,?,?,?)");
            $stmt->bind_param("ssss", $_POST["email"],  $_POST["username"], $_POST["dpname"],$cryptpw);
            $stmt->execute();
            $stmt->close();

            header("refresh: 0; login.php");
            exit();
            
        }		  
    }	 
    else {
        echo '<script type="text/javascript">';
        echo ' alert("Enter all infomation")';
        echo '</script>';
    }
}

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Please enter all the information before submit! </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo '<form action="signup.php" method="POST">';
echo "\n";	
echo 'Email: <input type="text" name="email" /><br />';
echo "\n";
echo 'Username: <input type="text" name="username" /><br />';
echo "\n";
echo 'Display name: <input type="text" name="dpname" /><br />';
echo "\n";
echo 'Password: <input type="password" name="password" /><br />';
echo "\n";
echo "<br>";
echo '<input type="submit" value="Submit" />';
echo "\n";
echo '</form>';
echo "\n";
echo '<br /><a href="login.php">Go back</a>';
echo "</div>";
?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>Sign Up</title>
</html>