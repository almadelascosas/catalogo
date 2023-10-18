<?php
$productosCart = productosCart();
$productosMostrar = array();
$precioEnvio = 0;
$precioTotal = 0;

if ($productosCart!=NULL && $productosCart!="") {
    if ($productosCart->num_rows() > 0) {
        foreach ($_SESSION['cart'] as $key2 => $value2) {
            $agregado[$key2] = 0;
            foreach ($productosCart->result_array() as $key => $value) {
                if ($value['productos_id']==$value2['productos_id']) {
                    $agg=0;
                    for ($i=0; $i < count($productosMostrar); $i++) {
                        if (isset($productosMostrar[$i]['productos_id'])) {
                            if($productosMostrar[$i]['productos_id']==$value2['productos_id']){
                                if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['productos_precio'])) {
                                    if ($agregado[$key2]!=1) {
                                        $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['productos_cantidad']);
                                        $agregado[$key2] = 1;
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($agregado[$key2]!=1) {
                        $image = "";
                        if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0) {
                            $image=image($value['medios_url']);
                        }
                        array_push($productosMostrar, array(
                            'productos_id' => $value['productos_id'],
                            'productos_imagen' => $image,
                            'productos_titulo' => $value['productos_titulo'],
                            'productos_precio' => floatval($value2['productos_precio']),
                            'productos_cantidad' => floatval($value2['productos_cantidad']),
                            'productos_ubicaciones_envio' => $value['productos_ubicaciones_envio'],
                            'productos_envio_local' => $value['productos_envio_local'],
                            'productos_envio_nacional' => $value['productos_envio_nacional'],
                            'productos_valor_envio_local' => $value['productos_valor_envio_local'],
                            'productos_valor_envio_nacional' => $value['productos_valor_envio_nacional'],
                            'productos_vendedor' => $value['productos_vendedor'],
                        ));
                        $agregado[$key2] = 1;
                    }
                    if (count($productosMostrar)<=0) {
                        $image = "";
                        if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0) {
                            $image=image($value['medios_url']);
                        }
                        array_push($productosMostrar, array(
                            'productos_id' => $value['productos_id'],
                            'productos_imagen' => $image,
                            'productos_titulo' => $value['productos_titulo'],
                            'productos_precio' => floatval($value2['productos_precio']),
                            'productos_cantidad' => floatval($value2['productos_cantidad']),
                            'productos_ubicaciones_envio' => $value['productos_ubicaciones_envio'],
                            'productos_envio_local' => $value['productos_envio_local'],
                            'productos_envio_nacional' => $value['productos_envio_nacional'],
                            'productos_valor_envio_local' => $value['productos_valor_envio_local'],
                            'productos_valor_envio_nacional' => $value['productos_valor_envio_nacional'],
                            'productos_vendedor' => $value['productos_vendedor'],
                        ));
                        $agregado[$key2] = 1;
                    }
                }
            }
        }
        $cantidadVendor=array();
        if (!isMobile()) { ?>
        <h4 class="mt-2">Total carrito</h4>
        <?php
        /* Pixel */
        $content_ids = "";
        $contents = "";
        $num_items = 0;
        $value_pixel = 0;

        $productos_id = "";
        $productos_cantidad = "";
        $productos_precio = "";
        $productos_addons = "";
        $precioEnvio=0;
        $count=0;
        foreach ($_SESSION['cart'] as $key => $value) {
            $count++;
            $num_items = $num_items+floatval($value['productos_cantidad']);
            $value_pixel = $value_pixel+floatval($value['productos_precio']);
            if ($content_ids=="") {
                $content_ids = "'".$value['productos_id']."'";
                $contents = "{'id': '".$value['productos_id']."', 'quantity':".$value['productos_cantidad']."}";
            }else{
                $content_ids .= ",'".$value['productos_id']."'";
                $contents .= ",{'id': '".$value['productos_id']."', 'quantity':".$value['productos_cantidad']."}";
            }
            if ($count==1) {

                $productos_id = $value['productos_id'];
                $productos_cantidad = $value['productos_cantidad'];
                $productos_precio = $value['productos_precio'];
                $productos_addons = $value['productos_addons'];
            }else{
                
                $productos_id .= "/+/".$value['productos_id'];
                $productos_cantidad .= "/+/".$value['productos_cantidad'];
                $productos_precio .= "/+/".$value['productos_precio'];
                $productos_addons .= "/+/".$value['productos_addons'];
            }
        }
        ?>
        <input type="hidden" name="productos_cantidad_cart[]" id="productos_cantidad_cart" value="<?=$productos_cantidad?>">
        <input type="hidden" name="productos_precio_cart[]" id="productos_precio_cart" value="<?=$productos_precio?>">
        <input type="hidden" name="productos_addons_cart[]" id="productos_addons_cart" value="<?=$productos_addons?>">
        <input type="hidden" name="productos_id_cart[]" id="productos_id_cart" value="<?=$productos_id?>">
        <?php
        foreach ($productosMostrar as $key => $value) {
            $upvendor = 0;
            for ($i=0; $i < count($cantidadVendor); $i++) {
                if (in_array($value['productos_vendedor'],$cantidadVendor[$i])) {
                    $upvendor=1;
                }
            }

            if ($upvendor==1) {
                for ($i=0; $i < count($cantidadVendor); $i++) {
                    if ($value['productos_vendedor']==$cantidadVendor[$i]['vendedor']) {
                        $upProduct = 0;
                        for ($i2=0; $i2 < count($cantidadVendor[$i]['productos']); $i2++) {
                            if ($cantidadVendor[$i]['productos'][$i2]['productos_id']==$value['productos_id']) {
                                $upProduct = 1;
                            }
                        }
                        if ($upProduct==0) {
                            array_push($cantidadVendor[$i]['productos'],array(
                                "productos_id" => $value['productos_id'],
                                "productos_ubicaciones_envio" => $value['productos_ubicaciones_envio'],
                                "productos_envio_nacional" => $value['productos_envio_nacional'],
                                "productos_envio_local" => $value['productos_envio_local'],
                                "productos_valor_envio_local" => $value['productos_valor_envio_local'],
                                "productos_valor_envio_nacional" => $value['productos_valor_envio_nacional']
                            ));
                        }
                    }
                }
            }else{
                array_push($cantidadVendor, array(
                    "vendedor" => $value['productos_vendedor'],
                    "productos" => array(
                        array(
                            "productos_id" => $value['productos_id'],
                            "productos_ubicaciones_envio" => $value['productos_ubicaciones_envio'],
                            "productos_envio_nacional" => $value['productos_envio_nacional'],
                            "productos_envio_local" => $value['productos_envio_local'],
                            "productos_valor_envio_local" => $value['productos_valor_envio_local'],
                            "productos_valor_envio_nacional" => $value['productos_valor_envio_nacional']
                        )
                    )
                ));
            } 
            ?>
        <input type="hidden" value="<?=$value['productos_id']?>" name="pedidos_productos[]">
        <div class="row">
            <div class="col-6">
                <p class="producto-item mb-0"><?=$value['productos_titulo']?> $ <?=number_format($value['productos_precio'], 0, ',', '.')?> <strong>x <?php
                echo $value['productos_cantidad'];
                $thisPrice = $value['productos_precio']*$value['productos_cantidad'];
                $precioTotal = $precioTotal+$thisPrice;
                ?></strong>
                <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_cantidad[]">
                </p>
                <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_extras[]">
            </div>
            <div class="col-6 text-right">
                <p class="producto-precio mb-0"><strong>$ <?=number_format($thisPrice, 0, ',', '.')?></strong></p>
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
        <?php
        }
        $precioEnvio = 0;
        foreach ($cantidadVendor as $key => $value) {
            $thisLocal = 0;
            $thisNacional = 0;
            $cantidadMun = array();
            for ($i=0; $i < count($value['productos']); $i++) {
                /* Verificación de envíos nacionales */
                if ($value['productos'][$i]['productos_envio_nacional']==1) {
                    /* Verificación de valor de envío nacional gratis */
                    if ($value['productos'][$i]['productos_valor_envio_nacional'] > 0) {
                        if (isset($_SESSION['municipio_session']) && isset($_SESSION['departamento_session'])) {
                            /* Verificación del municipio session del cliente */
                            $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                            $p_ub = 0;
                            for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                $thisUbi = explode(",",$tubicaciones[$i2]);
                                if ($thisUbi[0]==$_SESSION['departamento_session'] && $thisUbi[1]==$_SESSION['municipio_session']) {
                                    $p_ub = 1;
                                }                                                            
                            }
                            if ($p_ub==1) {
                                if (floatval($value['productos'][$i]['productos_valor_envio_local']) >= $thisLocal) {
                                    $thisLocal = floatval($value['productos'][$i]['productos_valor_envio_local']);
                                }
                            }else{
                                if ($value['productos'][$i]['productos_ubicaciones_envio']=="") {
                                    /* Verificación de municipio 0 */
                                    $ub_0 = 0;
                                    $valor_0 = 0;
                                    for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                        if ($cantidadMun[$ubi_0]['municipio']==0) {
                                            $ub_0 = 1;
                                            if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                            }
                                        }
                                    }
                                    if ($ub_0==0) {
                                        array_push($cantidadMun,array(
                                            "municipio" => 0,
                                            "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                        ));
                                    }
                                }else{
                                    /* Verificación de ubicaciones entre productos */
                                    $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                                    $p_ub = 0;
                                    for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                        $thisUbi = explode(",",$tubicaciones[$i2]);
                                        for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                            if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                $p_ub = 1;
                                                if (floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                    $cantidadMun[$cantvr]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                }       
                                            }
                                        }
                                    }
                                    if ($p_ub==0) {
                                        $thisUbi = explode(",",$tubicaciones[0]);
                                        if (isset($thisUbi[1])) {
                                            array_push($cantidadMun,array(
                                                "municipio" => $thisUbi[1],
                                                "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                            ));
                                        }else{
                                            $ub_0 = 0;
                                            $valor_0 = 0;
                                            for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                    $ub_0 = 1;
                                                    if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                        $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                    }
                                                }
                                            }
                                            if ($ub_0==0) {
                                                array_push($cantidadMun,array(
                                                    "municipio" => 0,
                                                    "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                                ));
                                            }       
                                        }
                                    }

                                }
                            }

                        }else{
                            /* Verificación de municipios entre productos */
                            $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                            $p_ub = 0;
                            for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                $thisUbi = explode(",",$tubicaciones[$i2]);
                                for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                    if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                        $p_ub = 1;
                                        if (floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                            $cantidadMun[$cantvr]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                        }       
                                    }
                                }
                            }
                            if ($p_ub==0) {
                                $thisUbi = explode(",",$tubicaciones[0]);
                                if (isset($thisUbi[1])) {
                                    array_push($cantidadMun,array(
                                        "municipio" => $thisUbi[1],
                                        "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                    ));
                                }else{
                                    $ub_0 = 0;
                                    $valor_0 = 0;
                                    for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                        if ($cantidadMun[$ubi_0]['municipio']==0) {
                                            $ub_0 = 1;
                                            if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                            }
                                        }
                                    }
                                    if ($ub_0==0) {
                                        array_push($cantidadMun,array(
                                            "municipio" => 0,
                                            "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                        ));
                                    }       
                                }
                            }
                        }
                    }
                }else{
                    $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                    $p_ub = 0;
                    for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                        $thisUbi = explode(",",$tubicaciones[$i2]);
                        for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                            if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                $p_ub = 1;
                                if (floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                    $cantidadMun[$cantvr]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                }       
                            }
                        }
                    }
                    if ($p_ub==0) {
                        $thisUbi = explode(",",$tubicaciones[0]);
                        if (isset($thisUbi[1])) {
                            array_push($cantidadMun,array(
                                "municipio" => $thisUbi[1],
                                "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                            ));
                        }else{
                            $ub_0 = 0;
                            $valor_0 = 0;
                            for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                if ($cantidadMun[$ubi_0]['municipio']==0) {
                                    $ub_0 = 1;
                                    if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                        $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                    }
                                }
                            }
                            if ($ub_0==0) {
                                array_push($cantidadMun,array(
                                    "municipio" => 0,
                                    "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                ));
                            }       
                        }
                    }
                }
            }
            for ($i74=0; $i74 < count($cantidadMun); $i74++) {
                $thisNacional = $thisNacional+floatval($cantidadMun[$i74]['valor']);
            }
            $precioEnvio = $precioEnvio+$thisLocal+$thisNacional;
        }
        ?>
        <div class="row">
            <div class="col-6">
                <p class="mb-0">Subtotal</p>
            </div>
            <div class="col-6 text-right">
                <p class="subtotal-precio mb-0"><strong>$ <?=number_format($precioTotal, 0, ',', '.')?></strong></p>
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <p class="mb-0">Envíos</p>
            </div>
            <div class="col-6 text-right">
                <p class="envio-precio mb-0"><strong> FIJO: $ <?=number_format($precioEnvio, 0, ',', '.')?></strong></p>
                <input type="hidden" name="pedidos_precio_envio" value="<?=$precioEnvio?>">
            </div>
            <div class="col-12">
                <hr>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <h5 class="total-h5precio mb-0"><strong>Total</strong></h5>
            </div>
            <div class="col-6 text-right">
                <?php
                if (count($cantidadVendor) > 1) {
                    echo "<p class='m-0'>Su pedido está compuesto por productos de distintos origenes.</p>";
                }
                ?>
                <h5 class="total-h5precio mb-0">$ <?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></h5>
                <input type="hidden" name="pedidos_precio_subtotal" value="<?=$precioTotal?>">
                <input type="hidden" name="pedidos_precio_total" value="<?=$precioTotal+$precioEnvio?>">
            </div>
        </div>
        <div class="col-12 checkout-boton-next text-center py-4">
            <button id="btn-next-pedido" onclick="nextCheck();" type="button" class="btn btn-green-alma text-white uppercase">Continuar al pago</button>
        </div>
        <div class="col-12 checkout-boton-submit d-none text-center py-4">
            <button onclick="loadedbutton('#btn-relizar-pedido');$('#form-checkout').submit();" type="button" id="btn-relizar-pedido" class="btn w-100 btn-green-alma text-white uppercase">Pagar pedido $ <?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></button>
        </div>
        <?php
        }else{ ?>
        <div class="col-12 px-0">
            <h4 class="text-left">Elige una forma de pago</h4>
        </div>
        <div class="w-100">
            <?php
            foreach ($metodos_pagos->result_array() as $key2 => $value2) {
                if ($value2['alma_metodos_pagos_titulo']=="Transferencia Bancaria") {
                    ?>
                    <div class="col-12 card-metodo-pago border mb-3">
                        <label>
                            <strong>
                                TRANSFERENCIA BANCARIA DIRECTA
                            </strong>
                            <input checked type="radio" name="pedidos_metodo_pago" value="0">
                            <p class="metodo-mensaje-check">Realiza tu pago directamente en nuestra cuenta bancaria. Por favor, usa el número del pedido como referencia de pago. Tu pedido no se procesará hasta que se haya recibido el importe en nuestra cuenta.</p>
                            <hr>
                            <div class="logo-banco">
                                <img src="<?=base_url()?>assets/img/methods/bancolombia_negro.png" style="width:180px;" alt="Banco">
                            </div>
                        </label>
                    </div>
                    <?php  
                }
                if ($value2['alma_metodos_pagos_titulo']=="PSE - Payzen") {
                    ?>
                        <div class="col-12 active card-metodo-pago border mb-3">
                            <label>
                                <strong>
                                    <img class="pr-2" src="<?=base_url()?>assets/img/methods/pse_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    PSE - Payzen
                                </strong>
                                <input checked type="radio" name="pedidos_metodo_pago" value="4">
                            </label>
                        </div>
                    <?php  
                }
                if ($value2['alma_metodos_pagos_titulo']=="Payzen") {
                    ?>
                        <div class="col-12 card-metodo-pago border mb-3">
                            <label>
                                <strong>
                                    Payzen
                                </strong>
                                <input type="radio" name="pedidos_metodo_pago" value="1">
                                <hr>
                                <div class="logo-banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/mastercard_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/visa_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/AE_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/dinner_nuevo.png?v=2" style="height:30px" alt="Banco">
                                </div>
                            </label>
                        </div>
                    <?php  
                }
                if ($value2['alma_metodos_pagos_titulo']=="Mercado Pago") {
                    ?>
                        <div class="col-12 card-metodo-pago border mb-3">
                            <label>
                                <strong>
                                    PSE o Tarjeta de Crédito
                                </strong>
                                <input type="radio" name="pedidos_metodo_pago" value="3">
                                <hr>
                                <div class="logo-banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/mastercard_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/visa_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/AE_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-1" src="<?=base_url()?>assets/img/methods/dinner_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/logo_mercado_pago.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-1" src="<?=base_url()?>assets/img/methods/pse_nuevo.png?v=2" style="height:30px" alt="Banco">
                                </div>
                            </label>
                        </div>
                    <?php  
                }
                if ($value2['alma_metodos_pagos_titulo']=="PayU") {
                    ?>
                        <div class="col-12 card-metodo-pago border mb-3">
                            <label>
                                <strong>
                                    PayU Latam
                                </strong>
                                <input type="radio" name="pedidos_metodo_pago" value="2">
                                <hr>
                                <div class="logo-banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/mastercard_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/visa_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/AE_nuevo.png?v=2" style="height:30px" alt="Banco">
                                    <img class="p-2" src="<?=base_url()?>assets/img/methods/dinner_nuevo.png?v=2" style="height:30px" alt="Banco">
                                </div>
                            </label>
                        </div>
                    <?php  
                }
            }
            ?>
        </div>
        <div class="col-12 mt-5">
            <h4 class="text-left">Resumen de pedido</h4>
        </div>
        <div class="col-12 card-metodo-pago border resumen-pago mb-4">
            <?php
            /* Pixel */
            $content_ids = "";
            $contents = "";
            $num_items = 0;
            $value_pixel = 0;

            $productos_id = "";
            $productos_cantidad = "";
            $productos_precio = "";
            $productos_addons = "";
            $precioEnvio=0;
            $count=0;
            foreach ($_SESSION['cart'] as $key => $value) {
                $count++;
                $num_items = $num_items+floatval($value['productos_cantidad']);
                $value_pixel = $value_pixel+floatval($value['productos_precio']);
                if ($content_ids=="") {
                    $content_ids = "'".$value['productos_id']."'";
                    $contents = "{'id': '".$value['productos_id']."', 'quantity':".$value['productos_cantidad']."}";
                }else{
                    $content_ids .= ",'".$value['productos_id']."'";
                    $contents .= ",{'id': '".$value['productos_id']."', 'quantity':".$value['productos_cantidad']."}";
                }
                if ($count==1) {

                    $productos_id = $value['productos_id'];
                    $productos_cantidad = $value['productos_cantidad'];
                    $productos_precio = $value['productos_precio'];
                    $productos_addons = $value['productos_addons'];
                }else{
                    
                    $productos_id .= "/+/".$value['productos_id'];
                    $productos_cantidad .= "/+/".$value['productos_cantidad'];
                    $productos_precio .= "/+/".$value['productos_precio'];
                    $productos_addons .= "/+/".$value['productos_addons'];
                }
            }
            ?>
            <input type="hidden" name="productos_cantidad_cart[]" id="productos_cantidad_cart" value="<?=$productos_cantidad?>">
            <input type="hidden" name="productos_precio_cart[]" id="productos_precio_cart" value="<?=$productos_precio?>">
            <input type="hidden" name="productos_addons_cart[]" id="productos_addons_cart" value="<?=$productos_addons?>">
            <input type="hidden" name="productos_id_cart[]" id="productos_id_cart" value="<?=$productos_id?>">
            <?php
            foreach ($productosMostrar as $key => $value) {
                $upvendor = 0;
                for ($i=0; $i < count($cantidadVendor); $i++) {
                    if (in_array($value['productos_vendedor'],$cantidadVendor[$i])) {
                        $upvendor=1;
                    }
                }

                if ($upvendor==1) {
                    for ($i=0; $i < count($cantidadVendor); $i++) {
                        if ($value['productos_vendedor']==$cantidadVendor[$i]['vendedor']) {
                            $upProduct = 0;
                            for ($i2=0; $i2 < count($cantidadVendor[$i]['productos']); $i2++) {
                                if ($cantidadVendor[$i]['productos'][$i2]['productos_id']==$value['productos_id']) {
                                    $upProduct = 1;
                                }
                            }
                            if ($upProduct==0) {
                                array_push($cantidadVendor[$i]['productos'],array(
                                    "productos_id" => $value['productos_id'],
                                    "productos_ubicaciones_envio" => $value['productos_ubicaciones_envio'],
                                    "productos_envio_nacional" => $value['productos_envio_nacional'],
                                    "productos_envio_local" => $value['productos_envio_local'],
                                    "productos_valor_envio_local" => $value['productos_valor_envio_local'],
                                    "productos_valor_envio_nacional" => $value['productos_valor_envio_nacional']
                                ));
                            }
                        }
                    }
                }else{
                    array_push($cantidadVendor, array(
                        "vendedor" => $value['productos_vendedor'],
                        "productos" => array(
                            array(
                                "productos_id" => $value['productos_id'],
                                "productos_ubicaciones_envio" => $value['productos_ubicaciones_envio'],
                                "productos_envio_nacional" => $value['productos_envio_nacional'],
                                "productos_envio_local" => $value['productos_envio_local'],
                                "productos_valor_envio_local" => $value['productos_valor_envio_local'],
                                "productos_valor_envio_nacional" => $value['productos_valor_envio_nacional']
                            )
                        )
                    ));
                } 
                ?>
            <input type="hidden" value="<?=$value['productos_id']?>" name="pedidos_productos[]">
            <div class="row">
                <div class="col-6">
                    <p class="producto-item"><?=$value['productos_titulo']?> $ <?=number_format($value['productos_precio'], 0, ',', '.')?> <strong>x <?php
                    echo $value['productos_cantidad'];
                    $thisPrice = $value['productos_precio']*$value['productos_cantidad'];
                    $precioTotal = $precioTotal+$thisPrice;
                    ?></strong>
                    <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_cantidad[]">
                    </p>
                    <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_extras[]">
                </div>
                <div class="col-6 text-right">
                    <p class="producto-precio"><strong>$ <?=number_format($thisPrice, 0, ',', '.')?></strong></p>
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <?php
            }
            $precioEnvio = 0;
            foreach ($cantidadVendor as $key => $value) {
                $thisLocal = 0;
                $thisNacional = 0;
                $cantidadMun = array();
                for ($i=0; $i < count($value['productos']); $i++) {
                    /* Verificación de envíos nacionales */
                    if ($value['productos'][$i]['productos_envio_nacional']==1) {
                        /* Verificación de valor de envío nacional gratis */
                        if ($value['productos'][$i]['productos_valor_envio_nacional'] > 0) {
                            if (isset($_SESSION['municipio_session']) && isset($_SESSION['departamento_session'])) {
                                /* Verificación del municipio session del cliente */
                                $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                                $p_ub = 0;
                                for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                    $thisUbi = explode(",",$tubicaciones[$i2]);
                                    if ($thisUbi[0]==$_SESSION['departamento_session'] && $thisUbi[1]==$_SESSION['municipio_session']) {
                                        $p_ub = 1;
                                    }                                                            
                                }
                                if ($p_ub==1) {
                                    if (floatval($value['productos'][$i]['productos_valor_envio_local']) >= $thisLocal) {
                                        $thisLocal = floatval($value['productos'][$i]['productos_valor_envio_local']);
                                    }
                                }else{
                                    if ($value['productos'][$i]['productos_ubicaciones_envio']=="") {
                                        /* Verificación de municipio 0 */
                                        $ub_0 = 0;
                                        $valor_0 = 0;
                                        for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                            if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                $ub_0 = 1;
                                                if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                    $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                }
                                            }
                                        }
                                        if ($ub_0==0) {
                                            array_push($cantidadMun,array(
                                                "municipio" => 0,
                                                "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                            ));
                                        }
                                    }else{
                                        /* Verificación de ubicaciones entre productos */
                                        $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                                        $p_ub = 0;
                                        for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                            $thisUbi = explode(",",$tubicaciones[$i2]);
                                            for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                                if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                    $p_ub = 1;
                                                    if (floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                        $cantidadMun[$cantvr]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                    }       
                                                }
                                            }
                                        }
                                        if ($p_ub==0) {
                                            $thisUbi = explode(",",$tubicaciones[0]);
                                            if (isset($thisUbi[1])) {
                                                array_push($cantidadMun,array(
                                                    "municipio" => $thisUbi[1],
                                                    "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                                ));
                                            }else{
                                                $ub_0 = 0;
                                                $valor_0 = 0;
                                                for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                    if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                        $ub_0 = 1;
                                                        if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                            $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                        }
                                                    }
                                                }
                                                if ($ub_0==0) {
                                                    array_push($cantidadMun,array(
                                                        "municipio" => 0,
                                                        "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                                    ));
                                                }       
                                            }
                                        }

                                    }
                                }

                            }else{
                                /* Verificación de municipios entre productos */
                                $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                                $p_ub = 0;
                                for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                    $thisUbi = explode(",",$tubicaciones[$i2]);
                                    for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                        if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                            $p_ub = 1;
                                            if (floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                $cantidadMun[$cantvr]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                            }       
                                        }
                                    }
                                }
                                if ($p_ub==0) {
                                    $thisUbi = explode(",",$tubicaciones[0]);
                                    if (isset($thisUbi[1])) {
                                        array_push($cantidadMun,array(
                                            "municipio" => $thisUbi[1],
                                            "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                        ));
                                    }else{
                                        $ub_0 = 0;
                                        $valor_0 = 0;
                                        for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                            if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                $ub_0 = 1;
                                                if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                    $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                }
                                            }
                                        }
                                        if ($ub_0==0) {
                                            array_push($cantidadMun,array(
                                                "municipio" => 0,
                                                "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                            ));
                                        }       
                                    }
                                }
                            }
                        }
                    }else{
                        $tubicaciones = explode("/",$value['productos'][$i]['productos_ubicaciones_envio']);
                        $p_ub = 0;
                        for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                            $thisUbi = explode(",",$tubicaciones[$i2]);
                            for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                    $p_ub = 1;
                                    if (floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                        $cantidadMun[$cantvr]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                    }       
                                }
                            }
                        }
                        if ($p_ub==0) {
                            $thisUbi = explode(",",$tubicaciones[0]);
                            if (isset($thisUbi[1])) {
                                array_push($cantidadMun,array(
                                    "municipio" => $thisUbi[1],
                                    "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                ));
                            }else{
                                $ub_0 = 0;
                                $valor_0 = 0;
                                for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                    if ($cantidadMun[$ubi_0]['municipio']==0) {
                                        $ub_0 = 1;
                                        if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                            $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                        }
                                    }
                                }
                                if ($ub_0==0) {
                                    array_push($cantidadMun,array(
                                        "municipio" => 0,
                                        "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                    ));
                                }       
                            }
                        }
                    }
                }
                for ($i74=0; $i74 < count($cantidadMun); $i74++) {
                    $thisNacional = $thisNacional+floatval($cantidadMun[$i74]['valor']);
                }
                $precioEnvio = $precioEnvio+$thisLocal+$thisNacional;
            }
            ?>
            <div class="row">
                <div class="col-6">
                    <p>Subtotal</p>
                </div>
                <div class="col-6 text-right">
                    <p class="subtotal-precio"><strong>$ <?=number_format($precioTotal, 0, ',', '.')?></strong></p>
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <p>Envíos</p>
                </div>
                <div class="col-6 text-right">
                    <p class="envio-precio"><strong> FIJO: $ <?=number_format($precioEnvio, 0, ',', '.')?></strong></p>
                    <input type="hidden" name="pedidos_precio_envio" value="<?=$precioEnvio?>">
                </div>
                <div class="col-12">
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <h5 class="total-h5precio"><strong>Total</strong></h5>
                </div>
                <div class="col-6 text-right">
                    <?php
                    if (count($cantidadVendor) > 1) {
                        echo "<p class='m-0'>Su pedido está compuesto por productos de distintos origenes.</p>";
                    }
                    ?>
                    <h5 class="total-h5precio">$ <?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></h5>
                    <input type="hidden" name="pedidos_precio_subtotal" value="<?=$precioTotal?>">
                    <input type="hidden" name="pedidos_precio_total" value="<?=$precioTotal+$precioEnvio?>">
                </div>
            </div>
        </div>
        <?php
        }
    }
}