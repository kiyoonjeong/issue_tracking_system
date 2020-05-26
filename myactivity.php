<?php

include "include.php";

$userid = $_SESSION['userid'];
echo "<div style = 'font-size:30px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'> My Activity Log </div>";
echo "<div style = 'font-size:15px; font-family: Comic Sans MS, cursive, serif; color: #1F3B34;'>";
echo "<br>";
echo "<br>";
if ($stmt = $mysqli->prepare("select timedate, actions from activity where userid = ?")) {
    $stmt->bind_param("d", $userid);
    $stmt->execute();
    $stmt->bind_result($timedate, $actions);
    echo "<table border = '1'>\n";
    echo "<tr>";
    echo "<td>Date and Time</td><td>Activity</td>";
    echo "</tr>\n";
    while ($stmt->fetch()) {
        echo "<tr>";
        echo "<td>$timedate</td>";
        if ($actions == 'Add Leader'){
            echo "<td><font color = 'E54B4B'> $actions </font></td>";
        }
        else if ($actions == 'Create New Project'){
            echo "<td><font color = '167C80'> $actions </font></td>";
        }
        else if ($actions == 'Initiate Workflow'){
            echo "<td><font color = '32B67A'> $actions </font></td>";
        }
        else if ($actions == 'Add Workflow'){
            echo "<td><font color = '371722'> $actions </font></td>";
        }
        else if ($actions == 'Delete Workflow'){
            echo "<td><font color = '055A5B'> $actions </font></td>";
        }
        else if ($actions == 'Report Issue'){
            echo "<td><font color = '4F3A4B'> $actions </font></td>";
        }
        else if ($actions == 'Change Issue Status'){
            echo "<td><font color = 'FB9B2A'> $actions </font></td>";
        }
        else if ($actions == 'Add Assignee'){
            echo "<td><font color = '12162D'> $actions </font></td>";
        }
        else if ($actions == 'Add Leader'){
            echo "<td><font color = '0C485E'> $actions </font></td>";
        }
        else{
            echo "<td> $actions </td>";
        }
        
        echo "</tr>\n";
    }
    echo "</table>\n";

    $stmt->close();
}


echo "\n";
echo '<br /><a href="main.php">Main Page</a>';
echo "</div>";
?>

<!DOCTYPE html>
<!-- Example Blog written by Raymond Mui -->
<html>
<body style = "background-color:#F8EBEE">
</body>
<title>My Activity</title>
</html>