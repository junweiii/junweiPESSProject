<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Police Emergency Service System</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>	
<script>
function validate()
{
var junweiCaller=document.forms["LogCall"]["junweiCaller"].value;
var junweiNumber=document.forms["LogCall"]["junweiNumber"].value;
var firstNumber= junweiNumber[0];
var junweiLocation=document.forms["LogCall"]["junweiLocation"].value;
var incidentDesc=document.forms["LogCall"]["incidentDesc"].value;	

if (!junweiCaller || junweiCaller == ""	)
{
alert("Caller Name is Required.");	
document.getElementById("junweiCaller").focus();
return false;
}
	
else 
{
if (!isNaN(junweiCaller))
{
alert("Only Characters are allowed");
document.getElementById("junweiCaller").focus();
return false;
}
}
		
if (!junweiNumber || junweiNumber == "")
{
alert("Contact Number is Required.");
document.getElementById("junweiNumber").focus();
return false;
}

else
{
if(isNaN(junweiNumber))
{
alert("Number only.");
document.getElementById("junweiNumber").focus();
return false; 
}

if((firstNumber != 6 && firstNumber != 8 && firstNumber != 9) || junweiNumber.length != 8)
{
alert("Number only can start with 6 , 8 and 9! \n 8 Numbers only");
document.getElementById("junweiNumber").focus();
return false;
}

if (!junweiLocation || junweiLocation=="")
{
alert("Location is Required.");
document.getElementById("junweiLocation").focus();
return false;
}

if (!incidentDesc || incidentDesc=="")
{
alert("Description is Required.");
document.getElementById("incidentDesc").focus();
return false;
}

} //end of else

} //end of function
</script>
<?php $page = 'logcall'; require 'nav.php';?>
<?php require 'db_config.php';

//create database connection
$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
	
if ($mysqli->connect_errno)
{
	die("Failed to connect to MYSQL: ".$mysqli->connect_errno);
}
	
$sql = "SELECT * FROM incidenttype";
	
if(!($stmt = $mysqli->prepare($sql)))
{
	die("Failed to run SQL Command: ".$mysqli->errno);
}
	
if (!$stmt->execute())
{
	die("Getting result set failed: ".$stmt->errno);
}
	
if (!($resultset = $stmt->get_result()))
{
	die("No data found in resultset: ".$stmt->errno);
}
	
$incidentType; //an array variable
	
while ($row = $resultset->fetch_assoc())
{
	$incidentType[$row['incidentTypeId']] = $row['incidentTypeDesc'];
}
	
$stmt->close();

$resultset->close();
	
$mysqli->close();
		
?>
<br>
<fieldset>
<legend>Caller's Information:</legend>	
<form name="LogCall" method="post" action="dispatch.php" onSubmit="return validate();">
<div class="callerTable">
<table width="50%" border="1" align="center" cellpadding="5" cellspacing"5">
<tr>
<td width="50">Caller's Name:</td>
<td width="50"><input type="text" name="junweiCaller" id="junweiCaller" size="20"></td>
</tr>
<tr>
<td width="50">Contact No:</td>
<td width="50"><input type="text" name="junweiNumber" id="junweiNumber" size="20"></td>
</tr>
<tr>
<td width="50">Location:</td>
<td width="50"><input type="text" name="junweiLocation" id="junweiLocation"></td>
</tr>
<tr>
<td width="50">Incident Type:</td>
<td width="50"><select name="incidentType" id="incidentType">
<?php foreach($incidentType as $key=> $value) {?>
<option value="<?php echo $key?>">
<?php echo $value ?></option>
<?php }?>
</select>
</td>
</tr>
<tr>
<td width="50">Description:</td>
<td width="50"><textarea name="incidentDesc" id="incidentDesc" cols="45" rows="5"></textarea></td>
</tr>
</table>
</div>
<br>
<div align="center">
<input type="submit" name="submitButton" id="submitButton" value="Process">
<input type="reset" name="resetButton" id="resetButton" value="Reset">
</div>
</form>		
</fieldset>
<br>
<br>
<hr>
<div align="center">
<p>&copy; 2020 Jun Wei PESS System.&nbsp;&nbsp;All Rights Reserved.</p>
<p>Developed and Done By: Jun Wei&nbsp;&nbsp; Email me <a href="mailto:junwei10@gmail.com">junwei10@gmail.com</a> if you have any enquiries.</p>
</div>
</body>
</html>
