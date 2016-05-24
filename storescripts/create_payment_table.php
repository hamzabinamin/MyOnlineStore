<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table PAYMENT 
(
	Payment_id int(11) not null auto_increment,
	Payment_type varchar(255) not null,
	primary key(Payment_id)
	
)";

if(mysql_query($sqlCommand))
	echo "Your PAYMENT table has been created successfully.";
else
	echo "ERROR! PAYMENT table has not been created.";
	


?>