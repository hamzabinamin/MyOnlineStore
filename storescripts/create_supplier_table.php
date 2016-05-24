<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table supplier
(
	Supplier_id int(11) not null auto_increment,
	Company_name varchar(255) not null,
	Email varchar(255) not null,
	Country varchar(255) not null,
	State varchar(255) not null,
	City varchar(255) not null,
	Postal_code varchar(255) not null,
	Address varchar(255) not null,
	Phone varchar(255) not null,
	primary key(Supplier_id),
	unique key Product_name(Company_name)
)";

if(mysql_query($sqlCommand))
	echo "Your supplier table has been created successfully.";
else
	echo "ERROR! supplier table has not been created.";
	


?>