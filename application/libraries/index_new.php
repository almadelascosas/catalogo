<?php
$productosCart = productosCart();
$productosMostrar = array();
$precioEnvio = 0;
$precioTotal = 0;
?>

<main class="col-12 cuerpo float-left mb-5">
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
                        ?>
                    <form onsubmit="loadedbutton('#btn-relizar-pedido');" action="<?=base_url()?>checkout/finalizar-compra" method="post" class="col-md-10 form-checkout offset-md-1 col-12">
                        <div class="row">
                            <div class="col-md-8 col-12">
                                <h4>Detalles de facturación</h4>
                                <?php
                                if (isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']!=NULL ) { ?>
                                <input type="hidden" name="pedidos_usuarios_id" id="pedidos_usuarios_id" value="<?=$_SESSION['usuarios_id']?>">
                                <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label for="pedidos_identificacion">Identificación <sup style="color:red;">*</sup></label>
                                    <input required class="form-control" type="tel" name="pedidos_identificacion" id="pedidos_identificacion">
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_nombre">Nombre Completo <sup style="color:red;">*</sup></label>
                                    <input required class="form-control" type="text" name="pedidos_nombre" id="pedidos_nombre">
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_pais">País / Región <sup style="color:red;">*</sup></label>
                                    <select class="form-control"  name="pedidos_pais" id="pedidos_pais">
                                        <option value="Colombia">Colombia</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_direccion">Dirección de la calle <sup style="color:red;">*</sup></label>
                                    <input required class="form-control" type="text" name="pedidos_direccion" id="pedidos_direccion">
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_departamento">Departamento <sup style="color:red;">*</sup></label>
                                    <select onchange="obtenerMunicipios(this,'#pedidos_municipio')" required class="form-control" name="pedidos_departamento" id="pedidos_departamento">
                                        <option value="">Seleccione...</option>
                                        <?php
                                        foreach ($departamentos->result_array() as $key => $value) { ?>
                                        <option value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_municipio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                    <select onchange="ubicacionCheckout('#pedidos_departamento',this);" required class="form-control" name="pedidos_municipio" id="pedidos_municipio">
                                        <option value="">Seleccione...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_codigo_postal">Código Postal (Opcional)</label>
                                    <input class="form-control" type="text" name="pedidos_codigo_postal" id="pedidos_codigo_postal">
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_telefono">Télefono <sup style="color:red;">*</sup></label>
                                    <input required class="form-control" type="tel" name="pedidos_telefono" id="pedidos_telefono">
                                </div>
                                <div class="form-group">
                                    <label for="pedidos_correo">Correo <sup style="color:red;">*</sup></label>
                                    <input required class="form-control" type="email" name="pedidos_correo" id="pedidos_correo">
                                </div>
                                <div class="row">
                                    <div class="col-12 mt-3 mb-4">
                                        <input class="check_otra_direccion" id="check_otra_direccion" type="checkbox">
                                        <input class="" id="pedidos_direccion_envio_conf" name="pedidos_direccion_envio_conf" type="hidden" value="0">
                                        <label for="check_otra_direccion">
                                            ¿ENVIAR A UNA DIRECCIÓN DIFERENTE?
                                        </label>
                                    </div>
                                    <div id="div_otra_direccion" class="d-none col-12">
                                        <div class="form-group">
                                            <label for="pedidos_correo_envio">NOMBRE COMPLETO <sup style="color:red;">*</sup></label>
                                            <input class="form-control required" type="text" name="pedidos_correo_envio" id="pedidos_correo_envio">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_pais_envio">País / Región <sup style="color:red;">*</sup></label>
                                            <select class="form-control"  name="pedidos_pais_envio" id="pedidos_pais_envio">
                                                <option value="Colombia">Colombia</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_direccion_envio">Dirección de la calle <sup style="color:red;">*</sup></label>
                                            <input class="required form-control mb-3" type="text" name="pedidos_direccion_envio" id="pedidos_direccion_envio">
                                            <input class="form-control" type="text" name="pedidos_nro_habitacion_envio" id="pedidos_nro_habitacion_envio">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_departamento">Departamento <sup style="color:red;">*</sup></label>
                                            <select onchange="obtenerMunicipios(this,'#pedidos_municipio_envio')" class="form-control" name="pedidos_departamento_envio" id="pedidos_departamento_envio">
                                                <option value="">Seleccione...</option>
                                                <?php
                                                foreach ($departamentos->result_array() as $key => $value) { ?>
                                                <option value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_municipio_envio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                            <select onchange="ubicacionCheckout('#pedidos_departamento_envio',this);" class="form-control" name="pedidos_municipio_envio" id="pedidos_municipio_envio">
                                                <option value="">Seleccione...</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_codigo_postal_envio">Código Postal (Opcional)</label>
                                            <input class="form-control" type="text" name="pedidos_codigo_postal_envio" id="pedidos_codigo_postal_envio">
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4 col-12 px-md-0">
                                <div class="card card-resumen-cart" id="resume-cart">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Producto</strong></p>
                                            </div>
                                            <div class="col-6">
                                                <p><strong>Subtotal</strong></p>
                                            </div>
                                        </div>
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
                                            <div class="col-6">
                                                <p class="producto-precio">$ <?=number_format($thisPrice, 0, ',', '.')?></p>
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

                                        <hr>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Subtotal</strong></p>
                                            </div>
                                            <div class="col-6 border-bottom">
                                                <p class="subtotal-precio">$ <?=number_format($precioTotal, 0, ',', '.')?></p>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Envío</strong></p>
                                            </div>
                                            <div class="col-6 border-bottom">
                                                <p class="envio-precio">PRECIO FIJO: $ <?=number_format($precioEnvio, 0, ',', '.')?></p>
                                                <input type="hidden" name="pedidos_precio_envio" value="<?=$precioEnvio?>">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <p><strong>Total</strong></p>
                                            </div>
                                            <div class="col-6">
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
                                        <hr>
                                        <p><strong>Selecciona el método de pago</strong></p>
                                        <div class="col-12 px-0">
                                            <label>
                                                <input checked type="radio" name="pedidos_metodo_pago" value="0">
                                                <strong>
                                                    TRANSFERENCIA BANCARIA DIRECTA
                                                </strong>
                                            </label>
                                            <p>Realiza tu pago directamente en nuestra cuenta bancaria. Por favor, usa el número del pedido como referencia de pago. Tu pedido no se procesará hasta que se haya recibido el importe en nuestra cuenta.</p>
                                            <div class="logo-banco">
                                                <img src="https://almadelascosas.com/wp-content/uploads/2020/09/payments_bancolombia.png" style="width:80px;height:80px" alt="Banco">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-12 px-0">
                                            <label>
                                                <input type="radio" name="pedidos_metodo_pago" value="2">
                                                <strong>
                                                    PAYU LATAM
                                                </strong>
                                            </label>
                                            <div class="logo-banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_payu.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_visa.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_mastercard.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_ame.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_pse.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_dinner.png" style="width:60px;height:60px" alt="Banco">
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col-12 px-0">
                                            <label>
                                                <input type="radio" name="pedidos_metodo_pago" value="3">
                                                <strong>
                                                    Mercado Pago
                                                </strong>
                                            </label>
                                            <div class="logo-banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_payu.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_visa.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_mastercard.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_ame.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_pse.png" style="width:60px;height:60px" alt="Banco">
                                                <img src="<?=base_url()?>assets/img/methods/logo_dinner.png" style="width:60px;height:60px" alt="Banco">
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" id="btn-relizar-pedido" class="btn btn-default uppercase">Realizar el pedido</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    }else{ ?>
                    <div class="col-12 text-center">
                        <h5>Aún no haz agregado nada a tu carrito</h5>
                        <a href="<?=base_url()?>tienda">Regresar a la tienda.</a>
                    </div>
                    <?php
                    }
                }else{ ?>
                    <div class="col-12 text-center">
                        <h5>Aún no haz agregado nada a tu carrito</h5>
                        <a href="<?=base_url()?>tienda">Regresar a la tienda.</a>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>

    </div>
</main>

<script type="text/javascript">
    fbq('track', 'InitiateCheckout', {content_ids:[<?=$content_ids?>],contents:[<?=$contents?>],num_items:<?=$num_items?>,currency:'COP',value:<?=$value_pixel?>});
</script>

