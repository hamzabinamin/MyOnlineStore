<?php
// help you have persistent data, if they aren't loggedin then session variable won't be created then we'll know they are not allowed to be here.
session_start(); 
/*
// Be sure to check that this customer SESSION value is in fact in the database cuz people using firefox etc can create session cookies out of thin air and forge things on login type forms and can see the links.
$managerID = preg_replace('#[^0-9]#i','',$_SESSION["id"]); // filter everything but nos and letters
$manager = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["manager"]); // filter everything but nos and letters
$password = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["password"]); // filter everything but nos and letters
*/
include "storescripts/connect_to_mysql.php";
//$sql = mysql_query("Select * from customer where id='$managerID' AND username='$manager' AND password='$password' LIMIT 1");
// Make sure person exists in the database.
/*
$existCount = mysql_num_rows($sql); // count the row nums.
if(!$existCount == 0)
{
	echo "Your login session data is not on record in the database.";
	//session_destroy();
	//header("location:../index.php");
	exit();	
}
*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Contact Us</title>
<link rel="stylesheet" href="style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php  if(isset($_SESSION["customer_username"])) { include_once("template_header_logout_account.php"); } else { include_once("template_header_login_account.php"); } ?>
<div id="pageContent"><br/>
  <div align="left" style="margin-left:24px;">
    <h2>Contact Us</h2>
    <p>E-mail: store@gmail.com</p>
    <p>Phone: +41-56346</p>
    <p>Location: Hogwarts School of Witchcraft and Wizardry </p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
    <p>&nbsp;</p>
</div>
  <br/>
<br/>
<br/>
</div>
<?php include_once("template_footer.php");  ?>

</div>
</body>
</html>