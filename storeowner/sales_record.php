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
  exit(); 
}

$dayQuantity = "";
$weekQuantity = "";
$monthQuantity = "";

$sql = oci_parse($conn,"Select sum(quantity)from orders o,product p,order2product op where o.order_id = op.order_id AND op.product_id = p.product_id AND trunc(order_date) BETWEEN to_date(SYSDATE - 1) AND to_date(SYSDATE)");

//$existCount = mysql_num_rows($sql); // count the row nums.
if(oci_execute($sql))
{
  while($row = oci_fetch_row($sql))
  {
    $dayQuantity = $row[0]; 
  }
}

$sql = oci_parse($conn,"Select sum(quantity)from orders o,product p,order2product op where o.order_id = op.order_id AND op.product_id = p.product_id AND trunc(order_date) BETWEEN to_date(SYSDATE - 7) AND to_date(SYSDATE)");

//$existCount = mysql_num_rows($sql); // count the row nums.
if(oci_execute($sql))
{
  while($row = oci_fetch_row($sql))
  {
    $weekQuantity = $row[0];
  } 
}

$sql = oci_parse($conn,"Select sum(quantity)from orders o,product p,order2product op where o.order_id = op.order_id AND op.product_id = p.product_id AND trunc(order_date) BETWEEN to_date(SYSDATE - 30) AND to_date(SYSDATE)");;

//$existCount = mysql_num_rows($sql); // count the row nums.
if(oci_execute($sql))
{
  while($row = oci_fetch_row($sql))
  {
    $monthQuantity = $row[0];
  } 
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
     <h2>Products Sales Record    </h2>
    <table width="510" border="1" cellpadding="6" cellspacing="0">
      <tr>
        <td width="145" align="center" bgcolor="#DAF1FE"><strong>Sales made in a Day</strong></td>
        <td width="158" align="center" bgcolor="#DAF1FE"><strong>Sales made in a Week</strong></td>
        <td width="163" align="center" bgcolor="#DAF1FE"><strong>Sales made in a Month</strong></td>
      </tr>
      <tr>
        <td align="center"><?php if ($dayQuantity != "" ) echo $dayQuantity; else echo '0';  ?></td>
        <td align="center"><?php if ($weekQuantity != "" ) echo $weekQuantity; else echo '0';  ?></td>
        <td align="center"><?php if ($monthQuantity != "" ) echo $monthQuantity; else echo '0';  ?></td>
      </tr>
    </table>
    <p>&nbsp;</p>
  </div>
  <br/>
<br/>
<br/>
</div>
<?php include_once("../template_footer.php");  ?>

</div>
</body>
</html>