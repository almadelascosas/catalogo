<?php
$precioTotal = 0;
$productosMostrar = array();
$total=0;
$total_envio=0;
$agregado = array();
if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
    if (count($pedido["productos"]) > 0) {
        foreach ($pedido["pedidos_productos"] as $key2 => $value2) {
            if ($value2['pedidos_detalle_pedidos_id']==$pedido['pedido']['pedidos_id']) {
                $agregado[$key2] = 0;
                foreach ($pedido["productos"] as $key3 => $value3) {
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
                            $image=image($value3['medios_url']);
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
                            $image=image($value3['medios_url']);
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
?>
<main class="col-12 cuerpo finalizar-compra float-left">
    <div class="row">
        <div class="col-12 pt-5">
            <div class="row">
                <?php
                if ($pedido['pedido']['pedidos_metodo_pago']=="pse" || $pedido['pedido']['pedidos_metodo_pago']==4) { 
                    $vad_trans_date = gmdate("YmdHis");
                    $vad_trans_id = date("his");
                    $vads = array(
                        "vads_action_mode" => "INTERACTIVE",
                        "vads_amount" => $pedido['pedido']['pedidos_precio_total']."00",
                        //"vads_ctx_mode" => "PRODUCTION",
                        "vads_ctx_mode" => "TEST",
                        "vads_currency" => "170",
                        "vads_cust_country" => "CO",
                        "vads_cust_email" => $pedido['pedido']['pedidos_correo'],
                        "vads_cust_first_name" => $pedido['pedido']['pedidos_nombre'],
                        "vads_cust_last_name" => "",
                        "vads_cust_phone" => $pedido['pedido']['pedidos_telefono'],
                        "vads_page_action" => "PAYMENT",
                        "vads_payment_cards" => "PSE",
                        "vads_payment_config" => "SINGLE",
                        "vads_site_id" => "80379999",
                        "vads_trans_date" => $vad_trans_date,
                        "vads_trans_id" => $vad_trans_id,
                        "vads_url_success" => base_url('checkout/thanks/exitoso/pedido-'.$pedido['pedido']['pedidos_id']),                        
                        "vads_url_refused" => base_url('checkout/thanks/rechazado/pedido-'.$pedido['pedido']['pedidos_id']),
                        "vads_url_cancel" => base_url('checkout/thanks/cancelado/pedido-'.$pedido['pedido']['pedidos_id']),
                        "vads_url_error" => base_url('checkout/thanks/error/pedido-'.$pedido['pedido']['pedidos_id']),
                        "vads_version" => "V2"
                    );
                    $signature = getSignature($vads,"OEOBrffArdHWSSWg"); // PRUEBA
                    //$signature = getSignature($vads,"WZhiqh8mYRheu8uV"); // PRODUCCIÓN
                    ?>
                    <form action="https://secure.payzen.lat/vads-payment/" method="post" class="col-lg-8 offset-lg-2 col-md-6 card-metodo-pago form-checkout offset-md-3 col-12 mt-5">
                        <?php
                            $nropedido = $pedido['pedido']['pedidos_id'].rand(9,9999);
                        ?>
                        <input type="hidden" name="vads_action_mode" value="INTERACTIVE" /> 
                        <input type="hidden" name="vads_amount" value="<?=$pedido['pedido']['pedidos_precio_total']."00"?>" /> 
                        <!-- <input type="hidden" name="vads_ctx_mode" value="PRODUCTION" /> --> 
                        <input type="hidden" name="vads_ctx_mode" value="TEST" /> 
                        <input type="hidden" name="vads_currency" value="170" />
                        <input type="hidden" name="vads_cust_country" value="CO" />
                        <input type="hidden" name="vads_cust_email" value="<?=$pedido['pedido']['pedidos_correo']?>" />
                        <input type="hidden" name="vads_cust_first_name" value="<?=$pedido['pedido']['pedidos_nombre']?>" />
                        <input type="hidden" name="vads_cust_last_name" value="" />
                        <input type="hidden" name="vads_cust_phone" value="<?=$pedido['pedido']['pedidos_telefono']?>" />
                        <input type="hidden" name="vads_page_action" value="PAYMENT" />
                        <input type="hidden" name="vads_payment_cards" value="PSE" /> 
                        <input type="hidden" name="vads_payment_config" value="SINGLE" /> 
                        <input type="hidden" name="vads_site_id" value="80379999" /> 
                        <input type="hidden" name="vads_trans_date" value="<?=$vad_trans_date?>" /> 
                        <input type="hidden" name="vads_trans_id" value="<?=$vad_trans_id?>" /> 
                        <input type="hidden" name="vads_url_success" value="<?=base_url('checkout/thanks/exitoso/pedido-'.$pedido['pedido']['pedidos_id'])?>" />
                        <input type="hidden" name="vads_url_refused" value="<?=base_url('checkout/thanks/rechazado/pedido-'.$pedido['pedido']['pedidos_id'])?>" />
                        <input type="hidden" name="vads_url_cancel" value="<?=base_url('checkout/thanks/cancelado/pedido-'.$pedido['pedido']['pedidos_id'])?>" />
                        <input type="hidden" name="vads_url_error" value="<?=base_url('checkout/thanks/error/pedido-'.$pedido['pedido']['pedidos_id'])?>" />
                        <input type="hidden" name="vads_version" value="V2" />
                        <input type="hidden" name="signature" value="<?=$signature?>"/>
                        <div class="row">
                            <div class="col-md-6 col-12 d-md-block d-none">
                                <div class="col-12 my-4 border-right">
                                    <div class="col-12 py-4 text-center">
                                        <img class="mx-auto" src="<?=base_url()?>assets/img/methods/pse_nuevo.png" alt="PSE">
                                    </div>
                                    <div class="col-12 mt-5">
                                        <p>Ha seleccionado el método de pago PSE, para ir a pagar favor dar clic en el botón continuar.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="col-12 my-4">
                                    <div class="col-12">
                                        <p class="mb-4 bold"><strong>Datos del pedido</strong></p>
                                        <p class="d-inline-flex w-100 border-bottom mb-3 pb-3">
                                            <span class="w-50">
                                                Número del pedido:
                                            </span>
                                            <strong class="text-right w-50">#<?=$pedido['pedido']['pedidos_id']?></strong>
                                        </p>
                                        <p class="d-inline-flex w-100 border-bottom mb-3 pb-3">
                                            <span class="w-50">
                                                Fecha:
                                            </span>
                                            <strong class="text-right w-50"><?=$pedido['pedido']['pedidos_fecha_creacion']?></strong>
                                        </p>
                                        <p class="d-inline-flex w-100 border-bottom mb-3 pb-3">
                                            <span class="w-50">
                                                Total:
                                            </span>
                                            <strong class="text-right w-50"><?=number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.')?></strong>
                                        </p>
                                        <p class="d-inline-flex w-100  mb-3 pb-3">
                                            <span class="w-50">
                                                Método de pago:
                                            </span>
                                            <strong class="text-right w-50">PSE - Payzen</strong>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 d-md-none">
                                <div class="col-12 mb-3 border-right">
                                    <div class="col-12">
                                        <p>Ha seleccionado el método de pago PSE, para ir a pagar favor dar clic en el botón continuar.</p>
                                    </div>
                                    <div class="col-12 py-3 text-center">
                                        <img class="mx-auto" style="width:45px;" src="<?=base_url()?>assets/img/methods/pse_nuevo.png" alt="PSE">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 text-center my-4">
                                <button type="submit" name="pagar" class="btn btn-green-alma text-white">
                                    Continuar con PSE
                                </button>
                            </div>
                        </div>
                    </form>
                <?php
                }
                if ($pedido['pedido']['pedidos_metodo_pago']=="Transferencia" || $pedido['pedido']['pedidos_metodo_pago']==0) { ?>
                <div class="col-12 col-md-10 offset-md-1 px-md-5">
                    <h5 class="check text-center">Gracias. Tu pedido ha sido recibido.</h5>
                    <h5 class="h5-banco">Nuestros detalles bancarios</h5>
                    <h5 class="h5-banco">Cuenta de ahorros:</h5>
                    <ul>
                        <li>Banco: <strong>Bancolombia</strong></li>
                        <li>Número de cuenta: <strong>02900003422</strong></li>
                        <li>Titular de cuenta: <strong>ALMA DE COLOMBIA S.A.S</strong></li>
                        <li>NIT: <strong>901450303</strong></li>
                    </ul>
                    <p>Una vez realices la transferencia del importe de la compra, envianos una foto del comprobante de pago al correo <a class="color-default" href="mailto:info@almadelascosas.com">info@almadelascosas.com</a> o al Whatsapp <a class="color-primary" href="https://wa.me/573502045177">+573502045177</a></p>
                </div>
                <div class="col-12 bg-gray py-4 mt-4 mb-5">
                    <ul class="list-order-resume">
                        <li class="order">
                            Número del pedido:					<br><strong><?=$pedido['pedido']['pedidos_id']?></strong>
                        </li>
                        <li class="date">
                            Fecha:					<br><strong><?=$pedido['pedido']['pedidos_fecha_creacion']?></strong>
                        </li>
                        <li class="email">
                            Email:						<br><strong><?=$pedido['pedido']['pedidos_correo']?></strong>
                        </li>
                        <li class="total">
                            Total:					<br><strong><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol">$</span>&nbsp;<?=number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.')?></bdi></span></strong>
                        </li>
                        <li class="method">
                            Método de pago:						<br><strong>Transferencia bancaria directa</strong>
                        </li>
                    </ul>
                </div>

                <div class="col-12 col-md-10 offset-md-1">
                    <h5 class="h5-banco">Detalles del pedido</h5>
                    <?php
                    $cuerpo = '
                    <table class="table table-striped">
                    <thead>
                    <tr>
                    <th>Número de pedido</th>
                    <th>#'.$pedido['pedido']['pedidos_id'].'</th>
                    </tr>
                    </thead>
                    <tbody>';
                        /* Pixel */
                        $content_ids = "";
                        $contents = "";
                        $num_items = 0;
                        $value_pixel = 0;
                        $cont = 0;
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cont++;
                            $num_items = $num_items+floatval($value4['productos_cantidad']);
                            $value_pixel = $value_pixel+floatval($value4['productos_precio']);
                            if ($cont==1) {
                                $content_ids = "'".$value4['productos_id']."'";
                                $contents = "{'id': '".$value4['productos_id']."', 'quantity':".$value4['productos_cantidad']."}";
                            }else{
                                $content_ids .= ",'".$value4['productos_id']."'";
                                $contents .= ",{'id': '".$value4['productos_id']."', 'quantity':".$value4['productos_cantidad']."}";
                            }
                            $cuerpo.='
                                <tr>
                                    <td>';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td>$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr>
                                <td>Valor del envío</td>
                                <td>$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Dirección de facturación</th>
                                <th>Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['municipio'].'
'.$pedido['pedido']['departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
<td><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['municipio_envio'].'
'.$pedido['pedido']['departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>';
                    echo $cuerpo;
                    ?>
                </div>
                <script type="text/javascript">
                    fbq('track', 'Purchase', {content_ids:[<?=$content_ids?>],contents:[<?=$contents?>],num_items:<?=$num_items?>,currency:'COP',value:<?=$value_pixel?>});
                </script>
                <?php
                }elseif ($pedido['pedido']['pedidos_metodo_pago']=="PayU Latam" || $pedido['pedido']['pedidos_metodo_pago']==2) {
                ?>
                <form action="https://checkout.payulatam.com/ppp-web-gateway-payu/" method="post" class="col-md-10 form-checkout offset-md-1 col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-resumen-cart py-4 px-2">
                                <div class="col-12">
                                    <ul>
                                        <li>Número del pedido: <strong>#<?=$pedido['pedido']['pedidos_id']?></strong></li>
                                        <li>Fecha: <strong><?=$pedido['pedido']['pedidos_fecha_creacion']?></strong></li>
                                        <li>Total: <strong><?=number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.')?></strong></li>
                                        <li>Método de pago: <strong>PayU Latam</strong></li>
                                    </ul>
                                    <p>Gracias por su pedido, de clic en el botón que aparece para continuar el pago con PayU Latam.</p>
                                    <?php
                                    $nropedido = $pedido['pedido']['pedidos_id'].rand(9,9999);
                                    ?>
                                    <hr>
                                    <input name="merchantId"      type="hidden"  value="813335"   >
                                    <input name="accountId"       type="hidden"  value="820452" >
                                    <input name="description"     type="hidden"  value="Pedido Alma de las Cosas"  >
                                    <input name="referenceCode"   type="hidden"  value="Alma<?=$nropedido?>" >
                                    <input name="amount"          type="hidden"  value="<?=$pedido['pedido']['pedidos_precio_total']?>"   >
                                    <input name="currency"        type="hidden"  value="COP" >
                                    <input name="signature"       type="hidden"  value="<?=md5("sYD80Q3Gcs7Bylg126618zWJq2~813335~Alma".$nropedido."~".$pedido['pedido']['pedidos_precio_total']."~COP")?>"  >
                                    <input name="test"            type="hidden"  value="0" >
                                    <input name="tax"             type="hidden"  value="0"  >
                                    <input name="extra1"          type="hidden"  value="<?=$pedido['pedido']["pedidos_id"]?>"  >
                                    <input name="taxReturnBase"   type="hidden"  value="0" >
                                    <input name="buyerEmail"      type="hidden"  value="<?=$pedido['pedido']['pedidos_correo']?>" >
                                    <input name="responseUrl"     type="hidden"  value="<?=base_url('checkout/thanks')?>" >
                                    <input name="confirmationUrl" type="hidden"  value="<?=base_url('checkout/confirmationUrl')?>" >

                                    <button type="submit" class="btn btn-default uppercase">Realizar el pedido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
                }elseif ($pedido['pedido']['pedidos_metodo_pago']=="MercadoPago" || $pedido['pedido']['pedidos_metodo_pago']==3) {
                ?>
                <div class="col-md-10 form-checkout offset-md-1 col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-resumen-cart py-4 px-2">
                                <div class="col-12">
                                    <ul>
                                        <li>Número del pedido: <strong>#<?=$pedido['pedido']['pedidos_id']?></strong></li>
                                        <li>Fecha: <strong><?=$pedido['pedido']['pedidos_fecha_creacion']?></strong></li>
                                        <li>Total: <strong><?=number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.')?></strong></li>
                                        <li>Método de pago: <strong>Mercado Pago</strong></li>
                                    </ul>
                                    <p>Gracias por su pedido, de clic en el botón que aparece para continuar el pago con Mercado Pago.</p>
                                    <?php
                                    $nropedido = $pedido['pedido']['pedidos_id'];


                                    require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/mercadopago/vendor/autoload.php';  

                                    $domain=base_url();
                                    switch($domain){
                                        case 'http://pruebas.almadelascosas.lc/':
                                        case 'https://code.almadelascosas.co/':
                                        case 'https://prevista.almadelascosas.com/':
                                            // PRUEBAS
                                            //MercadoPago\SDK::setAccessToken("TEST-737037781546252-072613-6ec8668d0de2667b89832928644e31a0-1166475224"); //Alma de Colombia
                                            MercadoPago\SDK::setAccessToken("TEST-6659308616457904-012410-c38cbf781a96da71b71616c5cd332cb2-173200436"); //Y3p4gu
                                            break;
                                        case 'https://almadelascosas.com/':
                                            // PRODUCTION
                                            MercadoPago\SDK::setAccessToken("APP_USR-737037781546252-072613-81cac146ff52e9ee8c693a407ab495b3-1166475224");
                                            break;
                                    }                                    

                                    # Crear un boton de pago a partir de una preferencia con atributos requeridos minimos
                                    $preference = new MercadoPago\Preference();
                                    /* Pixel */
                                    $content_ids = "";
                                    $contents = "";
                                    $num_items = 0;
                                    $value_pixel = 0;
                                    $cont = 0;
                                    
                                    $items = array();
                                    foreach ($productosMostrar as $key4 => $value4) {
                                        $cont++;
                                        $num_items = $num_items+floatval($value4['productos_cantidad']);
                                        $value_pixel = $value_pixel+floatval($value4['productos_precio']);
                                        if ($cont==1) {
                                            $content_ids = "'".$value4['productos_id']."'";
                                            $contents = "{'id': '".$value4['productos_id']."', 'quantity':".$value4['productos_cantidad']."}";
                                        }else{
                                            $content_ids .= ",'".$value4['productos_id']."'";
                                            $contents .= ",{'id': '".$value4['productos_id']."', 'quantity':".$value4['productos_cantidad']."}";
                                        }
                                        $this_price = 0;
                                        $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                                        $total=$total+$this_price;
                                    }
                                    $item = new MercadoPago\Item();
                                    $item->id = $pedido['pedido']['pedidos_id'];
                                    $item->title = "Pedido Nro #".$pedido['pedido']['pedidos_id']; 
                                    $item->quantity = 1;
                                    $item->currency_id = "COP";
                                    $item->unit_price = $pedido['pedido']['pedidos_precio_total'];
                                    array_push($items,$item);
                                    $preference->items = $items;
                                    $preference->back_urls = array(
                                        "success" => base_url("checkout/thanks/exitoso/pedido-".$nropedido),
                                        "pending" => base_url("checkout/thanks/pendiente/pedido-".$nropedido),
                                        "failure" => base_url("checkout/thanks/error/pedido-".$nropedido)
                                    );
                                    $preference->external_reference = $pedido['pedido']['pedidos_id'];
                                    $preference->save();
                                    ?>
                                    <hr>
                                    <a href="<?=$preference->init_point?>" class="btn btn-default uppercase text-white">Realizar el pedido</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
        <style>
            @media (max-width:767px){
                .card-metodo-pago{
                    border-top-left-radius: 34px;
                    border-top-right-radius: 34px;
                }
            }
        </style>
    </div>
</main>