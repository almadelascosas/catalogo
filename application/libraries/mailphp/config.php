<?php

/*
*
* Endeos, Working for You
* blog.endeos.com
*
*/

require_once('PHPMailerAutoload.php');

/*
$mail = new PHPMailer;

$mail->IsSMTP();
$mail->Host = 'almadelascosas.com';   																		
$mail->SMTPSecure = 'ssl';   
$mail->Port = 465;   
$mail->SMTPAuth = true;   
$mail->Username = 'testapi@almadelascosas.co';   
$mail->Password = '1VJ*Wn01C*F)';   
$mail->From = 'testapi@almadelascosas.co';  
$mail->FromName = 'Alma de las Cosas';   
$mail->CharSet = 'UTF-8';   
*/

$host = base_url();

$mail = new PHPMailer;
$mail->SMTPDebug = 0;
$mail->IsSMTP();

if($host==='http://pruebas.almadelascosas.lc/' || $host==='https://prevista.almadelascosas.com/'){
	/*
	$mail->Host = 'smtp.gmail.com';   																		
	$mail->SMTPSecure = 'tls';   
	$mail->Port = 587;   
	$mail->SMTPAuth = true;   
	$mail->Username = 'notificaciones@almadelascosas.com';   
	$mail->Password = 'BE2DRC%a9B&J8nQ&';   
	$mail->From = 'notificaciones@almadelascosas.com';  
	$mail->FromName = ($host==='http://pruebas.almadelascosas.lc/')?'Yepagu Pruebas - Alma de las Cosas':'Prevista - Alma de las Cosas';  ;  
	$mail->CharSet = 'UTF-8';  
	*/
	
	$mail->Host = 'prevista.almadelascosas.com';																		
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465; 
	$mail->SMTPAuth = true;  
	$mail->Username = 'info@prevista.almadelascosas.com';   
	$mail->Password = 'd==#[K69nLGL';  
	$mail->From = 'info@prevista.almadelascosas.com'; 
	$mail->FromName = ($host==='http://pruebas.almadelascosas.lc/')?'Yepagu Pruebas - Alma de las Cosas':'Prevista - Alma de las Cosas';  
	$mail->CharSet = 'UTF-8'; 
	
}else if($host==='https://code.almadelascosas.co/'){
	$mail->Host = 'almadelascosas.co';   																		
	$mail->SMTPSecure = 'tls';   
	$mail->Port = 26;   
	$mail->SMTPAuth = true;   
	$mail->Username = 'testapi@almadelascosas.co';   
	$mail->Password = 'd,v.uFQnv246';   
	$mail->From = 'testapi@almadelascosas.co';  
	$mail->FromName = 'Pruebas Code - Alma de las Cosas'; 
	$mail->CharSet = 'UTF-8';
}else if($host==='https://almadelascosas.com/'){
	/*
	$mail->Host = 'smtp.gmail.com';   																		
	$mail->SMTPSecure = 'tls';   
	$mail->Port = 587;   
	$mail->SMTPAuth = true;   
	$mail->Username = 'notificaciones@almadelascosas.com';   
	$mail->Password = 'BE2DRC%a9B&J8nQ&';   
	$mail->From = 'notificaciones@almadelascosas.com';  
	$mail->FromName = ($host==='http://pruebas.almadelascosas.lc/')?'Yepagu Pruebas - Alma de las Cosas':'Prevista - Alma de las Cosas';  ;  
	$mail->CharSet = 'UTF-8';  
	*/
	
	$mail->Host = 'prevista.almadelascosas.com';																		
	$mail->SMTPSecure = 'ssl';
	$mail->Port = 465; 
	$mail->SMTPAuth = true;  
	$mail->Username = 'info@prevista.almadelascosas.com';   
	$mail->Password = 'd==#[K69nLGL';  
	$mail->From = 'info@prevista.almadelascosas.com'; 
	$mail->FromName = 'Alma de las Cosas';  
	$mail->CharSet = 'UTF-8';  
}
?>