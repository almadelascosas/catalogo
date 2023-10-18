/*
SQLyog Ultimate v11.33 (32 bit)
MySQL - 10.1.38-MariaDB 
*********************************************************************
*/
/*!40101 SET NAMES utf8 */;

create table `auth_login_success` (
	`log_id` int (11),
	`user_id` int (11),
	`date` date ,
	`time` time ,
	`user_agent` text ,
	`ip` varchar (150)
); 
