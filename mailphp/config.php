<?php

/*
*
* Endeos, Working for You
* blog.endeos.com
*
*/

require_once('PHPMailerAutoload.php');


$mail = new PHPMailer;

//$mail->SMTPDebug    = 3;

$mail->IsSMTP();
$mail->Host = 'smtp.sendgrid.net';   /*Servidor SMTP*/																		
$mail->SMTPSecure = '';   /*Protocolo SSL o TLS*/
$mail->Port = 25;   /*Puerto de conexión al servidor SMTP*/
$mail->SMTPAuth = true;   /*Para habilitar o deshabilitar la autenticación*/
$mail->Username = 'apikey';   /*Usuario, normalmente el correo electrónico*/
$mail->Password = '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';   /*Tu contraseña*/
$mail->From = 'admin@almadelascosas.com';   /*Correo electrónico que estamos autenticando*/
$mail->FromName = 'Alma de las Cosas';   /*Puedes poner tu nombre, el de tu empresa, nombre de tu web, etc.*/
$mail->CharSet = 'UTF-8';   /*Codificación del mensaje*/

?>