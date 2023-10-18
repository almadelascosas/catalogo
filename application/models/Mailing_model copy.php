<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mailing_model extends CI_Model {

    public function __construct()
    {
        $this->load->library('phpmailing');
                
    }

    public function mailPedidoRecibido($pedido=array()){
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES-04.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Hemos recibido tu pedido</p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/?pedido_nro='.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Hemos recibido tu pedido #'.$pedido['pedido']['pedidos_id'].', le enviremos otro correo cuando se haya confirmado el pago.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Número de pedido</th>
                                <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_detalle_pedidos_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_detalle_producto']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_detalle_producto']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_detalle_producto_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_detalle_producto_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
                                '.$pedido['pedido']['pedidos_direccion'].'
                                '.$pedido['pedido']['municipio'].'
                                '.$pedido['pedido']['departamento'].'
                                '.$pedido['pedido']['pedidos_codigo_postal'].'
                                '.$pedido['pedido']['pedidos_telefono'].'
                                '.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
                                '.$pedido['pedido']['pedidos_direccion_envio'].'
                                '.$pedido['pedido']['municipio_envio'].'
                                '.$pedido['pedido']['departamento_envio'].'
                                '.$pedido['pedido']['pedidos_codigo_postal_envio'].'
                                '.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }
    }
    
    public function mailPedidoRecibidoPrueba($pedido=array()){
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress("softmenaca@gmail.com", $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES-04.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Hemos recibido tu pedido</p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/?pedido_nro='.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Hemos recibido tu pedido #'.$pedido['pedido']['pedidos_id'].', le enviremos otro correo cuando se haya confirmado el pago.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Número de pedido</th>
                                <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_detalle_pedidos_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_detalle_producto']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_detalle_producto']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_detalle_producto_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_detalle_producto_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
                                '.$pedido['pedido']['pedidos_direccion'].'
                                '.$pedido['pedido']['municipio'].'
                                '.$pedido['pedido']['departamento'].'
                                '.$pedido['pedido']['pedidos_codigo_postal'].'
                                '.$pedido['pedido']['pedidos_telefono'].'
                                '.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
                                '.$pedido['pedido']['pedidos_direccion_envio'].'
                                '.$pedido['pedido']['municipio_envio'].'
                                '.$pedido['pedido']['departamento_envio'].'
                                '.$pedido['pedido']['pedidos_codigo_postal_envio'].'
                                '.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }
    }

    public function mailPedidoEnviado($pedido=array()){
        
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES_1.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Tu pedido está en camino</p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/?pedido_nro='.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Tu pedido #'.$pedido['pedido']['pedidos_id'].' ha sido enviado, y se encuentra en estos momentos en camino a destino.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                    <thead style="background-color:#eaeaea;">
                    <tr style="padding:10px;background-color:#eaeaea;">
                    <th style="text-align:left;padding:10px">Número de pedido</th>
                    <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                    </tr>
                    </thead>
                    <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_detalle_pedidos_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_detalle_producto']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_detalle_producto']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_detalle_producto_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_detalle_producto_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['municipio'].'
'.$pedido['pedido']['departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['municipio_envio'].'
'.$pedido['pedido']['departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }

    }
    public function mailNuevaVenta($pedido=array()){
        if ($pedido!=array()) {

            $correos = "";
            $ids_vendedores = array(0);
            foreach ($pedido['pedidos_productos']->result_array() as $key => $value) {
                array_push($ids_vendedores,$value['pedidos_detalle_vendedor']);
            }
            
            $this->db->select("email");
            $vendedores_cons = $this->db->get("usuarios");
            $cn = 0;
            foreach ($vendedores_cons->result_array() as $key => $value) {
                $cn++;
                if ($cn==1) {
                    $correos = $value['email'];
                }else{
                    $correos .= ",".$value['email'];
                }
            }

            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($correos);
            //Set the subject line
            $mail->Subject = 'NUEVO PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>NUEVO PEDIDO - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:100px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/mail-pedido-recibido.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Nuevo Pedido Recibido, Pedido nro. '.$pedido['pedido']['pedidos_id'].'</p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'pedidos/">VER PEDIDO</a>
                    </div>
                    <h3>¡Hola tienes un nuevo pedido!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Ha llegado a tu bandeja de pedidos el siguiente pedido, te invitamos a que ingreses a tu perfil y consultes toda la información del pedido.</p>                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }       
        }
    }

    public function mailPedidoFallido($pedido=array()){
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES-03.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Tu pedido fue fallido</p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/?pedido_nro='.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Lo sentimos, tu pedido #'.$pedido['pedido']['pedidos_id'].' ha fallido.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Número de pedido</th>
                                <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_detalle_pedidos_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_detalle_producto']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_detalle_producto']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_detalle_producto_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_detalle_producto_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['municipio'].'
'.$pedido['pedido']['departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['municipio_envio'].'
'.$pedido['pedido']['departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }
    }
    public function mailPedidoPreparacion($pedido=array()){
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES-02.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Tu pedido está en preparación</p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/?pedido_nro='.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Buenas noticias!, tu pedido #'.$pedido['pedido']['pedidos_id'].' ya está en preparación, te enviaremos un correo una vez haya sido enviado.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Número de pedido</th>
                                <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_detalle_pedidos_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_detalle_producto']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_detalle_producto']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_detalle_producto_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_detalle_producto_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['municipio'].'
'.$pedido['pedido']['departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['municipio_envio'].'
'.$pedido['pedido']['departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }
    }

    /* Mail Pedidos retrasados */

    public function pedidoRetrasado($pedido=array(),$receptores = array()){
        if ($pedido!=array() && $receptores!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('info@almadelascosas.co', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('info@almadelascosas.co', 'Alma de las cosas');
            //Set who the message is to be sent to
            foreach ($receptores as $key => $value) {
                $mail->addAddress($value['correo'], $value['nombre']);
            }
            //Set the subject line
            $mail->Subject = 'RETRASO DE PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>RETRASO DE PEDIDO - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <h3>¡Hola!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">
                        Te escribimos para contarte que el pedido #'.$pedido['pedido']['pedidos_id'].' 
                        aún no se ha procesado y tiene tiempo de retraso. Si ya lo enviaste te invitamos 
                        a subir y cambiar el estado a enviado para notificar al cliente Ayúdanos a gestionarlo 
                        para que entre todos le brindemos una gran experiencia a todos los clientes. 
                        Te deseamos un gran día
                    </p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'pedidos/editar_new/'.$pedido['pedido']['pedidos_id'].'">GESTIONAR PEDIDO</a>
                    </div>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">
                        Ponte en contacto con nuestro equipo de soporte para todo lo que necesites
                    </p>
                    <div style="width:100%;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #1FD161;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="https://api.whatsapp.com/send?phone=573502045177">Contactar a soporte</a>
                    </div>

                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if ($mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }else{
            $datos['result']= 0;
            $datos['mensaje']= "Error al Enviar";
        }
        return $datos;
    }

}