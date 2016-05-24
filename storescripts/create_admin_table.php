<?php

require "connect_to_mysql.php";

$sqlCommand = "Create table admin 
(
	id int(11) not null auto_increment,
	username varchar(255) not null,
	password varchar(255) not null,
	last_login_date date not null,
	primary key(id),
	unique key username(username)
)";

if(mysql_query($sqlCommand))
	echo "Your admin table has been created successfully.";
else
	echo "ERROR! Admin table has not been created."
	


?>