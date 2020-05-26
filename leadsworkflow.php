<?php
ob_start();
include "include.php";

$userid = $_SESSION["userid"];

if ($userid == 0){
    header("refresh: 0; login.php");
}


if(isset($_GET["pid"])) {
    $pid = $_GET["pid"];
}

// pid  authorized valid?
$stmt = $mysqli->prepare("select pid from leads where pid = ? and userid = ?");
$stmt->bind_param("dd", $pid, $userid);
$stmt->execute();
if ($stmt->fetch()) {
    $stmt->close();

    #ADD
    if(isset($_POST["addcurrstat"]) && isset($_POST["addnextstat"])) {
        if(!empty($_POST["addcurrstat"]) && !empty($_POST["addnextstat"])) {
            $stmt = $mysqli->prepare("select pid from workflow where pid = ? and currstat = ? and nextstat=? ");
            $stmt->bind_param("dss", $pid, $_POST["addcurrstat"], $_POST["addnextstat"]);
            $stmt->execute();
            if ($stmt->fetch()) {
            echo '<script type="text/javascript">';
            echo ' alert("This workflow already exists.")';
            echo '</script>';
            $stmt->close();
            }
            else {
                $stmt->close();
                $stmt = $mysqli->prepare("insert into workflow (pid,currstat, nextstat) values (?,?,?)");
                $stmt->bind_param("dss", $pid, $_POST["addcurrstat"], $_POST["addnextstat"]);
                $stmt->execute();
                $stmt->close();

                $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Add Workflow')");
                $stmt->bind_param("d", $_SESSION["userid"]);
                $stmt->execute();
                $stmt->close();

                header("refresh: 0; leadsworkflow.php?pid=$pid");      
            }		  
        }	 
        else {
            echo '<script type="text/javascript">';
            echo ' alert("Enter all infomation")';
            echo '</script>';
        }
    }

    #Delete
    else if(isset($_POST["delcurrstat"]) && isset($_POST["delnextstat"])) {
        if(!empty($_POST["delcurrstat"]) && !empty($_POST["delnextstat"])) {
            $stmt = $mysqli->prepare("select pid from workflow where pid = ? and currstat = ? and nextstat=? ");
            $stmt->bind_param("dss", $pid, $_POST["delcurrstat"], $_POST["delnextstat"]);
            $stmt->execute();
            if ($stmt->fetch()) {
                $stmt->close();
                $stmt = $mysqli->prepare("select count(pid) from workflow where currstat = 'OPEN' and pid = ?");
                $stmt->bind_param("d", $pid);
                $stmt->execute();
                $stmt->bind_result($count);
                $stmt->fetch();
                if ($_POST["delcurrstat"] == "OPEN" && $count == 1){
                    echo '<script type="text/javascript">';
                    echo ' alert("OPEN should exist in workflow")';
                    echo '</script>';
                    $stmt->close();  
                }

                else{
                    $stmt->close();
                    $stmt = $mysqli->prepare("delete from workflow where pid = ? and currstat = ? and nextstat=?");
                    $stmt->bind_param("dss", $pid, $_POST["delcurrstat"], $_POST["delnextstat"]);
                    $stmt->execute();
                    $stmt->close();  
                    header("refresh: 0; leadsworkflow.php?pid=$pid");

                    $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Delete Workflow')");
                    $stmt->bind_param("d", $_SESSION["userid"]);
                    $stmt->execute();
                    $stmt->close();
                }
            }
            else {
                echo '<script type="text/javascript">';
                echo ' alert("This workflow does not exist.")';
                echo '</script>';
                $stmt->close();      
            }		  
        }	 
        else {
            echo '<script type="text/javascript">';
            echo ' alert("Enter all infomation")';
            echo '</script>';
        }
    }

    #Table
    $stmt = $mysqli->prepare("select currstat, nextstat from workflow where pid = ?");
    $stmt->bind_param("d", $pid);
    $stmt->bind_result($currstat, $nextstat);
    $stmt->execute();
    if ($stmt->fetch()){
        $stmt->execute();
        echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Project $pid's workflow </div>";
        echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
        echo "<br>";
        echo "<br>";
        echo "Current Workflow";
        echo "<br>";
        echo "<br>";
        echo "<table border = '1'>\n";
        echo "<tr>";
        echo "<td>Current Status</td><td>Next Status</td>";
        echo "</tr>\n";
        while ($stmt->fetch()) {
            $currstat = htmlspecialchars($currstat);
            $nextstat = htmlspecialchars($nextstat);
            echo "<tr>";
            echo "<td>$currstat</td><td>$nextstat</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
        $stmt->close();
    }
    else{
        $stmt->close();
        $stmt = $mysqli->prepare("insert into workflow (pid, currstat, nextstat) values (?, 'OPEN', 'CLOSED')");
        $stmt ->bind_param("d", $pid);
        $stmt->execute();
        $stmt-> close();

        $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Initiate Workflow')");
        $stmt->bind_param("d", $_SESSION["userid"]);
        $stmt->execute();
        $stmt->close();

        header("refresh: 0; leadsworkflow.php?pid=$pid");
        exit();
    }


    echo "<br>";
    echo "* Add Workflow";
    echo "<br>";
    echo "<br>";
    echo "\n";
    echo "<form action='leadsworkflow.php?pid=$pid' method='POST'>";
    echo "Current Status : <input type = 'text' name='addcurrstat'>";
    echo "&nbsp&nbsp&nbsp&nbsp";
    echo "Next Status : <input type = 'text' name='addnextstat'>";
    echo "&nbsp&nbsp&nbsp&nbsp";
    echo "<input type='submit' value='Add' style='font-size:8pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px'/>";
    echo '</form>';


    echo "<br>";
    echo "* Delete Workflow";
    echo "<br>";
    echo "<br>";
    echo "\n";
    echo "<form action='leadsworkflow.php?pid=$pid' method='POST'>";
    echo "Current Status : <input type = 'text' name='delcurrstat'>";
    echo "&nbsp&nbsp&nbsp&nbsp";
    echo "Next Status : <input type = 'text' name='delnextstat'>";
    echo "&nbsp&nbsp&nbsp&nbsp";
    echo "<input type='submit' value='Delete' style='font-size:8pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px'/>";
    echo '</form>';


    echo "\n";
    echo '<br /><a href="main.php">Main Page</a>';

    echo "\n";
    echo '<br /><a href="myproject.php">My Project</a>';
    echo "</div>";

}
else{
    $stmt->close();
    header("refresh: 0; login.php");
}

?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>Workflow</title>
</html>