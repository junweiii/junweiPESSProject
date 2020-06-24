<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Police Emergency Service System</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<?php require 'nav.php';?> 
<?php 
if (isset($_POST["btnDispatch"]))
{
require_once 'db_config.php';
	
// create database connection
$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
// check connection
if($mysqli->connect_errno)
{
die("Failed to connect to MySQL: ".$mysqli->connect_errno);
}
$patrolcarDispatched = $_POST["chkPatrolcar"]; // array of patrolcar being dispatched from post back
$numofPatrolcarDispatched = count($patrolcarDispatched);
	
// insert new incident
$incidentStatus;
if($numofPatrolcarDispatched > 0)
{
$incidentStatus='2'; // incident status to be set as Dispatched
} else
{
$incidentStatus='1'; // incident status to be set as Pending
}
	
$sql = "INSERT INTO incident (callerName, phoneNumber, incidentTypeId, incidentLocation, incidentDesc, incidentStatusId) VALUES (?, ?, ?, ?, ?, ?)";
	
if (!($stmt = $mysqli->prepare($sql)))
{
die("Prepare failed: ".$mysqli->errno);
}
	
if(!$stmt->bind_param('ssssss', $_POST['junweiCaller'], $_POST['junweiNumber'], $_POST['incidentType'], $_POST['junweiLocation'], $_POST['incidentDesc'], $incidentStatus))
{
die("Binding parameters failed: ".$stmt->errno);
}
	
if (!$stmt->execute())
{
die("Insert incident table failed: ".$stmt->errno);
}

// retrieve incident_id for the newly inserted incident
$incidentId=mysqli_insert_id($mysqli);;

//update patrolcar status table and add into dispatch table
for($i=0; $i < $numofPatrolcarDispatched; $i++)
{
// update patrol car status
$sql = "UPDATE patrolcar SET patrolcarStatusId='1' WHERE patrolcarId = ?";

if (!($stmt = $mysqli->prepare($sql)))
{
die("Prepare failed: ".$mysqli->errno);
}

if (!$stmt->bind_param('s', $patrolcarDispatched[$i]))
{
die("Binding parameters failed: ".$stmt->errno);
}

if (!$stmt->execute())
{
die("Update patrolcar_status table failed: ".$stmt->errno);
}

//insert dispatch data
$sql = "INSERT INTO dispatch (incidentId, patrolcarId, timeDispatched) VALUES (?, ?, NOW())";

if (!($stmt = $mysqli->prepare($sql)))
{
die("Prepare failed: ".$mysqli->errno);
}

if (!$stmt->bind_param('ss', $incidentId,$patrolcarDispatched[$i]))
{
die("Binding parameters failed: ".$stmt->errno);
}

if(!$stmt->execute())
{
die("Insert dispatch table failed: ".$stmt->errno);
}
}

$stmt->close();

$mysqli->close();

}

?>
	
<!--display the incident information passed from logcall.php-->
<br>	
<fieldset>
<legend>Log Call</legend>
<form name="form1" method="post" action="<?php echo htmlentities($_SERVER['PHP_SELF']);?> ">
<div class="incidentTable">
<table width="50%"  align="center" border="1" cellpadding="4" cellspacing="4">

<tr>
<td colspan="2" align="center"><strong>Incident Detail</strong></td>	
</tr>

<tr>
<td width="25%">Caller's Name :</td>
<td width="75%"><?php echo $_POST['junweiCaller']?> <input type="hidden" name="junweiCaller" id="junweiCaller" value="<?php echo $_POST['junweiCaller']?>"></td>
</tr>
	
<tr>
<td width="25%">Contact No :</td>
<td width="75%"><?php echo $_POST['junweiNumber']?> <input type="hidden" name="junweiNumber" id="junweiNumber" value="<?php echo $_POST['junweiNumber']?>"></td>
</tr>
	
<tr>
<td width="25%">Location :</td>
<td width="75%"><?php echo $_POST['junweiLocation']?> <input type="hidden" name="junweiLocation" id="junweiLocation" value="<?php echo $_POST['junweiLocation']?>"></td>
</tr>
	
<tr>
<td width="25%">Incident Type :</td>
<td width="75%"><?php echo $_POST['incidentType']?> <input type="hidden" name="incidentType" id="incidentType" value="<?php echo $_POST['incidentType']?>"></td>
</tr>

<tr>
<td width="25%">Description :</td>
<td width="75%"><?php echo $_POST['incidentDesc']?> <input type="hidden" name="incidentDesc" id="incidentDesc" value="<?php echo $_POST['incidentDesc']?>"></td>
</tr>
</div>	
</table>
</fieldset>
<?php 

require_once 'db_config.php';
	
$mysqli = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);

if($mysqli->connect_errno) 
{
die("Failed to connect to MySQL: ".$mysqli->connect_errno);
}

$sql = "SELECT patrolcarId, statusDesc FROM patrolcar JOIN patrolcar_status
ON patrolcar.patrolcarStatusId=patrolcar_status.StatusId
WHERE patrolcar.patrolcarStatusId='2' OR patrolcar.patrolcarStatusId='3'";

if (!($stmt = $mysqli->prepare($sql)))
{
die("Prepare failed: ".$mysqli->errno);
}
if (!$stmt->execute())
{
die("Cannot run SQL command: ".$stmt->errno);
}
if(!($resultset = $stmt->get_result()))
{
die("No data in resultset: ".$stmt->errno);
}
	
$patrolcarArray; // an array variable
	
while  ($row = $resultset->fetch_assoc()) 
{
$patrolcarArray[$row['patrolcarId']] = $row['statusDesc'];
}
	
$stmt->close();
	
$resultset->close();
	
$mysqli->close();
?>
		
<br>
<div class="incidentTable">
<table border="1" align="center">
<tr>
<td colspan="3" align="center"><strong>Dispatch Patrol Car Panel</strong></td>
</tr>
<?php
foreach($patrolcarArray as $key=>$value) 
{ 
?>
<tr>
<td>
<input type="checkbox" name="chkPatrolcar[]" value="<?php echo $key?>"></td>
<td><?php echo $key?></td>
<td><?php echo $value?></td>
</tr>
<?php }  ?>
<tr>
<td><input type="reset" name="btnCancel" id="btnCancel" value="Reset"></td>
<td colspan="2"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="btnDispatch" id="btnDispatch" value="Dispatch">
</td>
</tr>
</table>
</form>
</div>
<br>
<br>
<hr>
<div align="center">
<p>&copy; 2020 Jun Wei PESS System.&nbsp;All Rights Reserved.</p>
<p>Developed and Done By: Jun Wei&nbsp;&nbsp; Email me <a href="mailto:junwei10@gmail.com">junwei10@gmail.com</a> if you have any enquiries.</p>
</div>
</body>
</html>