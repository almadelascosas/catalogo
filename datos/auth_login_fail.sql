/*
SQLyog Ultimate v11.33 (32 bit)
MySQL - 10.1.38-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `auth_login_fail` (
	`log_id` int (11),
	`username` varchar (150),
	`password` varchar (225),
	`reason` varchar (765),
	`date` date ,
	`time` time ,
	`user_agent` text ,
	`ip` varchar (150)
); 
