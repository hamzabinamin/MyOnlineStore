<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table CATEGORY 
(
	Category_id int(11) not null auto_increment,
	Category_name varchar(255) not null,
	primary key(Category_id),
	unique key Product_name(Category_name)
)";

if(mysql_query($sqlCommand))
	echo "Your CATEGORY table has been created successfully.";
else
	echo "ERROR! CATEGORY table has not been created.";
	


?>