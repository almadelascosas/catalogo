<?php //if (isMobile()) { ?>
                            <!--
                        <div class="checkout-modulos col-12">
                            <div class="row">
                                <div class="col-12 checkout-modulo checkout-mod-envio" id="checkout-mod-envio">
                                    <h4 class="text-center"><?=(isset($datosEnvio['id_dir']))?'Elige':'Ingresa'?> una dirección de envío</h4>
                                    <?php if (isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']!=NULL ) { ?>
                                    <input type="hidden" name="pedidos_usuarios_id" id="pedidos_usuarios_id" value="<?=$_SESSION['usuarios_id']?>">
                                    <?php } ?>

                                    <?php if(isset($datosEnvio['id_dir'])){ ?>
                                    <div class="col-12 px-5 card-metodo-pago">
                                        <div class="row">
                                            <div class="col-12">
                                                <label><b><?=$datosEnvio['nombre']?></b></label>
                                                <?=$datosEnvio['direccion']?><br />
                                                <?=$datosEnvio['barrio']?><br />
                                                <?=getDepartamentoById($datosEnvio['id_dpto'])?>, <?=getMunicipioById($datosEnvio['id_muni'])?>
                                            </div>
                                            <?php if(isset($countDir) && $countDir>1){ ?>
                                            <div class="col-12">
                                                <a href="<?=base_url('checkout/mis-direcciones')?>">Elegir otra direccion</a>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>                          

                                    <div class="col-12 mt-3 mb-4">
                                        <input class="check_otra_direccion" id="check_otra_direccion" type="checkbox">
                                        <input class="" id="pedidos_direccion_envio_conf" name="pedidos_direccion_envio_conf" type="hidden" value="0">
                                        <label for="check_otra_direccion capitalize normal">
                                            ¿Enviar a una dirección diferente?
                                        </label>
                                    </div>
                                    <?php } ?>


                                    <div id="div_otra_direccion" class="<?=(isset($datosEnvio['id_dir']))?'d-none':'';?> px-0 col-12">
                                        <div class="form-group">
                                            <input required class="required form-control" type="email" name="pedidos_correo" id="pedidos_correo" placeholder="Correo" value="<?=(isset($datos_usuario['usuarios_id']))?$datos_usuario['email']:''?>">
                                        </div>
                                        <div class="form-group">
                                            <input required class="required form-control" type="tel" name="pedidos_identificacion" id="pedidos_identificacion" placeholder="Identificación" value="<?=(isset($datos_usuario['usuarios_id']))?$datos_usuario['dni']:''?>">
                                        </div>
                                        <div class="form-group">
                                            <input required class="required form-control" type="text" name="pedidos_nombre" id="pedidos_nombre" placeholder="Nombre completo" value="<?=(isset($datos_usuario['usuarios_id']))?(isset($datosEnvio['persona']))?$datosEnvio['persona']:$datos_usuario['name'].' '.$datos_usuario['lastname']:''?>">
                                        </div>
                                        <!-- 
                                        <div class="form-group select">
                                            <select class="form-control required" name="pedidos_pais" id="pedidos_pais" required>
                                                <option value="0">Colombia</option>
                                            </select>
                                        </div> 
                                        -->
                                        <div class="col-12 mb-0">
                                            <p class="bold">Dirección</p>
                                        </div>
                                        <div class="form-group select">
                                            <select onchange="obtenerMunicipios(this,'#pedidos_municipio')" required class=" required form-control" name="pedidos_departamento" id="pedidos_departamento">
                                                <option value="">Departamento</option>
                                                <?php
                                                foreach ($departamentos->result_array() as $key => $value) { 
                                                    $DptoSeleccionado='';
                                                    if(isset($_SESSION['departamento_session'])) $DptoSeleccionado= $_SESSION['departamento_session'];
                                                    if(isset($datosEnvio['id_dpto'])) $DptoSeleccionado= $datosEnvio['id_dpto'];                                                    
                                                ?>
                                                <option <?=($DptoSeleccionado!=='' && $DptoSeleccionado==$value['id_departamento'])?"selected":'';?> value="<?=$value['id_departamento']?>">
                                                    <?=$value['departamento']?>
                                                </option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <?php
                                        $muniSeleccionado='';
                                        if(isset($_SESSION['municipio_session'])) $muniSeleccionado= $_SESSION['municipio_session'];
                                        if(isset($datosEnvio['id_dpto'])) $muniSeleccionado= $datosEnvio['id_muni']; 

                                        if($muniSeleccionado!==''){ ?>
                                        <script>
                                            var checkMun=1;
                                                var itemDep="#pedidos_departamento";
                                                var itemMun="#pedidos_municipio";
                                                var valorMun=<?=$muniSeleccionado?>;
                                            </script>
                                        <?php } ?>
                                        <div class="form-group select">
                                            <select onchange="ubicacionCheckout('#pedidos_departamento',this);" required class="required form-control" name="pedidos_municipio" id="pedidos_municipio">
                                                <option value="">Ciudad / Localidad</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <input required class="required form-control" type="tel" name="pedidos_barrio" id="pedidos_barrio" placeholder="Barrio" value="<?=(isset($datosEnvio['barrio']))?$datosEnvio['barrio']:''?>">
                                        </div>
                                        <div class="form-group">
                                            <input required class="required form-control" type="text" name="pedidos_direccion" id="pedidos_direccion" placeholder="Dirección completa, Eje: Cra. X Cll X, Casa #XX-XX, Apto XX" value="<?=(isset($datosEnvio['direccion']))?$datosEnvio['direccion']:''?>">
                                        </div>
                                        <div class="form-group">
                                            <input required class="required form-control" type="tel" name="pedidos_telefono" id="pedidos_telefono" placeholder="Télefono" value="<?=(isset($datos_usuario['usuarios_id']))?(isset($datosEnvio['telefono']))?$datosEnvio['telefono']:$datos_usuario['phone']:''?>">
                                        </div>
                                    </div>
                                    <!--
                                    <div id="div_otra_direccion" class="d-none px-0 col-12">
                                        <div class="col-12 mb-3 px-0">
                                            <input class="form-control" type="text" name="pedidos_correo_envio" id="pedidos_correo_envio" placeholder="Nombre completo">
                                        </div>
                                        <div class="col-12 mb-3 select px-0">
                                            <select class="form-control" name="pedidos_pais_envio" id="pedidos_pais_envio">
                                                <option value="Colombia">Colombia</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3 px-0">
                                            <input class="form-control mb-3" type="text" name="pedidos_direccion_envio" id="pedidos_direccion_envio" placeholder="Dirección de la calle">
                                        </div>
                                        <div class="col-12 mb-3 select px-0">
                                            <select onchange="obtenerMunicipios(this,'#pedidos_municipio_envio')" class="form-control" name="pedidos_departamento_envio" id="pedidos_departamento_envio">
                                                <option value="">Departamento</option>
                                                <?php //foreach ($departamentos->result_array() as $key => $value) { ?>
                                                <option value="<?php //print $value['id_departamento']?>"> <?php //print $value['departamento']?> </option>
                                                <?php //} ?>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3 select px-0">
                                            <select onchange="ubicacionCheckout('#pedidos_departamento_envio',this);" class="form-control" name="pedidos_municipio_envio" id="pedidos_municipio_envio">
                                                <option value="">Localidad / Ciudad</option>
                                            </select>
                                        </div>
                                        <div class="col-12 mb-3 px-0">
                                            <input class="form-control" type="text" name="pedidos_codigo_postal_envio" id="pedidos_codigo_postal_envio" placeholder="Código Postal (Opcional)">
                                        </div>
                                    </div>
                                    -->
                                </div>
                                
                                <div class="checkout-modulo col-12 d-none checkout-mod-pago" id="checkout-mod-pago">
                                    <div class="w-100" id="resume-cart">
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
                                                    <p class="producto-item">
                                                        <?=$value['productos_titulo']?> $
                                                        <?=number_format($value['productos_precio'], 0, ',', '.')?> <strong>x
                                                            <?php
                                                        echo $value['productos_cantidad'];
                                                        $thisPrice = $value['productos_precio']*$value['productos_cantidad'];
                                                        $precioTotal = $precioTotal+$thisPrice;
                                                        ?></strong>
                                                        <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_cantidad[]">
                                                    </p>
                                                    <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_extras[]">
                                                </div>
                                                <div class="col-6 text-right">
                                                    <p class="producto-precio bold">$
                                                        <?=number_format($thisPrice, 0, ',', '.')?>
                                                    </p>
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
                                                    <p class="subtotal-precio bold">$
                                                        <?=number_format($precioTotal, 0, ',', '.')?>
                                                    </p>
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
                                                    <p class="envio-precio bold"> FIJO: $
                                                        <?=number_format($precioEnvio, 0, ',', '.')?>
                                                    </p>
                                                    <input type="hidden" name="pedidos_precio_envio" value="<?=$precioEnvio?>">
                                                </div>
                                                <div class="col-12">
                                                    <hr>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-6">
                                                    <h5 class="total-h5precio bold">Total</h5>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <?php
                                                        if (count($cantidadVendor) > 1) {
                                                            echo "<p class='m-0'>Su pedido está compuesto por productos de distintos origenes.</p>";
                                                        }
                                                        ?>
                                                    <h5 class="total-h5precio bold">$
                                                        <?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?>
                                                    </h5>
                                                    <input type="hidden" name="pedidos_precio_subtotal" value="<?=$precioTotal?>">
                                                    <input type="hidden" name="pedidos_precio_total" value="<?=$precioTotal+$precioEnvio?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 checkout-boton-next text-center py-4">
                            <?php if(intval($datos_faltantes)===0){ ?>
                            <button id="btn-next-pedido" onclick="nextCheck();" type="button" class="btn btn-green-alma text-white uppercase w-100">Continuar al pago</button>
                            <?php } ?>
                        </div>
                        <div class="col-12 checkout-boton-submit d-none text-center py-4 caja-btn-fixed-bottom">
                            <button onclick="loadedbutton('#btn-relizar-pedido');$('#form-checkout').submit();" type="button" id="btn-relizar-pedido" class="btn w-100 btn-green-alma text-white uppercase">Pagar pedido $
                                <?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></button>
                        </div>