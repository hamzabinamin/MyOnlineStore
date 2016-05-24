<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table CUSTOMER
(
	Customer_id int(11) not null auto_increment,
	Fist_name varchar(255) not null,
	Last_name varchar(255) not null,
	Email varchar(255) not null,	
	Country varchar(255) not null,	
	State varchar(255) not null,
	City varchar(255) not null,
	Postal_code varchar(255) not null,
	Address varchar(255) not null,
	Billing_address varchar(255),
	Phone varchar(255) not null,
	primary key(Customer_id)
)";

if(mysql_query($sqlCommand))
	echo "Your CUSTOMER table has been created successfully.";
else
	echo "ERROR! CUSTOMER table has not been created."
	


?>