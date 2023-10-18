<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<?php
$html='';
$totalEnvios=0;
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
                            'productos_fecha_programada' => $value2['productos_fecha_programada']
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
                            'productos_fecha_programada' => $value['productos_fecha_programada']
                        ));
                        $agregado[$key2] = 1;
                    }
                }
            }
        }
        $cantidadVendor=array();
    }
}


/*
** Direcciones de Envio
**
** --- CASO 1 --- (Logueado con Direcciones)
** Si esta logueado... recorremos cual de las direcciones tiene como predeterminado y lo usaremos como datos de envio
** si no tiene ninguno como pre4determinado... tomamos la primera direccion que tenga
** Si no tiene direciones, mostramos el form de ingreso de direccion
**
** --- CASO 2 --- (Logueado sin Direcciones)
** Mostramos el form de ingresar los datos de direccion... autollenamos los campos como Identificacion, Nombre, Correo, Dpto y Ciudad
**
** --- CASO 3 --- (No Logueado)
** Mostramos el form de ingresar los datos de direccion
*/
$countDir=0;
if(isset($datos_usuario['usuarios_id'])){
    $datosEnvio=[];
    if(is_array($datos_usuario['direcciones'])){
        foreach($datos_usuario['direcciones'] as $key => $dir):
            $countDir++;
            if(intval($key)===0) $datosEnvio=$dir;
            if(intval($dir['prede'])===1) $datosEnvio=$dir;
        endforeach;
    }
}

?>
<main class="col-12 cuerpo float-left mb-5">
    <div class="row">
        <div class="col-12 pt-5 mt-2">
            <div class="row">
                <?php
                if ($productosCart!=NULL && $productosCart!="") {
                    if ($productosCart->num_rows() > 0) {
                        ?>
                <form action="<?=base_url('checkout/finalizar-compra')?>" method="post" id="form-checkout" class="col-md-10 form-checkout offset-md-1 col-12 px-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="w-100 h-100 d-table">
                                <div class="d-table-cell align-middle">
                                    <a class="btn-back-check mb-3 d-none" onclick="prevCheck();" href="#back"><span class="icon-arrow-left2"></span> <span class="d-md-inline-block d-none">Volver</span></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 height-50"><!-- SEPARADO --></div>
                        <div class="col-12">
                            <div class="col-12">
                                <h3>Checkout</h3>
                            </div>
                        </div>
                        <div class="checkout-modulos col-12">
                            <div class="row">                               

                                <!-- INICIO COLUMNA DIRECCION | METODO -->
                                <div class="col-md-7 col-sm-12">
                                    <div class="row">
                                        
                                        <div class="col-12 height-30"></div>                                        

                                        <?php if(isset($datosEnvio['id_dir'])){ ?>
                                        <div class="col-md-11 col-12 card-metodo-pago border ">
                                            <div class="row">
                                                <div class="col-11 interior">
                                                    <div style="height: 20px;"></div>
                                                    <div class="row">
                                                        <div class="col-8 text-left">
                                                            <h4 class="metodo-pago-titulo">Dirección de envio</h4>
                                                        </div>
                                                        <div class="col-4 text-right">
                                                            <?php if(isset($countDir) && $countDir>1){ ?>
                                                            <!-- <a href="<?=base_url('checkout/elegir-mis-direcciones')?>" class="btn-green-alma-simple" data-toggle="modal" data-target="#mdCambiarDireccion">Cambiar</a> -->
                                                            <a href="javascript:void(0)" class="btn-green-alma-simple text-left" data-toggle="modal" data-target="#mdCambiarDireccion">Cambiar</a>
                                                        
                                                            <?php }else if(isset($countDir) && $countDir===1){ ?>
                                                            <a href="javascript:void(0)>" class="btn-green-alma-simple" data-toggle="modal" data-target="#mdNuevaDireccion">Crear</a>
                                                            <?php } ?>
                                                        </div>
                                                        <div class="col-12"> <hr> </div>
                                                        <div class="col-12">
                                                            <b><?=$datosEnvio['nombre']?></b><br />
                                                            <?=$datosEnvio['direccion']?><br />
                                                            <?=$datosEnvio['barrio']?><br />
                                                            <?=getDepartamentoById($datosEnvio['id_dpto'])?>,
                                                            <?=getMunicipioById($datosEnvio['id_muni'])?>, Colombia
                                                        </div>
                                                    </div>
                                                    <div style="height: 20px;"></div>
                                                </div>
                                                
                                            </div>                                         
                                        </div>
                                        <?php } ?>

                                        <div class="col-md-11 col-12 card-metodo-pago border <?=(isset($datosEnvio['id_dir']))?'d-none':'';?>" id="div_otra_direccion">
                                            <div class="row">
                                                <div class="col-11 interior">
                                                    <div style="height: 20px;"></div>
                                                    <div class="col-12 text-left">
                                                        <h4 class="metodo-pago-titulo">Datos de facturación</h4>
                                                    </div>

                                                    <input type="hidden" name="pedidos_usuarios_id" id="pedidos_usuarios_id" value="<?=(isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']!==NULL)?$_SESSION['usuarios_id']:''?>">
                                                    <div class="form-group">
                                                        <input required class="required form-control form-control-sm" type="email" name="pedidos_correo" id="pedidos_correo" placeholder="Correo" value="<?=(isset($datos_usuario['usuarios_id']))?$datos_usuario['email']:''?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <input required class="required form-control" type="tel" name="pedidos_identificacion" id="pedidos_identificacion" placeholder="Identificación" value="<?=(isset($datos_usuario['usuarios_id']))?$datos_usuario['dni']:''?>">
                                                    </div>
                                                    <div class="form-group">
                                                        <input required class="required form-control" type="text" name="pedidos_nombre" id="pedidos_nombre" placeholder="Nombre completo" value="<?=(isset($datos_usuario['usuarios_id']))?(isset($datosEnvio['persona']))?$datosEnvio['persona']:$datos_usuario['name'].' '.$datos_usuario['lastname']:''?>">
                                                    </div>
                                                    <div class="col-12 mb-0">
                                                        <p class="bold">Dirección de envío</p>
                                                    </div>
                                                    <div class="form-group">
                                                        <input required class="required form-control" type="text" name="pedidos_nombre_envio" id="pedidos_nombre_envio" placeholder="Nombre de quien recibe" value="">
                                                    </div>

                                                    <?php
                                                    $deptoSeleccionado='';
                                                    $muniSeleccionado='';

                                                    if(isset($_SESSION['departamento_session'])) $deptoSeleccionado = $_SESSION['departamento_session'];
                                                    if(isset($datosEnvio['id_dpto'])) $deptoSeleccionado = $datosEnvio['id_dpto']; 

                                                    if(isset($_SESSION['municipio_session'])) $muniSeleccionado = $_SESSION['municipio_session'];
                                                    if(isset($datosEnvio['id_dpto'])) $muniSeleccionado = $datosEnvio['id_muni']; 
                                                    ?>

                                                    <div class="form-group select">
                                                        <select onchange="obtenerMunicipios(this, '#pedidos_municipio')" required class=" required form-control" name="pedidos_departamento" id="pedidos_departamento">
                                                            <option value="">Departamento</option>
                                                            <?php 
                                                            foreach($departamentos->result_array() as $key => $dep):
                                                                $selected = (intval($deptoSeleccionado) === intval($dep['id_departamento'])) ? 'selected' : '';
                                                                print '<option value="'.$dep['id_departamento'].'" '.$selected.'>'.$dep['departamento'].'</option>';
                                                            endforeach;
                                                            ?>

                                                        </select>
                                                    </div>

                                                    <?php                                                 
                                                    if($muniSeleccionado!==''){ ?>
                                                        <script>
                                                        var checkMun=1;
                                                            var itemDep="#pedidos_departamento";
                                                            var itemMun="#pedidos_municipio";
                                                            var valorMun=<?=$muniSeleccionado?>;
                                                        </script>
                                                    <?php } ?>

                                                    <div class="form-group select">
                                                        <!-- onchange="ubicacionCheckout('#pedidos_departamento',this);" -->
                                                        <select required class="required form-control" name="pedidos_municipio" id="pedidos_municipio">
                                                            <option value="">Ciudad / Localidad</option>
                                                            <?php
                                                            if(intval($deptoSeleccionado)>0){
                                                                $muniXdep = $mdMunicipios->getAllByIdDpto($deptoSeleccionado);
                                                                foreach($muniXdep as $muni):
                                                                    $selected = (intval($muniSeleccionado) === intval($muni['id_municipio'])) ? "selected" : "";
                                                                    print '<option value="'.$muni['id_municipio'].'" '.$selected.'>'.$muni['municipio'].'</option>';
                                                                endforeach;
                                                            }                                                                    
                                                            ?>
                                                            
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <input required class="required form-control" type="text" name="pedidos_direccion" id="pedidos_direccion" placeholder="Dirección completa" value="<?=(isset($datosEnvio['direccion']))?$datosEnvio['direccion']:''?>">
                                                    </div>
                                                    <div class="form-group">

                                                        <input required class="required form-control" type="text" name="pedidos_barrio" id="pedidos_barrio" placeholder="Barrio" value="<?=(isset($datosEnvio['barrio']))?$datosEnvio['barrio']:''?>">
                                                    </div>
                                                    <div class="form-group d-none">
                                                        <textarea class="form-control" id="pedidos_referencia" name="pedidos_referencia" rows="3" placeholder="Referencia de dirección"><?=(isset($datosEnvio['referencia']))?$datosEnvio['referencia']:''?></textarea>
                                                        <span style="font-size: 10px: color: #D93434 !important;">(Opcional)</span>
                                                    </div>
                                                    
                                                    <div class="form-group">
                                                        <input required class="required form-control" type="tel" name="pedidos_telefono" id="pedidos_telefono" placeholder="Télefono" value="<?=(isset($datos_usuario['usuarios_id']))? (isset($datosEnvio['telefono']))?$datosEnvio['telefono']:'' :''?>">
                                                    </div>
                                                    
                                                    <?php  if(isset($datos_usuario['usuarios_id'])){  ?>
                                                    <div class="form-group">
                                                        <label class="form-check-label" for="optionSaveAddress">Agregar a<a href="<?=base_url('mi-cuenta/edit-address')?>" class="btn">Mis Direcciones</a></label>
                                                        <select class="form-control" name="saveAddress">
                                                            <option value="1">Si</option>
                                                            <option value="0" selected>NO</option>
                                                        </select>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                           
                                        </div>

                                        <div class="col-12 height-20"><!-- SEPARADO --></div>   
                             
                                        <div class="col-md-11 col-12 card-metodo-pago border">
                                            <div class="row ">
                                                <div class="col-md-11 col-sm-12 interior">
                                                    <div style="height: 30px;"></div>
                                                    <div class="col-12">
                                                        <h4 class="metodo-pago-titulo">Seleccione un método de pago</h4>
                                                    </div>
                                                    <?php
                                                        foreach ($metodos_pagos->result_array() as $key2 => $value2) {
                                                            if ($value2['alma_metodos_pagos_titulo']=="Transferencia Bancaria") {
                                                        ?>
                                                    <div class="col-12" style="height: 30px;">
                                                        <hr>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="metodo-pago-label">
                                                            <img class="pr-2" src="assets/img/methods/LogoBancolombia.png" style="height:30px" alt="Banco">
                                                            <b>TRANSFERENCIA BANCOLOMBIA</b>
                                                            <input checked type="radio" name="pedidos_metodo_pago" value="0">
                                                            <!--
                                                                <p class="metodo-mensaje-check">Realiza tu pago directamente en nuestra cuenta bancaria. Por favor, usa el número del pedido como referencia de pago. Tu pedido no se procesará hasta que se haya recibido el importe en nuestra cuenta.</p>
                                                                <hr>
                                                                <div class="logo-banco">
                                                                    <img src="assets/img/methods/bancolombia_negro.png" style="width:180px;" alt="Banco">
                                                                </div>
                                                                -->
                                                        </label>
                                                    </div>
                                                    <?php 
                                                            }                                                            
                                                            if ($value2['alma_metodos_pagos_titulo']=="PSE - Payzen") { 
                                                        ?>
                                                    <div class="col-12 active">
                                                        <label class="metodo-pago-label">
                                                            <img class="pr-2" src="assets/img/methods/pse_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                            <b>PSE - Payzen</b>
                                                            <input checked type="radio" name="pedidos_metodo_pago" value="4">
                                                        </label>
                                                    </div>
                                                    <?php  
                                                            }
                                                            if ($value2['alma_metodos_pagos_titulo']=="Payzen") {
                                                        ?>
                                                    <div class="col-12">
                                                        <label>
                                                            <b>Payzen</b>
                                                            <input type="radio" name="pedidos_metodo_pago" value="1">
                                                            <hr>
                                                            <div class="logo-banco">
                                                                <img class="p-2" src="assets/img/methods/mastercard_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                <img class="p-2" src="assets/img/methods/visa_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                <img class="p-2" src="assets/img/methods/AE_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                <img class="p-2" src="assets/img/methods/dinner_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <?php  
                                                            }
                                                            if ($value2['alma_metodos_pagos_titulo']=="Mercado Pago") {
                                                        ?>
                                                    <div class="col-12" style="height: 30px;">
                                                        <hr>
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="metodo-pago-label">
                                                            <img class="p-1" src="<?=base_url('assets/img/methods/pse_nuevo.png')?>" style="height:30px" alt="Banco">
                                                            <b>PSE o Tarjeta de Crédito</b>
                                                            <input type="radio" name="pedidos_metodo_pago" value="3">
                                                            <!--
                                                                    <hr>
                                                                    <div class="logo-banco">
                                                                        <img class="p-2" src="assets/img/methods/mastercard_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                        <img class="p-2" src="assets/img/methods/visa_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                        <img class="p-2" src="assets/img/methods/AE_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                        <img class="p-1" src="assets/img/methods/dinner_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                        <img class="p-2" src="assets/img/methods/logo_mercado_pago.png?v=2" style="height:30px" alt="Banco">
                                                                        
                                                                    </div>
                                                                    -->
                                                        </label>
                                                    </div>
                                                    <?php  
                                                        }
                                                        if ($value2['alma_metodos_pagos_titulo']=="PayU") {
                                                    ?>
                                                    <div class="col-12">
                                                        <label>
                                                            <b>PayU Latam</b>
                                                            <input type="radio" name="pedidos_metodo_pago" value="2">
                                                            <hr>
                                                            <div class="logo-banco">
                                                                <img class="p-2" src="assets/img/methods/mastercard_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                <img class="p-2" src="assets/img/methods/visa_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                <img class="p-2" src="assets/img/methods/AE_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                                <img class="p-2" src="assets/img/methods/dinner_nuevo.png?v=2" style="height:30px" alt="Banco">
                                                            </div>
                                                        </label>
                                                    </div>
                                                    <?php  
                                                        }
                                                    }
                                                    ?>
                                                    <div class="col-12" style="height: 30px;">
                                                        <hr>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FINAL COLUMNA DIRECCION | METODO -->

                                <!-- INICIO COLUMNA PAGO -->
                                <div class="col-md-4 col-sm-12 ">
                                    <div class="row">  
                                        <div class="col-12 height-30 hg-3"><!-- SEPARADO --></div>                                         
                                        <div class="col-md-11 col-12 card-metodo-pago border">
                                            <div class="col-11 interior">
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
                                                        <p class="producto-item mb-0">
                                                            <?=$value['productos_titulo']?> $
                                                            <?=number_format($value['productos_precio'], 0, ',', '.')?> <b>x
                                                            <?php
                                                            echo $value['productos_cantidad'];
                                                            $thisPrice = $value['productos_precio']*$value['productos_cantidad'];
                                                            $precioTotal = $precioTotal+$thisPrice;
                                                            ?>
                                                            </b>
                                                            <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_cantidad[]">
                                                        </p>
                                                        <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_extras[]">
                                                    </div>                                                

                                                    <div class="col-6 text-right">
                                                        <p class="producto-precio bold mb-0">$
                                                            <?=number_format($thisPrice, 0, ',', '.')?>
                                                        </p>
                                                    </div>

                                                    <?php 
                                                    if(isset($value['productos_fecha_programada']) && $value['productos_fecha_programada']!==''){ 
                                                        date_default_timezone_set("America/Bogota");
                                                        setlocale(LC_TIME, "spanish");
                                                        setlocale(LC_ALL,"es_ES");
                                                        $newDate = date("d-m-Y", strtotime($value['productos_fecha_programada'])); 
                                                    ?>
                                                    <div class="col-12">
                                                        <span style="font-size: 13px;"><b>Fecha entrega:</b> <?=utf8_encode(strftime("%A %d de %B %Y", strtotime($newDate)))?></span>
                                                    </div>
                                                    <?php } ?>

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
                                                                            if ($thisUbi[0]==$_SESSION['departamento_session'] && $thisUbi[1]==$_SESSION['municipio_session']) $p_ub = 1;                                                          
                                                                        }
                                                                        if ($p_ub==1) {
                                                                            //substr($value['productos_titulo'], 0, 10)
                                                                            if (floatval($value['productos'][$i]['productos_valor_envio_local']) >= $thisLocal) {
                                                                                $thisLocal = floatval($value['productos'][$i]['productos_valor_envio_local']);
                                                                                $html.='<tr>
                                                                                        <td>'.substr('', 0, 10).'...</td>
                                                                                        <td>L</td>
                                                                                        <td class="text-right"> '.number_format($thisLocal, 0, ',', '.').'</td>
                                                                                        </tr>';
                                                                                $totalEnvios+= $thisLocal;
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
                                                                                            $html.='<tr>
                                                                                                    <td>'.substr($value['productos'][$i]['productos_titulo'], 0, 10).'...</td>
                                                                                                    <td>N</td>
                                                                                                    <td class="text-right"> '.number_format($value['productos'][$i]['productos_valor_envio_nacional'], 0, ',', '.').'</td>
                                                                                                    </tr>';
                                                                                            $totalEnvios+= $value['productos'][$i]['productos_valor_envio_nacional'];
                                                                                        }
                                                                                    }
                                                                                }
                                                                                if ($ub_0==0) {
                                                                                    array_push($cantidadMun,array(
                                                                                        "municipio" => 0,
                                                                                        "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                                                                    ));
                                                                                    /*
                                                                                    $html.='<tr>
                                                                                            <td>...</td>
                                                                                            <td>L</td>
                                                                                            <td class="text-right"> '.number_format($value['productos'][$i]['productos_valor_envio_nacional'], 0, ',', '.').'</td>
                                                                                            </tr>';
                                                                                    */
                                                                                    $totalEnvios+= $value['productos'][$i]['productos_valor_envio_nacional'];
                                                                                    
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
                                                                                                //substr($value['productos'][$i]['productos_titulo'], 0, 10)
                                                                                                $html.='<tr>
                                                                                                        <td>'.'--'.'...</td>
                                                                                                        <td>Nacional</td>
                                                                                                        <td class="text-right"> '.number_format($cantidadMun[$cantvr]['valor'], 0, ',', '.').'</td>
                                                                                                        </tr>';
                                                                                                $totalEnvios+= $cantidadMun[$cantvr]['valor'];
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
                                                                                        $html="";
                                                                                    }else{
                                                                                        $ub_0 = 0;
                                                                                        $valor_0 = 0;
                                                                                        for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                                            if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                                $ub_0 = 1;
                                                                                                if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                                    $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                                                                    $html.='<tr>
                                                                                                            <td>'.substr($value['productos'][$i]['productos_titulo'], 0, 10).'...</td>
                                                                                                            <td>Nacional</td>
                                                                                                            <td class="text-right"> $ '.number_format($value['productos'][$i]['productos_valor_envio_nacional'], 0, ',', '.').'</td>
                                                                                                            </tr>';
                                                                                                    $totalEnvios+= $value['productos'][$i]['productos_valor_envio_nacional'];
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                        if ($ub_0==0) {
                                                                                            array_push($cantidadMun,array(
                                                                                                "municipio" => 0,
                                                                                                "valor" => floatval($value['productos'][$i]['productos_valor_envio_nacional'])
                                                                                            ));
                                                                                            $html="-2-";
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
                                                                                        $productoDato=$mdProducto->single($value['productos'][$i]['productos_id']);
                                                                                        $html.='<tr>
                                                                                                <td>'.$productoDato['productos_titulo'].'</td>
                                                                                                <td>Nacional</td>
                                                                                                <td class="text-right"> $ 0</td>
                                                                                                </tr>';
                                                                                        $totalEnvios+= 0;
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
                                                                                $productoDato=$mdProducto->single($value['productos'][$i]['productos_id']);
                                                                                $html.='<tr>
                                                                                        <td>'.$productoDato['productos_titulo'].'</td>
                                                                                        <td>Nacional</td>
                                                                                        <td class="text-right"> $'.number_format($value['productos'][$i]['productos_valor_envio_nacional'], 0, ',', '.').'</td>
                                                                                        </tr>';
                                                                                $totalEnvios+= $value['productos'][$i]['productos_valor_envio_nacional'];
                                                                            }else{
                                                                                $ub_0 = 0;
                                                                                $valor_0 = 0;
                                                                                for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                                    if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                        $ub_0 = 1;
                                                                                        if(floatval($value['productos'][$i]['productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                            $cantidadMun[$ubi_0]['valor'] = floatval($value['productos'][$i]['productos_valor_envio_nacional']);
                                                                                            $html="";
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
                                                    $html.='<tr>
                                                                <td class="text-right" colspan="2"><b>TOTAL</b></td>
                                                                <td class="text-right"> $'.number_format($totalEnvios, 0, ',', '.').'</td>
                                                            </tr>';
                                                    ?>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <p class="mb-0">Subtotal</p>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        <p class="subtotal-precio bold mb-0">$<?=number_format($precioTotal, 0, ',', '.')?></p>

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
                                                        <p class="envio-precio bold mb-0"> $<span id="span_pedidos_precio_envio"><?=number_format($precioEnvio, 0, ',', '.')?></span></p>
                                                        <input type="hidden" id="pedidos_precio_envio" name="pedidos_precio_envio" value="<?=$precioEnvio?>">
                                                    </div>

                                                    <?php if(count($cantidadVendor) > 1){ ?>
                                                    <div class="col-12">
                                                        <div style="height:15px">&nbsp;</div>
                                                        
                                                        <p class='m-0' style='font-size:12px'>Su pedido está compuesto por productos de distintos origenes. <a href='#' data-toggle='modal' data-target='#modalEnvios' class='btn-yellow-alma-simple' style='font-size:11px'>ver info</a></p>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modalEnvios" tabindex="-1" role="dialog" aria-labelledby="modalEnviosLabel" aria-hidden="true">
                                                          <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                              <div class="modal-header">
                                                                <h5 class="modal-title" id="modalEnviosLabel">Relacion de envios</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                  <span aria-hidden="true">&times;</span>
                                                                </button>
                                                              </div>
                                                              <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <table id="tableEnvios" class="table table-striped table-bordered" style="width:100%">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Articulo</th>
                                                                                    <th>Envío</th>
                                                                                    <th>Valor</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <?=$html?>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                              </div>
                                                              <div class="modal-footer">
                                                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                              </div>
                                                            </div>
                                                          </div>
                                                        </div>
                                                    </div>
                                                    <?php } ?>

                                                    <div class="col-12">
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <h5 class="total-h5precio bold mb-0">Total</h5>
                                                    </div>
                                                    <div class="col-6 text-right">
                                                        
                                                        <h5 class="total-h5precio bold mb-0 precioTotal">$<?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></h5>
                                                        <input type="hidden" name="pedidos_precio_subtotal" id="pedidos_precio_subtotal" value="<?=$precioTotal?>">
                                                        <input type="hidden" name="pedidos_precio_total" value="<?=$precioTotal+$precioEnvio?>">
                                                    </div>
                                                </div>
                                                <div class="col-12 checkout-boton-next text-center py-4">
                                                    <!--<button id="btn-next-pedido" onclick="nextCheck();" type="button" class="btn btn-green-alma w-100 text-white uppercase">Continuar al pago</button> -->
                                                    <button <?=(!isset($datosEnvio['id_dir']))?'disabled':'';?> onclick="loadedbutton('#btn-relizar-pedido');$('#form-checkout').submit();" type="button" id="btn-relizar-pedido" class="btn <?=(!isset($datosEnvio['id_dir']))?'btn-secondary':'btn-green-alma';?> btn-lg uppercase">Pagar pedido <span class="precioTotal">$<?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></span></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FINAL COLUMNA PAGO -->

                            </div>
                        </div>
                        <?php
                            //}
                            ?>
                    </div>
                </form>
                <?php
                    }else{ ?>
                <div class="col-12 text-center">
                    <h5>Aún no haz agregado nada a tu carrito</h5>
                    <a href="tienda">Regresar a la tienda.</a>
                </div>
                <?php
                    }
                }else{ ?>
                <div class="col-12 text-center">
                    <h5>Aún no haz agregado nada a tu carrito</h5>
                    <a href="tienda">Regresar a la tienda.</a>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php if(isset($datos_usuario['direcciones'])){ ?>
<!-- INICIO - Direcciones Usuario -->
<form action="<?=base_url('checkout/dirsel')?>" method="post" id="formListAddress">
    <div class="modal fade" id="mdCambiarDireccion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 style="font-family: 'Raleway', sans-serif;">Elige una dirección de envío</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span style="color:red">x</span></button>
                </div>
                <div class="modal-body" style="font-family: 'Raleway', sans-serif;">
                    <div class="container">
                        <div class="row">
                            <?php foreach($datos_usuario['direcciones'] as $key => $dir): ?>
                            <div class="col-11 card-metodo-pago border row">
                                <div class="col-1 text-center">
                                    <input class="checkadlc" type="radio" name="rdDir" value="<?=$dir['id_dir']?>" <?=(intval($dir['prede'])===1)?'checked':''?> required>
                                </div>
                                <div class="col-10">
                                    <b>
                                    <?=$dir['nombre']?></b><br>
                                    <?=$dir['nombre']?><br>
                                    <?=$dir['direccion']?><br>
                                    <?=getDepartamentoById($dir['id_dpto'])?>,
                                    <?=getMunicipioById($dir['id_muni'])?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="row">
                            <div class="col-12 text-center">
                                <a href="#" class="btn-green-alma-simple" data-toggle="modal" data-target="#mdNuevaDireccion" data-dismiss="modal">+ Agregar nueva dirección</a>
                            </div>
                            <div class="col-12">&nbsp;</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center">
                    <button type="submit" class="btn w-180 btn-green-alma text-white uppercase">Continuar</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- FINAL - Direcciones Usuario -->
<?php } ?>

<!-- INICIO - Nueva Direccion Usuario -->
<form action="<?=base_url('checkout/addDireccion')?>" method="post" id="formNewAddress">
    <div class="modal fade" id="mdNuevaDireccion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <div class="d-none d-sm-none d-md-block"><h5 class="modal-title" id="exampleModalLabel"><b>Ingresa una dirección de envío</b></h5></div>
                    <div class="d-block d-sm-block d-md-none"><b>Ingresa una dirección de envío</b></div>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span style="color:red">x</span></button>
                </div>          
                <div class="modal-body">
                    <div class="row">  
                        <?php                         
                        if(isset($this->session->userdata('login_user')['usuarios_id'])){
                            $idUserLogin = $this->session->userdata('login_user')['usuarios_id'];
                        }else{
                            $idUserLogin = (isset($datos_usuario['usuarios_id']))?$datos_usuario['usuarios_id']:'';
                        }
                        ?>
                        <input type="hidden" name="id_usuario" value="<?=(isset($this->session->userdata('login_user')['usuarios_id']))?:''?>"> 

                        <div class="col-12 <?=($idUserLogin==='')?'':'d-none';?>">
                            <div class="form-group">
                                <input required class="form-control " type="text" name="dni" id="dni" placeholder="Identificación" value="<?=$idUserLogin;?>">
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <input required class="form-control" type="text" name="persona" placeholder="Nombre de persona que recibe" value="">
                            </div>                           
                            <div class="form-group">
                                <select onchange="obtenerMunicipios(this, '#pedidos_municipio_2')" required class="form-control" name="id_dpto" id="pedidos_departamento_2">
                                    <option value="">Departamento</option>
                                    <?php foreach ($departamentos->result_array() as $key => $value) { ?>
                                    <option value="<?=$value['id_departamento']?>"><?=$value['departamento']?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <select onchange="ubicacionCheckout('#pedidos_departamento_2',this);" required class="form-control" name="id_muni" id="pedidos_municipio_2">
                                    <option value="">Ciudad / Localidad-</option>
                                </select>
                            </div>
                        </div>
                        <div class="d-none d-sm-none d-md-block">
                            <div class="col-12"> <label><b>Dirección</b></label> </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="form-group">
                                <input required class="form-control" type="text" name="direccion" placeholder="Dirección completa" value="">
                            </div>
                            <div class="form-group">
                                <input required class="form-control" type="text" name="barrio" placeholder="Barrio" value="">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="referencia" rows="3" placeholder="Referencia o indicaciones para llegar a la dirección (Opcional)"></textarea>
                            </div>  
                            <div class="form-group">
                                <input required class="form-control" type="text" name="telefono" placeholder="Telefono ó numero celular" value="">
                            </div>            

                            <div class="form-group">
                                <input required class="form-control" type="text" name="nombre" id="nombre" placeholder="Nombre o Referencia, Eje: Mi Casa" value="">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" value="1" name="prede" id="prede">
                                <label class="form-check-label" for="flexCheckDefault">Hacer predeterminado</label>
                            </div>
                        </div>  
                        <div class="col-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-green-alma text-white uppercase w-100">Guardar</button>
                            </div>
                        </div>

                    </div>
                    

                </div>
                
            </div>
        </div>
    </div>
</form>
<!-- FINAL - Nueva Direccion Usuario -->

<script type="text/javascript"> 
    fbq('track', 'InitiateCheckout', { content_ids: [<?=$content_ids?>], contents: [<?=$contents?>], num_items: <?=$num_items?>, currency: 'COP', value: <?=$value_pixel?> }); 
    <?php //$html = preg_replace('"', "'", $html); ?>
    var html='<?=trim(preg_replace("/[\r\n|\n|\r]+/", "", $html));?>';
</script>
<style>
body {
    overflow-x: initial !important;
}
</style>

