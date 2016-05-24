<?php
// help you have persistent data, if they aren't loggedin then session variable won't be created then we'll know they are not allowed to be here.
session_start(); 
if(!isset($_SESSION["owner"])) 
{
	header("location:owner_login.php"); // will not execute if loggedin.
	exit();
}
// Be sure to check that this owner SESSION value is in fact in the database cuz people using firefox etc can create session cookies out of thin air and forge things on login type forms and can see the links.
$ownerID = preg_replace('#[^0-9]#i','',$_SESSION["owner_id"]); // filter everything but nos and letters
$owner = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["owner"]); // filter everything but nos and letters
$password = preg_replace('#[^A-Za-z0-9]#i','',$_SESSION["owner_password"]); // filter everything but nos and letters

$conn = oci_connect("Hamza", "1234", 'localhost/orcl'); 
$sql = oci_parse($conn,"Select * from owner o,person p where o.person_id = p.person_id AND username='$owner' AND password='$password'");
// Make sure person exists in the database.
oci_execute($sql);
$result = oci_fetch_all($sql, $res);
//$existCount = mysql_num_rows($sql); // count the row nums.
if($result == 0)
{
	echo "Your login session data is not on record in the database.";
	//session_destroy();
	//header("location:../index.php");
	exit();	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Owner Area</title>
<link rel="stylesheet" href="../style/style.css" type="text/css" media="screen" />

</head>

<body>
<div align="center" id="mainWrapper">
<?php include_once("../template_header_owner.php"); ?>
<div id="pageContent"><br/>
<div align="right" style="margin-right:32px"></div>
  <div align="left" style="margin-left:24px;">
    <h2>Hello owner, what would you like to do today?</h2>
    <p><a href="sales_record.php">View Sales Record</a>  </p>
  </div>
  <br/>
<br/>
<br/>
</div>
<?php include_once("../template_footer.php");  ?>

</div>
</body>
</html>