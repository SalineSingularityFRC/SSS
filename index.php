<?php
$m = $_SERVER['HTTP_X_FORWARDED_FOR'];
if(strval($m) == "204.38.171.125"){
	$key = rtrim(file_get_contents("/usr/local/www/sss/key.txt"), "\n");
	setcookie("key", $key);
	header("Location: form.php");
}
?>

<!DOCTYPE html>
<html>
	<head>
	
		<title>Saline Singularity Attendance</title>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
			

			<div class="link">
			<a href="/data.php">Data</a>
			</div>

		<div class="page"> 
		<h1>Sign In & Out</h1>

		<form class = "sign" action="/c/k.py">
			 <div class = "form-text"> Key: </div>  <input placeholder=" Key" class = "input-form" type="text" name="key"/><br/><br/>
			<input class = "submit-button" type="submit" value="Submit"/>
		</form>
		</div>
	</body>
</html>
