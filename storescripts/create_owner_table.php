create table Owner
(
   id int(11) not null auto_increment,
   username varchar(64) not null,
   password varchar(64) not null,
   last_login_date date not null 
);