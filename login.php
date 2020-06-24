<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Police Emergency Login Page</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
	
<script>
	
function validate()
{
var jwUsername=document.forms["LoginForm"]["jwUsername"].value;
	
if (!jwUsername) || (jwUsername == "")
{
alert("Username is Required");
return false;
}
	
	
}
	
</script>	
	
<?php
session_start();

$username = "admin";
$password = "admin";


if (isset($_SESSION['loggedin']) && $SESSION['loggedin'] == true)
{
header("Location: logcall.php");
}
	
if (isset($_POST['admin']) && isset($_POST['admin']))
{
if ($_POST['admin'] == $username && $_POST['admin' == $password])
{
$_SESSION['loggedin'] = true;
header("Location: logcall.php");
}
	
else
{
alert("Incorrect Username and Password");

}
	
}
?>
<body>
<form name="LoginForm" method ="post" action="logcall.php">
Username:
<input type="text" name="jwUsername"><br><br>
Password:
<input type="password" name="jwPassword"><br/>
<br>
<input type="submit" value="Login"> <input type="reset" value="Reset" onSubmit="return validate();">
</form>
</body>
</html>
