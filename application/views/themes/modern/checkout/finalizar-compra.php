<main class="col-12 cuerpo finalizar-compra float-left">
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-12 bg-checkout">
                    <div class="col-md-10 col-12 offset-md-1 text-md-center">
                        <h2 class="mb-0">Finalizar compra</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 pt-5">
            <div class="row">
                <?php
                if ($pedido['pedido']['pedidos_metodo_pago']=="Transferencia") { ?>
                <div class="col-12 col-md-10 offset-md-1 px-md-5">
                    <h5 class="check text-center">Gracias. Tu pedido ha sido recibido.</h5>
                    <h5 class="h5-banco">Nuestros detalles bancarios</h5>
                    <h5 class="h5-banco">Cuenta de ahorros:</h5>
                    <ul>
                        <li>Banco: <strong>Bancolombia</strong></li>
                        <li>Número de cuenta: <strong>02900001350</strong></li>
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
                            Fecha:					<br><strong><?=$pedido['pedido']['pedidos_fecha']?></strong>
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
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_productos_pedido_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_productos_producto_id']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_productos_producto_id']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_productos_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_productos_cantidad']);
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
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
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
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
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
                                <th>Dirección de envío</th>
                                <th>Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['pedidos_localidad'].'
'.$pedido['pedido']['pedidos_departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
<td><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['pedidos_localidad_envio'].'
'.$pedido['pedido']['pedidos_departamento_envio'].'
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
                }else{
                ?>
                <form action="https://checkout.payulatam.com/ppp-web-gateway-payu/" method="post" class="col-md-10 form-checkout offset-md-1 col-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-resumen-cart py-4 px-2">
                                <div class="col-12">
                                    <ul>
                                        <li>Número del pedido: <strong>#<?=$pedido['pedido']['pedidos_id']?></strong></li>
                                        <li>Fecha: <strong><?=$pedido['pedido']['pedidos_fecha']?></strong></li>
                                        <li>Total: <strong><?=$pedido['pedido']['pedidos_precio_total']?></strong></li>
                                        <li>Método de pago: <strong><?=$pedido['pedido']['pedidos_metodo_pago']?></strong></li>
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
                                    <input name="responseUrl"     type="hidden"  value="<?=base_url()?>checkout/thanks" >
                                    <input name="confirmationUrl" type="hidden"  value="<?=base_url()?>checkout/confirmationUrl" >

                                    <button type="submit" class="btn btn-default uppercase">Realizar el pedido</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
                }
                ?>
            </div>
        </div>

    </div>
</main>