<?php

include "include.php";

$userid = $_SESSION['userid'];

if ($userid == 0){
    header("refresh: 0; login.php");
}

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Manage My Issues </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
if ($stmt = $mysqli->prepare("select iid, pid, userid, ititle, idescription, current_stat from
(select iid from authority where userid = ?) as t1 natural join issue natural join
(select iid, stat as current_stat from track natural join
(select iid, max(timedate) as timedate from track group by iid) as a1) as b1;"
)) {
    $stmt->bind_param("d", $userid);
    $stmt->execute();
    $stmt->bind_result($iid, $pid, $reporterid, $ititle, $idescription, $status);
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Issue ID</td><td>Issue Title</td><td>Project ID</td><td>Reporter ID</td><td>Description ID</td><td>Status</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        $ititle = htmlspecialchars($ititle);
        $idescription = htmlspecialchars($idescription);
        $status = htmlspecialchars($status);
        echo "<tr>";
        echo "<td>$iid</td><td>$ititle</td><td>$pid</td><td>$reporterid</td><td>$idescription</td>
        <td><a href='changestatus.php?iid=$iid'>$status</a></td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    $stmt->close();
}

echo "\n";
echo '<br /><a href="main.php">Main page</a>';
echo "</div>";
?>
<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>My Issue</title>
</html>