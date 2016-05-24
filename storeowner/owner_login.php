<?php

session_start(); 

if(isset($_SESSION["owner"])) 
{
	header("location:index.php"); //index page of owner.
	exit();
}
?>
<?php
// Parse the log in form if the user has filled it out and pressed "Log In"
if(isset($_POST["username"]) && isset($_POST["password"])) 
{
	$owner = preg_replace('#[^A-Za-z0-9]#i','',$_POST["username"]); //filter everything but nos and letters.
	$password = preg_replace('#[^A-Za-z0-9]#i','',$_POST["password"]); // filter everything but nos and letters.
	
	$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
	//$sql = mysql_query("select id from owner where username='$owner' AND password='$password' limit 1");
	$sql = oci_parse($conn,"SELECT OWNER_ID FROM OWNER,PERSON WHERE OWNER.PERSON_ID = PERSON.PERSON_ID AND USERNAME = '$owner' AND PASSWORD = '$password' ");
	// Make sure person exists in database.
	oci_execute($sql);
	$result = oci_fetch_all($sql,$res);
	if($result > 0)
	{
		while($row = oci_fetch_row($sql))
		{ $id = $row[0]; }	
		
	$_SESSION["owner_id"] = $id;
	$_SESSION["owner"] = $owner;
	$_SESSION["owner_password"] = $password;
	echo $id;
	echo $manager;
	echo $password;
	header("location:index.php");
	exit();
	}
	
	else
	{
		if(!empty($_POST['username'])&& !empty($_POST['password']))
		{
			echo "<script> alert('Wrong Username/Password'); </script>";
		}	
	}
}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Owner Login</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />
<script>

function checkforblank()
{
	if(document.getElementById('username').value == "")
	{
		alert('Please enter your User Name to log in');
		document.getElementById('username').style.borderColor = "red";
		return false;		
	}
	
	if(document.getElementById('password').value == "")
	{
		alert('Please enter your Password to log in');
		document.getElementById('password').style.borderColor = "red";
		return false;		
	}		
}

</script>
</head>

<body>
<div align="center" id="mainWrapper">
<?php include_once("../template_header_owner_login.php"); ?>
<div id="pageContent"><br/>
  <div align="left" style="margin-left:24px;">
    <h2>Please log in to see sales information</h2>
    <form id="form2" name="myForm" method="post" action="owner_login.php" onsubmit="return checkforblank()" enctype = "multipart/form-data">
    User Name:<br/>
    <input name="username" type="text" id="username" size="40" />
    <br/><br/>
    Password:<br/>
    <input name="password" type="password" id="password" size="40" />
    <br/>
    <br/>
    <br/>
    
    <input type="submit" name="button" id="button" value="Log In"/>
    
    </form>
    <p>&nbsp; </p>
    </div>
    <br/>
    <br/>
    <br/>
    </div>
<?php include_once("../template_footer.php"); ?>

</div>
</body>
</html>