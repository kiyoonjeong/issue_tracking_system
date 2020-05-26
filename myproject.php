<?php

include "include.php";
$userid = $_SESSION["userid"];

if ($userid == 0){
    header("refresh: 0; login.php");
}

if(isset($_POST["pid"]) && isset($_POST["leaderid"])) {
    if(!empty($_POST["pid"]) && !empty($_POST["leaderid"])) {
        $stmt = $mysqli->prepare("select pid from leads where userid = ? and pid = ?");
        $stmt->bind_param("dd", $userid, $_POST["pid"]);
        $stmt->execute();
        if ($stmt->fetch()) {
		    $stmt->close();
		    $stmt = $mysqli->prepare("insert into leads (userid ,pid) values (?,?)");
            $stmt->bind_param("dd", $_POST["leaderid"],  $_POST["pid"]);
            $stmt->execute();
            echo '<script type="text/javascript">';
            echo ' alert("Leader Successfully Added.")';
            echo '</script>';
            $stmt->close();  

            $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Add Leader')");
            $stmt->bind_param("d", $_SESSION["userid"]);
            $stmt->execute();
            $stmt->close();

        }
		//if not then insert the entry into database, note that user_id is set by auto_increment
		else {
            echo '<script type="text/javascript">';
            echo ' alert("You are not authorized to manage this project.")';
            echo '</script>';
            $stmt->close();  
        }		  
    }	 
    else if(isset($_POST["iid"]) && isset($_POST["assigneeid"])) {
        if(!empty($_POST["iid"]) && !empty($_POST["assigneeid"])) {
            $stmt = $mysqli->prepare("select iid from (select pid from leads where userid = ?) as t1 natural join
            issue where iid = ?");
            $stmt->bind_param("dd", $userid, $_POST["iid"]);
            $stmt->execute();
            if ($stmt->fetch()) {
                $stmt->close();
                $stmt = $mysqli->prepare("insert into authority (userid ,iid) values (?,?)");
                $stmt->bind_param("dd", $_POST["assigneeid"],  $_POST["iid"]);
                $stmt->execute();
                echo '<script type="text/javascript">';
                echo ' alert("Assignee Successfully Added.")';
                echo '</script>';
                $stmt->close();  

                $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Add Assignee')");
                $stmt->bind_param("d", $_SESSION["userid"]);
                $stmt->execute();
                $stmt->close();

            }
            //if not then insert the entry into database, note that user_id is set by auto_increment
            else {
                echo '<script type="text/javascript">';
                echo ' alert("You are not authorized to assign this issue.")';
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
    else {
        echo '<script type="text/javascript">';
        echo ' alert("Enter all infomation")';
        echo '</script>';
    }
}

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> List Of My Project and Co-leaders</div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
if ($stmt = $mysqli->prepare("select pid, pname, userid as leader from
(select pid from leads where userid = ?)as t1 natural join project natural join leads"
)) {
    $stmt->bind_param("d", $userid);
    $stmt->execute();
    $stmt->bind_result($pid, $pname, $leader);
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Project ID</td><td>Title</td><td>Leaders ID</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        $pname = htmlspecialchars($pname);
        echo "<tr>";
        echo "<td>$pid</td><td>$pname</td><td>$leader</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";

    $stmt->close();
}
echo "<br>";
echo "* Add Project Leader";
echo "<br>";
echo "<br>";
echo "\n";
echo "<form action='myproject.php' method='POST'>";
echo "Project ID : <input type = 'number' name='pid'>";
echo "&nbsp&nbsp&nbsp&nbsp";
echo "Leader ID : <input type = 'number' name='leaderid' >";
echo "&nbsp&nbsp&nbsp&nbsp";
echo "<input type='submit' value='Add' style='font-size:8pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px'/>";



echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "</div>";


echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Assigned Issue Table </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
if ($stmt = $mysqli->prepare("select pid, pname, iid, ititle, idescription from issue natural join
(select pid from leads where userid = ?)as t1 natural join project")) {
    $stmt->bind_param("d", $userid);
    $stmt->execute();
    $stmt->bind_result($pid, $pname, $iid, $ititle, $idescription);
    
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Project ID</td><td>Title</td><td>Issue ID</td><td>Issue title</td><td>Issue Description</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        $pname = htmlspecialchars($pname);
        $ititle = htmlspecialchars($ititle);
        $idescription = htmlspecialchars($idescription);
        echo "<tr>";
        echo "<td>$pid</td><td>$pname</td><td>$iid</td><td>$ititle</td><td>$idescription</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    $stmt->close();
}
echo "<br>";
echo "* Add Assignee";
echo "<br>";
echo "<br>";
echo "\n";
echo "<form action='myproject.php' method='POST'>";
echo "Issue ID : <input type = 'number' name='iid'>";
echo "&nbsp&nbsp&nbsp&nbsp";
echo "Assignee ID : <input type = 'number' name='assigneeid'>";
echo "&nbsp&nbsp&nbsp&nbsp";
echo "<input type='submit' value='Add' style='font-size:8pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px'/>";


echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo "</div>";

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Edit Workflow </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "* Please select the project which you want to fix";
echo "<br>";
echo "<br>";
if ($stmt = $mysqli->prepare("select pid from leads where userid = ?")) {
    $stmt->bind_param("d", $userid);
    $stmt->execute();
    $stmt->bind_result($pid);
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Project ID</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<td><a href='leadsworkflow.php?pid=$pid'>$pid</a></td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    $stmt->close();
}



echo "<br>";
echo '<br /><a href="main.php">Go back</a>';


echo "</div>";
?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>My Project</title>
</html>