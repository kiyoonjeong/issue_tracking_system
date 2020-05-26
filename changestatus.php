<?php
ob_start();
include "include.php";

$userid = $_SESSION["userid"];

if ($userid == 0){
    header("refresh: 0; login.php");
}

if(isset($_GET["iid"])) {
    $stmt = $mysqli->prepare("select iid from authority where userid = ? and iid = ?");
    $stmt->bind_param("dd", $userid, $_GET["iid"]);
    $stmt->execute();
    if ($stmt->fetch()) {
        $stmt->close();
        $stmt = $mysqli -> prepare("select stat from track natural join (select iid, max(timedate) as timedate from track group by iid) as a1 
        where iid = ?");
        $stmt->bind_param("d", $_GET["iid"]);
        $stmt->execute();
        $stmt->bind_result($status);
        while ($stmt->fetch()){
            $current_status = $status;
        }
    }
    else{
        header("refresh: 0; login.php");
    }
    $stmt->close();    
}


if(isset($_GET["nextstat"])) {
    $stmt = $mysqli->prepare("select pid from workflow where currstat = ? and nextstat = ?");
    $stmt->bind_param("ss", $current_status, $_GET["nextstat"]);
    $stmt->execute();
    if ($stmt->fetch()){
        $stmt->close();
        $stmt = $mysqli->prepare("insert into track (iid, stat) values 
        (?,?)");
        $stmt->bind_param("ds", $_GET["iid"], $_GET["nextstat"]);
        $stmt->execute();
        $stmt->close();

        $stmt = $mysqli->prepare("insert into activity (userid, actions) values (?, 'Change Issue Status')");
        $stmt->bind_param("d", $_SESSION["userid"]);
        $stmt->execute();
        $iid = $_GET["iid"];
        header("refresh: 0; changestatus.php?iid=$iid");
    }
    else {
        header("refresh: 0; login.php");
    }
    $stmt->close();
}

if(isset($_GET["iid"])) {
    $stmt = $mysqli->prepare("select nextstat from workflow where pid = 
    (select pid from issue where iid = ?) and currstat = ?");
    $stmt->bind_param("ds", $_GET["iid"], $current_status);
    $stmt->execute();
    $stmt->bind_result($nextstat);

    $iid = $_GET["iid"];
    $current_status = htmlspecialchars($current_status);
    echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Issue $iid's Current Status : $current_status </div>";
    echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
    echo "<br>";
    echo "<br>";
    echo "<br>";
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Next Possible Status</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        $nextstat = htmlspecialchars($nextstat);
        echo "<tr>";
        echo "<td><a href='changestatus.php?iid=$iid&nextstat=$nextstat'>$nextstat</a></td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    echo "</div>";
    $stmt->close();
}
echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Tracking Status Changes </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
if ($stmt = $mysqli->prepare("select timedate, stat from track where iid = ? order by timedate desc")) {
    $stmt->bind_param("d", $_GET["iid"]);
    $stmt->execute();
    $stmt->bind_result($timedate, $stat);
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Date and Time</td><td>Status</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        $stat = htmlspecialchars($stat);
        echo "<tr>";
        echo "<td>$timedate</td><td>$stat</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";

    $stmt->close();
}

echo "\n";
echo '<br /><a href="main.php">Main Page</a>';

echo "\n";
echo '<br /><a href="myissue.php">My Issue Page</a>';
echo "</div>";
?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<body style = "background-color:#F8EBEE">
</body>
<title>Change Issue Status</title>
<html>
</html>