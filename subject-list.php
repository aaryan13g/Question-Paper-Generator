<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subject List</title>
    <link rel="stylesheet" href="newcss.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 400px; padding: 20px; }
        td{ padding:0 30px 0 30px;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2><b>AVAILABLE SUBJECT LIST</b></h2>

			<?php
			require_once "config.php";
			$query = "SELECT * FROM topics";
			$result = mysqli_query($link,$query);

			echo "<table border = 1>";

			while($row = mysqli_fetch_array($result)){   //Creates a loop to loop through results
			echo "<tr><td>" . $row['subject_code'] . "</td><td>" . $row['subject_name'] . "</td></tr>";  //$row['index'] the index here is a field name
			}

			echo "</table>";

			mysqli_close($link);
			?>

	</div>    
</body>
</html>