<?php

include "include.php";

$userid = $_SESSION["userid"];

if ($userid == 0){
    header("refresh: 0; login.php");
}

if (isset($_GET["pid"])){
    $pid = $_GET["pid"];
}
else{
    header("refresh: 0; project.php");
}

#Add Issue

if(isset($_POST["ititle"]) && isset($_POST["idescription"])) {
    if(!empty($_POST["ititle"]) && !empty($_POST["idescription"])) {
        $stmt = $mysqli->prepare("select iid from issue where pid = ? and userid = ? and ititle = ? and idescription=?");
        $stmt->bind_param("ddss", $pid, $userid, $_POST["ititle"], $_POST["idescription"]);
        $stmt->execute();
        if ($stmt->fetch()) {
          echo '<script type="text/javascript">';
          echo ' alert("This issue has already been reported.")';
          echo '</script>';
          $stmt->close();
        }
		else {

            #insert issue
		    $stmt->close();
		    $stmt = $mysqli->prepare("insert into issue (pid, userid, ititle, idescription) values (?,?,?,?)");
            $stmt->bind_param("ddss", $pid, $userid, $_POST["ititle"], $_POST["idescription"]);
            $stmt->execute();

            $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Report Issue')");
            $stmt->bind_param("d", $_SESSION["userid"]);
            $stmt->execute();
            $stmt->close();

            #insert authority
            $stmt = $mysqli->prepare("insert into authority (userid, iid) 
            select userid, iid from leads natural join 
            (select iid, pid from issue order by iid desc limit 1) as i1
            where pid = ?");
            $stmt->bind_param("d", $pid);
            $stmt->execute();
            $stmt->close();


            #insert track
            $stmt = $mysqli->prepare("insert into track (iid, stat)
            select iid, 'OPEN' from issue order by iid desc limit 1");
            $stmt->execute();
            $stmt->close();

            header("refresh: 0; issue.php?pid=$pid");
            
        }		  
    }	 
    else {
        echo '<script type="text/javascript">';
        echo ' alert("Enter all infomation")';
        echo '</script>';
    }
}


if(isset($_POST["clue"]) && !empty($_POST["clue"])){
    $clue = "%{$_POST['clue']}%";
    $stmt = $mysqli->prepare("select iid, userid, ititle, idescription from issue where pid = ? and (ititle like ? or idescription like ?)");
    $stmt->bind_param("dss", $pid, $clue, $clue);
    $stmt->execute();
    $stmt->bind_result($iid, $reporterid, $ititle, $idescription);


    echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Issues On Project $pid </div>";
    echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
    echo "<br>";
    echo "<br>";
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Issue ID</td><td>Issue Title</td><td>Description</td><td>Reporter ID</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        $ititle = htmlspecialchars($ititle);
        $idescription = htmlspecialchars($idescription);
        echo "<tr>";
        echo "<td>$iid</td><td>$ititle</td><td>$idescription</td><td>$reporterid</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    $stmt->close();
}
else {
    $stmt = $mysqli->prepare("select iid, userid, ititle, idescription from issue where pid = ?");
    $stmt->bind_param("d", $pid);
    $stmt->execute();
    $stmt->bind_result($iid, $reporterid, $ititle, $idescription);


    echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Issues On Project $pid </div>";
    echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
    echo "<br>";
    echo "<br>";
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Issue ID</td><td>Issue Title</td><td>Description</td><td>Reporter ID</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        $ititle = htmlspecialchars($ititle);
        $idescription = htmlspecialchars($idescription);
        echo "<tr>";
        echo "<td>$iid</td><td>$ititle</td><td>$idescription</td><td>$reporterid</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    $stmt->close();
}
echo "<br>";
echo "<form action='issue.php?pid=$pid' method='POST'>";
echo "Title / Description : <input type = 'text' name='clue'>";
echo "<input type='submit' value='Search' style='font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px'/>";
echo '</form>';

echo "</div>";

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Report Issue </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
echo "<form action='issue.php?pid=$pid' method='POST'>";
echo "Issue Title : <input type = 'text' name='ititle'>";

echo "<br>";
echo "<br>";
echo 'Description : ';
echo "<br>";
echo '<textarea name="idescription" rows = "5" cols="40"></textarea>';
echo "<br>";
echo "<br>";
echo "<input type='submit' value='Submit' style='font-size:10pt;font-family: Comic Sans MS, cursive, serif;color:#F9F7E8;background-color:#167C80;border:2px solid #F9F7E8;padding:3px'/>";
echo '</form>';





echo "<br>";
echo "<br>";
echo "\n";
echo '<br /><a href="project.php">Project Page</a>';

echo "\n";
echo '<br /><a href="main.php">Main Page</a>';

echo "</div>";
?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>Issue</title>
</html>