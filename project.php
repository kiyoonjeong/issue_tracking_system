<?php

include "include.php";

$userid = $_SESSION["userid"];

if ($userid == 0){
    header("refresh: 0; login.php");
}

#View project
$stmt = $mysqli->prepare("select pid, pname, pdescription from project");
$stmt->execute();
$stmt->bind_result($pid, $pname, $pdescription);

echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> On Going Project </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
echo "* Click project id to see its issues";
echo "<br>";
echo "<table border = '1'>\n";
echo "<tr>";
echo "<td>Project ID</td><td>Project Title</td><td>Description</td>";
echo "</tr>\n";
while ($stmt->fetch()) {
    $pname = htmlspecialchars($pname);
    $pdescription = htmlspecialchars($pdescription);
    echo "<tr>";
    echo "<td><a href='issue.php?pid=$pid'>$pid</a></td><td>$pname</td><td>$pdescription</td>";
    echo "</tr>\n";
}
echo "</table>\n";
$stmt->close();
echo "</div>";

#View Issues I reported
$stmt = $mysqli->prepare("select iid, ititle, idescription, currstat
from issue natural join 
(select iid, stat as currstat from track natural join
(select iid, max(timedate) as timedate from track group by iid) as a1) as p1
where userid = ?");
$stmt->bind_param("d",$userid);
$stmt->execute();
$stmt->bind_result($iid, $ititle, $idescription, $currstat);

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> Check My Issue Report </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
echo "<table border = '1'>\n";
echo "<tr>";
echo "<td>Issue ID</td><td>Title</td><td>Description</td><td>Current Status</td>";
echo "</tr>\n";
while ($stmt->fetch()) {
    $ititle = htmlspecialchars($ititle);
    $idescription = htmlspecialchars($idescription);
    $currstat = htmlspecialchars($currstat);
    echo "<tr>";
    echo "<td>$iid</td><td>$ititle</td><td>$idescription</td><td>$currstat</td>";
    echo "</tr>\n";
}
echo "</table>\n";
$stmt->close();





echo "\n";
echo '<br /><a href="main.php">Main Page</a>';

echo "</div>";
?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>View All Project</title>
</html>