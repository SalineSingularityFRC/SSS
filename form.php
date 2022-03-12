<!DOCTYPE html>
<html>
	<head>
		<title>Saline Singularity Attendance</title>
		<meta charset="utf-8"/>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
		<div class="link">
			
		<a href="/"> Home </a>
		<br>
		<a href="/data.php">Data</a>
	
		</div>

		<h1>Sign In & Out</h1>

		<form class="sign" action="/c/f.py">
		 <input placeholder=" First & Last Name" class = "input-form" type="text" name="name"/><br/><br/>
			<input class = "submit-button" type="submit" value="submit"/>
		</form>
		<?php
		if (count($_GET) > 0) {
			$m = $_GET["m"];
			echo "<p class=\"m\">$m</p>";
		}
		?>
		
		
	</body>
</html>
