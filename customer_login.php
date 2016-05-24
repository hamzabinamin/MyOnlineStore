<?php

session_start(); 

if(isset($_SESSION["customer"])) 
{
	header("location:index.php"); //index page of admin.
	exit();
}

?>
<?php
// Parse the login form if the user has filled it out and pressed "Log In"
if(isset($_POST["username"]) && isset($_POST["password"])) 
{
	$customer_username = preg_replace('#[^A-Za-z0-9]#i','',$_POST["username"]); //filter everything but nos and letters.
	$customer_password = preg_replace('#[^A-Za-z0-9]#i','',$_POST["password"]); // filter everything but nos and letters.
	
	$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
	$sql = oci_parse($conn,"select Customer_id from customer,person where username='$customer_username' AND password='$customer_password' ");
	// Make sure person exists in database.
	//$existCount = mysql_num_rows($sql); // count the row nums.
	oci_execute($sql);
	$result = oci_fetch_all($sql, $res);

	if($result > 0)
	{
		while($row = oci_fetch_array($sql))
		{ $customer_id = $row["Customer_id"]; }	
		
	$_SESSION["customer_id"] = $customer_id;
	$_SESSION["customer_username"] = $customer_username;
	$_SESSION["customer_password"] = $customer_password;
	echo $customer_id;
	echo $customer_username;
	echo $customer_passowrd;
	header("location:index.php");
	exit();
	}
	else
	{
		echo 'That information is incorrect, try again <a href="customer_login.php">Click Here</a>';	
		exit();
	}
}
?>	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Customer Login</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><br/>
  <div align="left" style="margin-left:24px;">
    <h2>Please log in to buy stuff</h2>
    <form id="form3" name="form1" method="post" action="customer_login.php">
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
<?php include_once("template_footer.php"); ?>

</div>
</body>
</html>