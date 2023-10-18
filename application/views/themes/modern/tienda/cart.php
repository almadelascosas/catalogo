<?php
$productos = productosCart();
$productosMostrar = array();
$precioEnvio = 0;
$precioTotal = 0;
if (isset($result) && $result == 0) { ?>
<script>
    Snackbar.show({
        text: '<?=$mensaje?>',
        pos: 'top-center',
        actionText: 'Ok',
        duration: 5000
    });
</script>
<?php
} 
?>
<hr>
<div class="col-12 productos-cart-scroll">
<?php
$precioTotal = 0;
$productosMostrar = array();
$productosCart = productosCart();
$agregado = array();
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
                        if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0)  $image = delBackslashIni($value['medios_url']); 
                        if(!file_exists($image)) $image='assets/img/Not-Image.png';
                        
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
                        if ($value['productos_imagen_destacada']!="" && $value['productos_imagen_destacada']!=0)  $image = delBackslashIni($value['medios_url']); 
                        if(!file_exists($image)) $image='assets/img/Not-Image.png';
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
        <div class="col-12 item-cart">
            <div class="row">
                <div class="col-3">
                    <div class="d-table h-100 w-100">
                        <div class="d-table-cell align-middle">
                            <?php
                            if ($value['productos_imagen']!="") $image=delBackslashIni($value['productos_imagen']);
                            if(!file_exists($image)) $image="assets/img/Not-Image.png";
                            ?>
                            <img class="imgcarsingle" src="<?=$image?>" alt="Image" srcset="<?=$image?>">
                            
                        </div>
                    </div>
                </div>
                <div class="col-8 pr-4">
                    <div class="d-table h-100 w-100">
                        <div class="d-table-cell align-middle">
                            <p class="m-0"><?=$value['productos_titulo']?><br>
                            <span>$ <?=number_format($value['productos_precio'], 0, ',', '.')?> x <?php
                            echo $value['productos_cantidad'];
                            $thisPrice = $value['productos_precio']*$value['productos_cantidad'];
                            $precioTotal = $precioTotal+$thisPrice;
                            ?> = $ <?=number_format($thisPrice, 0, ',', '.')?></span>
                            </p>
                            <span class="del-item" onclick="delitem(<?=$value['productos_id']?>,<?=$value['productos_precio']?>);$(this).parent().parent().parent().parent().parent().remove();">×</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 px-0">
                    <hr>
                </div>
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
                                if (floatval($value['productos'][$i]['productos_valor_envio_local']) >= $cantidadMun[$cantvr]['valor']) {
                                    $cantidadMun[$cantvr]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_local']);
                                }       
                            }
                        }
                    }
                    if ($p_ub==0) {
                        $thisUbi = explode(",",$tubicaciones[0]);
                        if (isset($thisUbi[1])) {
                            array_push($cantidadMun,array(
                                "municipio" => $thisUbi[1],
                                "valor" => floatval($value['productos'][$i]['productos_valor_envio_local'])
                            ));
                        }else{
                            $ub_0 = 0;
                            $valor_0 = 0;
                            for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                if ($cantidadMun[$ubi_0]['municipio']==0) {
                                    $ub_0 = 1;
                                    if(floatval($value['productos'][$i]['productos_valor_envio_local']) >= $cantidadMun[$ubi_0]['valor']){
                                        $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_local']);
                                    }
                                }
                            }
                            if ($ub_0==0) {
                                array_push($cantidadMun,array(
                                    "municipio" => 0,
                                    "valor" => floatval($value['productos'][$i]['productos_valor_envio_local'])
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
    }else{ ?>
    <div class="ilust-cart">
        <span class="icon-cart"></span>
    </div>
    <p class="text-center">No hay productos en el carrito.</p>
    <?php
    }
}else{ ?>
    <div class="ilust-cart">
        <span class="icon-cart"></span>
    </div>
    <p class="text-center">No hay productos en el carrito.</p>
<?php
}?>
</div>
<?php
if ($productosCart!=NULL && $productosCart!="") {
    if ($productosCart->num_rows() > 0) { ?>
    <div class="bottom-cart col-12 pb-4">
        <div class="row">
            <div class="col-6 pt-4 text-left">
                Envíos:
            </div>
            <div class="col-6 pt-4 text-right">
                $ <?=number_format($precioEnvio, 0, ',', '.')?>
            </div>
            <div class="col-12">
                <hr>
            </div>
            <div class="col-6 text-left">
                <strong>Total con envío:</strong>
            </div>
            <div class="col-6 text-right">
                <strong>$ <?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></strong>
            </div>
            <!--
            <div class="col-6">
                <a class="btn w-100 text-center py-3 btn-second" onclick="cerrarCart();" href="#">Seguir comprando</a>
            </div>-->
            <div class="col-12 text-center py-4">
                <a class="btn w-100 text-center py-3 btn-green-alma" href="<?=base_url('checkout')?>">Continuar con la compra</a>
            </div>
        </div>
    </div>
    <?php
    }
}
?>