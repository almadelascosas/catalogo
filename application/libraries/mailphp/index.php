<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/mailphp/config.php');
$emails = array(
    "softmenaca@gmail.com",
    "jcslr99@gmail.com",
    "eahchenriquez15@gmail.com",
);

for ($i=0; $i < count($emails); $i++) {
    $mail->ClearAllRecipients( );
    $mail->AddAddress($emails[$i]);
    $mail->IsHTML(true);  //podemos activar o desactivar HTML en mensaje
    $mail->Subject = 'asunto del mensaje prueba varios';
    
    $msg = "<h2>Contenido mensaje HTML:</h2>
    <p>Contenido</p>
    <p>Más Contenido...</p>
    ";
    
    $mail->Body    = $msg;
    $mail->Send();
}

?>