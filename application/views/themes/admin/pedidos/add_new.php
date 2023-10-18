<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <?php
            if ($pedido['pedido']['pedidos_id']=="" || $pedido['pedido']['pedidos_id']==0 || $pedido['pedido']['pedidos_id']==NULL) { ?>
            <span class="wcfm-page-heading-text">Añadir Pedido</span>
            <?php
            }else { ?>
                <span class="wcfm-page-heading-text">Actualizar Pedido</span>
            <?php
            }
            ?>
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="<?=base_url()?>assets/img/LOGO_ALMA.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfmfa fa-bell"></i>
                <span class="unread_notification_count message_count">0</span>
                <div class="notification-ring"></div>
                </a>
                </div>
        </div>
        <form class="col-12 my-5 contact-form" action="<?=base_url('pedidos/guardar_new')?>" method="post">
            <div class="wcfm-container simple variable external grouped booking">
                <?php if(isset($_SESSION['message_tipo'])){ ?>
                <div class="alert alert-<?=($_SESSION['message_tipo']=="success")?'success':'danger'?>" role="alert">
                    <?=$_SESSION['message']?>
                </div>
                <?php 
                    unset($_SESSION['message_tipo'], $_SESSION['message']);
                } 
                ?>
                <div class="col-12">
                    <div class="row">
                        <input type="hidden" name="pedidos_id" id="pedidos_id" value="<?=$pedido['pedido']['pedidos_id']?>">
                        <input type="hidden" name="pedidos_estatus_anterior" id="pedidos_estatus_anterior" value="<?=$pedido['pedido']['pedidos_estatus']?>">
                        <input type="hidden" name="pedidos_fecha_creacion" id="pedidos_fecha_creacion" value="<?=$pedido['pedido']['pedidos_fecha_creacion']?>">
                        <?php
                        if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <p>FECHA DEL PEDIDO: <?=$pedido['pedido']['pedidos_fecha_creacion']?></p>
                                    <p>
                                        ESTADO DEL PEDIDO : 
                                        <select name="pedidos_estatus" id="pedidos_estatus">
                                            <?php
                                            if ($pedido['pedido']['pedidos_estatus']=="Esperando confirmación de Pago" || $pedido['pedido']['pedidos_estatus']=="Esperando confirmación de pago" || $pedido['pedido']['pedidos_estatus']==1){ ?>
                                            <option <?php if($pedido['pedido']['pedidos_estatus']=="Esperando confirmación de Pago" || $pedido['pedido']['pedidos_estatus']=="Esperando confirmación de pago" || $pedido['pedido']['pedidos_estatus']==1){ echo "selected"; }  ?> value="Esperando confirmación de Pago">Esperando confirmación de Pago</option>
                                            <?php
                                            }
                                            if ($pedido['pedido']["pedidos_estatus"]!="Enviado") { ?>
                                            <option <?php if($pedido['pedido']['pedidos_estatus']==6 || $pedido['pedido']['pedidos_estatus']=="Rechazado" ){ echo "selected"; }?> value="Rechazado">Rechazado</option>
                                            <option <?php if($pedido['pedido']['pedidos_estatus']=="Pendiente"){ echo "selected"; }  ?> value="Pendiente">Pendiente</option>
                                            <option <?php if($pedido['pedido']['pedidos_estatus']=="Cancelado"){ echo "selected"; }  ?> value="Cancelado">Cancelado</option>
                                            <option <?php if($pedido['pedido']['pedidos_estatus']==4 || $pedido['pedido']['pedidos_estatus']=="En preparación" ){ echo "selected"; } ?> value="En preparación">En preparación</option>
                                            <?php
                                            } else{ ?>
                                                <option <?php if($pedido['pedido']['pedidos_estatus']=="Enviado"){ echo "selected"; } ?> value="Enviado">Enviado</option>
                                                <?php
                                                if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                                                <option <?php if($pedido['pedido']['pedidos_estatus']==4 || $pedido['pedido']['pedidos_estatus']=="En preparación" ){ echo "selected"; } ?> value="En preparación">En preparación</option>
                                                <?php
                                                }
                                            }
                                            ?>
                                        </select>

                                        <br>
                                        Pago a través de <?php
                                        if ($pedido['pedido']['pedidos_metodo_pago']==0) {
                                            echo "Transferencia Bancaria";
                                        }elseif ($pedido['pedido']['pedidos_metodo_pago']==1) {
                                            echo "Payzen";
                                        }elseif ($pedido['pedido']['pedidos_metodo_pago']==2) {
                                            echo "PayU Latam";
                                        }elseif ($pedido['pedido']['pedidos_metodo_pago']==3) {
                                            echo "MercadoPago";
                                        }else{
                                            echo $pedido['pedido']['pedidos_metodo_pago'];
                                        }
                                        ?>.</p>
                                    </p>
                                </div>
                                <div class="col-md-6 col-12">
                                    <h4>Detalles de facturación</h4>
                                    
                                    <div class="form-group">
                                        <label for="pedidos_identificacion">Identificación <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_identificacion" value="<?=$pedido['pedido']['pedidos_identificacion']?>" id="pedidos_identificacion">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_nombre">Nombre Completo <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_nombre" id="pedidos_nombre" value="<?=$pedido['pedido']['pedidos_nombre']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_direccion">Dirección de la calle <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_direccion" id="pedidos_direccion" value="<?=$pedido['pedido']['pedidos_direccion']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_barrio">Barrio <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_barrio" id="pedidos_barrio" value="<?=$pedido['pedido']['pedidos_barrio']?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pedidos_departamento">Departamento <sup style="color:red;">*</sup></label>
                                        <input disabled required class="form-control" type="text" name="" id="pedidos_departamento" value="<?=$pedido['pedido']['departamento']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_municipio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                        <input disabled required class="form-control" type="text" name="" id="pedidos_municipio" value="<?=$pedido['pedido']['municipio']?>">
                                    </div>
                                    
                                    
                                    <!-- <div class="form-group d-none">
                                        <label for="pedidos_codigo_postal">Código Postal (Opcional)</label>
                                        <input class="form-control" type="text" name="pedidos_codigo_postal" id="pedidos_codigo_postal" value="<?=$pedido['pedido']['pedidos_codigo_postal']?>">
                                    </div> -->
                                    
                                    <div class="form-group">
                                        <label for="pedidos_telefono">Télefono <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_telefono" id="pedidos_telefono" value="<?=$pedido['pedido']['pedidos_telefono']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_correo">Correo <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="email" name="pedidos_correo" id="pedidos_correo" value="<?=$pedido['pedido']['pedidos_correo']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_barrio">Persona quien recibe: <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_nombre_envio" id="pedidos_nombre_envio" value="<?=$pedido['pedido']['pedidos_nombre_envio']?>">
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 mt-md-0 mt-5">
                                    <div class="col-12 mt-5 mb-4">
                                        <h4>Detalles de envío</h4>
                                        <input <?php if($pedido['pedido']['pedidos_direccion_envio_conf']==1){ echo "checked"; } ?> class="check_otra_direccion" id="check_otra_direccion" type="checkbox">
                                        <input class="" id="pedidos_direccion_envio_conf" name="pedidos_direccion_envio_conf" type="hidden" value="<?=$pedido['pedido']['pedidos_direccion_envio_conf']?>">
                                        <label for="check_otra_direccion">
                                            ¿ENVIAR A UNA DIRECCIÓN DIFERENTE?
                                        </label>
                                    </div>
                                    <div id="div_otra_direccion" class="<?php if($pedido['pedido']['pedidos_direccion_envio_conf']==0){ echo "d-none"; } ?> col-12">
                                        <div class="form-group">
                                            <label for="pedidos_correo_envio">Nombre Completo<sup style="color:red;">*</sup></label>
                                            <input class="form-control required" type="text" name="pedidos_correo_envio" id="pedidos_correo_envio" value="<?=$pedido['pedido']['pedidos_correo_envio']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_correo_envio">Barrio<sup style="color:red;">*</sup></label>
                                            <input class="form-control required" type="text" name="pedidos_barrio_envio" id="pedidos_barrio_envio" value="<?=$pedido['pedido']['pedidos_barrio_envio']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_direccion_envio">Dirección de la calle <sup style="color:red;">*</sup></label>
                                            <input class="required form-control mb-3" type="text" name="pedidos_direccion_envio" id="pedidos_direccion_envio" value="<?=$pedido['pedido']['pedidos_direccion_envio']?>">
                                            <input class="form-control" type="text" name="pedidos_nro_habitacion_envio" id="pedidos_nro_habitacion_envio" value="<?=$pedido['pedido']['pedidos_nro_habitacion_envio']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_municipio_envio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                            <input  disabled class="required form-control" type="text" name="" id="pedidos_municipio_envio" value="<?=$pedido['pedido']['municipio_envio']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_departamento_envio">Departamento <sup style="color:red;">*</sup></label>
                                            <input  disabled class="required form-control" type="text" name="" id="pedidos_departamento_envio" value="<?=$pedido['pedido']['departamento_envio']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_codigo_postal_envio">Código Postal (Opcional)</label>
                                            <input class="form-control" type="text" name="pedidos_codigo_postal_envio" id="pedidos_codigo_postal_envio" value="<?=$pedido['pedido']['pedidos_codigo_postal_envio']?>">
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        
                        }else{ ?>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <p>FECHA DEL PEDIDO: <?=$pedido['pedido']['pedidos_fecha_creacion']?></p>
                                    <p>
                                        <input type="hidden" id="pedidos_estatus" name="pedidos_estatus" value="<?=$pedido['pedido']['pedidos_estatus']?>">
                                        ESTADO DEL PEDIDO :
                                        <?php 
                                        if($pedido['pedido']['pedidos_estatus']==6){ 
                                            echo "<span class='order-status order-status-refunded'>Rechazado</span>"; 
                                        }elseif ($pedido['pedido']['pedidos_estatus']==4) {
                                            echo "<span class='order-status order-status-processing'>En Preparación</span>"; 
                                        }else {
                                            switch ($pedido['pedido']['pedidos_estatus']) {
                                                case 'Enviado':
                                                    echo "<span class='order-status order-status-completed'>".$pedido['pedido']['pedidos_estatus']."</span>";
                                                    break;
                                                case 'En preparación':
                                                    echo "<span class='order-status order-status-processing'>".$pedido['pedido']['pedidos_estatus']."</span>";
                                                    break;
                                                case 'Esperando confirmación de pago':
                                                    echo "<span class='order-status order-status-on-hold'>".$pedido['pedido']['pedidos_estatus']."</span>";
                                                    break;
                                                case 'Esperando confirmación de Pago':
                                                    echo "<span class='order-status order-status-on-hold'>".$pedido['pedido']['pedidos_estatus']."</span>";
                                                    break;
                                                case 1:
                                                    echo "<span class='order-status order-status-on-hold'>Esperando confirmación de Pago</span>";
                                                    break;
                                                case 'Cancelado':
                                                        echo "<span class='order-status order-status-cancelled'>".$pedido['pedido']['pedidos_estatus']."</span>";
                                                    break;
                                                default:
                                                    echo "<span class='order-status order-status-on-hold'>".$pedido['pedido']['pedidos_estatus']."</span>";
                                                    break;
                                            }
                                        }
                                        ?>
                                    </p> 
                                    <p>
                                        Pago a través de <?php
                                        if ($pedido['pedido']['pedidos_metodo_pago']==0) {
                                            echo "Transferencia Bancaria";
                                        }elseif ($pedido['pedido']['pedidos_metodo_pago']==1) {
                                            echo "Payzen";
                                        }elseif ($pedido['pedido']['pedidos_metodo_pago']==2) {
                                            echo "PayU Latam";
                                        }elseif ($pedido['pedido']['pedidos_metodo_pago']==3) {
                                            echo "MercadoPago";
                                        }else{
                                            echo $pedido['pedido']['pedidos_metodo_pago'];
                                        }
                                        ?>.
                                    </p>
                                </div>
                                <div class="col-md-6 col-12">
                                    <h4>Detalles de facturación</h4>
                                    
                                    <div class="form-group">
                                        <label for="pedidos_identificacion">Identificación <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_identificacion']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_nombre">Nombre Completo <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_nombre']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_direccion">Dirección de la calle <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_direccion']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_barrio">Barrio <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_barrio']?></p>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pedidos_departamento">Departamento <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['departamento']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_municipio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['municipio']?></p>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label for="pedidos_codigo_postal">Código Postal (Opcional)</label>
                                        <p><?php //echo $pedido['pedido']['pedidos_codigo_postal']?></p>
                                    </div> -->

                                    <div class="form-group">
                                        <label for="pedidos_telefono">Télefono <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_telefono']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_correo">Correo <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_correo']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_barrio">Persona quien recibe: <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_nombre_envio']?></p>
                                    </div>
                                </div>
                                <?php 
                                if($pedido['pedido']['pedidos_direccion_envio_conf']==1){ ?>
                                <div class="col-md-6 col-12 mt-md-0 mt-5">
                                    <div class="col-12 mt-5 mb-4">
                                        <h4>Detalles de envío</h4>
                                    </div>
                                    <div id="div_otra_direccion" class="<?php if($pedido['pedido']['pedidos_direccion_envio_conf']==0){ echo "d-none"; } ?> col-12">
                                        <div class="form-group">
                                            <label for="pedidos_correo_envio">Nombre Completo <sup style="color:red;">*</sup></label>
                                            <p><?=$pedido['pedido']['pedidos_correo_envio']?></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_pais_envio">Barrio <sup style="color:red;">*</sup></label>
                                            <p><?=$pedido['pedido']['pedidos_barrio']?></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_direccion_envio">Dirección de la calle <sup style="color:red;">*</sup></label>
                                            <p>
                                            <?=$pedido['pedido']['pedidos_direccion_envio']?><br>
                                            <?=$pedido['pedido']['pedidos_nro_habitacion_envio']?>
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_departamento_envio">Departamento <sup style="color:red;">*</sup></label>
                                            <p><?=$pedido['pedido']['departamento_envio']?></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_municipio_envio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                            <p><?=$pedido['pedido']['municipio_envio']?></p>
                                        </div>
                                        <!-- <div class="form-group">
                                            <label for="pedidos_codigo_postal_envio">Código Postal (Opcional)</label>
                                            <p><?php //print $pedido['pedido']['pedidos_codigo_postal_envio']?></p>
                                        </div> -->

                                    </div>
                                </div>
                                <?php
                                } ?>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    
                        <div class="col-12 card">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <p>Artículos del pedido</p>
                                    </div>
                                </div>
                                

                            </div>
                            <div class="card-body">
                                <table class="table table-alma" width="100%">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Artículo</th>
                                            <th>Estado</th>
                                            <th>Coste</th>
                                            <th>Cantidad</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $total_vendedor=0;
                                        $conteo=0;

                                        foreach ($pedido['pedidos_productos'] as $key2 => $value2):
                                            $conteo++;
                                            if ($value2['pedidos_detalle_producto_cantidad'] > 0){
                                                foreach ($pedido['productos'] as $key => $value):
                                                    if ($value2['pedidos_detalle_producto']===$value['productos_id']) {
                                                        $img=delBackslashIni($value['medios_enlace_miniatura']);
                                                        if(!file_exists($img)) $img=delBackslashIni($value['medios_url']);
                                                        if(!file_exists($img)) $img='assets/img/Not-Image.png';

                                        ?>
                                                    <tr class="<?=(intval($this->session->userdata('login_user')['tipo_accesos'])!==0 && intval($this->session->userdata('login_user')['tipo_accesos'])!==1 && $value['productos_vendedor']!==$this->session->userdata('login_user')['usuarios_id'])?"d-none":""; ?>">
                                                        <td>
                                                            <img style="max-width:50px;" class="miniatura" src="<?=$img.'?'.rand()?>" alt="Image">
                                                        </td>
                                                        <td>
                                                            <a href="<?=base_url('productos/editar/'.$value['productos_id'].'/'.limpiarUri($value['productos_titulo']))?>"><?=$value['productos_titulo']?></a>
                                                            <br>
                                                            <span>(<?=$value['productos_sku']?>)</span>
                                                            <p class="addons">                                                                
                                                            <?php
                                                            $addons = str_replace("\n"," ", $value2['pedidos_detalle_producto_addons']);
                                                            $addons = str_replace("],{","]},{", $addons);
                                                            $addons = str_replace("  "," ", $addons);
                                                            $addons = preg_replace("[\n|\r|\n\r]"," ", $addons);
                                                            if ($_SESSION['usuarios_id']==9){ debug($addons); }

                                                            $addons = json_decode($addons);
                                                            if ($value2['pedidos_detalle_producto_addons']!="}]}" && $addons!=NULL) {
                                                                foreach ($addons as $key15 => $value15):
                                                                    for ($i15=0; $i15 < count($value15); $i15++) { 
                                                                        ?>
                                                                        <p><strong><?=$value15[$i15]->addons_titulo?></strong></p>
                                                                        <?php
                                                                        if (isset($value15[$i15]->addons_respuesta)) {
                                                                            for ($resp=0; $resp < count($value15[$i15]->addons_respuesta); $resp++) {
                                                                                echo "<p>";
                                                                                echo $value15[$i15]->addons_respuesta[$resp];
                                                                                if (isset($value15[$i15]->addons_valor[$resp])) {
                                                                                    if (intval($value15[$i15]->addons_valor[$resp]) > 0) {
                                                                                        echo " + ".number_format($value15[$i15]->addons_valor[$resp], 0, ',', '.');
                                                                                    }
                                                                                }
                                                                                echo "</p>";
                                                                            }
                                                                        }
                                                                    }
                                                                endforeach;
                                                            }
                                                            ?>
                                                            </p>
                                                            <b>Vendedor: </b> <?=$value2['nombreVendedor'];?>
                                                            <?php 
                                                            if($value2['pedidos_detalle_fechaprogramada']!=='' && $value2['pedidos_detalle_fechaprogramada']!==null && $value2['pedidos_detalle_fechaprogramada']!=='0000-00-00'){ 
                                                                date_default_timezone_set("America/Bogota");
                                                                setlocale(LC_TIME, "spanish");
                                                                setlocale(LC_ALL,"es_ES");
                                                                $newDate = date("d-m-Y", strtotime($value2['pedidos_detalle_fechaprogramada'])); 
                                                            ?>
                                                            <br><span style="color:#F97B7B"><b>Fecha programada: </b><?=utf8_encode(strftime("%A %d de %B %Y", strtotime($newDate)))?></span>
                                                            <?php } ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <input type="hidden" name="posicion[]" value="<?=$conteo?>">
                                                            <input type="hidden" name="addons[]" value="<?=$value2['pedidos_detalle_producto']?>">
                                                            <input type="hidden" name="estatus_productos_producto_id[]" id="" value="<?=$value['productos_id']?>">
                                                            <?php
                                                            $this_estatus = "";
                                                            $this_guia = "";
                                                            $this_empresa = "";
                                                            $coordinadora_estado = "";
                                                            $coordinadora_guia = "";
                                                            $coordinadora_seguimiento = "";
                                                            
                                                            foreach ($pedido['estatus']->result_array() as $key3 => $value3) {
                                                                if ($value3['productos_id']==$value['productos_id']) {
                                                                    $this_estatus = $value3['estatus'];
                                                                    $this_guia = $value3['nro_guia'];
                                                                    $this_empresa = $value3['nombre_empresa'];
                                                                    $coordinadora_estado = $value3['coordinadora_estado'];
                                                                    $coordinadora_guia = $value3['coordinadora_guia'];
                                                                    $coordinadora_seguimiento = $value3['coordinadora_seguimiento'];
                                                                }
                                                            }
                                                            
                                                            if (intval($pedido['pedido']['pedidos_estatus'])===1 
                                                                || $pedido['pedido']['pedidos_estatus']==="Esperando confirmación de pago" 
                                                                || $pedido['pedido']['pedidos_estatus']==="Esperando confirmación de Pago" 
                                                                || intval($pedido['pedido']['pedidos_estatus'])===4 
                                                                || $pedido['pedido']['pedidos_estatus']==="En preparación" 
                                                                || $pedido['pedido']['pedidos_estatus']==="Enviado" 
                                                                || $pedido['pedido']['pedidos_estatus']==="Reembolsado") 
                                                            { 

                                                            ?>
                                                            <a class="btn-edit-guia <?=($this_estatus!=="Enviado")?"d-none":"";?>" href="javascript:void(0)" data-toggle="modal" data-target="#ModalGuia_<?=$value['productos_id']?>">Editar Guia</a>
                                                            <select name="pedidos_productos_estatus[]" class="<?=(intval($pedido['pedido']['pedidos_estatus'])!==4 && $pedido['pedido']['pedidos_estatus']!=="En preparación" && $pedido['pedido']['pedidos_estatus']!=="Enviado" && $pedido['pedido']['pedidos_estatus']!=="Reembolsado")?"d-none":""; ?> pedidos_productos_estatus form-control" data-modal-id="<?=$value['productos_id']?>" onchange="popEmpresa(this);">
                                                                <option value="">Seleccione...</option>
                                                                <?php  if (intval($this->session->userdata('login_user')['tipo_accesos'])===0 || intval($this->session->userdata('login_user')['tipo_accesos'])===1){ ?>
                                                                <option <?=($this_estatus==="En preparación")?"selected":"";?> value="En preparación">En preparación</option>
                                                                <option <?=($this_estatus==="Enviado")?"selected":"";?> value="Enviado">Enviado</option>
                                                                <option <?=($this_estatus==="Reembolsado")?"selected":"";?> value="Reembolsado">Reembolsado</option>
                                                                <?php }else if($this_estatus!=="Reembolsado" && $this_estatus!=="Enviado"){ ?>
                                                                <option <?=($this_estatus==="En preparación")?"selected":"";?> value="En preparación">En preparación</option>
                                                                <option <?=($this_estatus==="Enviado")?"selected":"";?> value="Enviado">Enviado</option>
                                                                <?php
                                                                }else{
                                                                    print '<option selected value="'.$this_estatus.'">'.$this_estatus.'</option>';
                                                                }
                                                                ?>
                                                            </select>
                                                            <?php } ?>
                                                            
                                                            <input type="hidden" name="coordinadora_guia[]" id="coordinadora_guia" value="<?=$coordinadora_guia?>">
                                                            <input type="hidden" name="coordinadora_seguimiento[]" id="coordinadora_seguimiento" value="<?=$coordinadora_seguimiento?>">

                                                            <div class="modal fade" id="ModalGuia_<?=$value['productos_id']?>" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="ModalGuiaLabel" aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                                    <div class="modal-content">
                                                                        <div class="card p-5">
                                                                            <div class="form-group">
                                                                                <label for="nro_guia">Nro. de Guía<sup class="text-danger">*</sup></label>
                                                                                <input type="text" class="form-control" name="nro_guia[]" value="<?=$this_guia?>">
                                                                                <!-- <input type="text" id="nro_guia" class="form-control" value=""> -->
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label for="nombre_empresa">Nombre de la empresa transportadora<sup class="text-danger">*</sup></label>
                                                                                <!-- <input type="text" id="nombre_empresa" class="form-control" value=""> -->
                                                                                <input type="text" class="form-control" name="nombre_empresa[]" value="<?=$this_empresa?>">
                                                                            </div>
                                                                            <div class="col-12">
                                                                                <p class="text-right">
                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Continuar</button>
                                                                                    <!-- <button onclick="editarGuia();" class="btn btn-default" id="btnGuiaEditar" type="button">Continuar</button> -->
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        
                                                        </td>
                                                        <td><b>$</b><?=number_format($value2['pedidos_detalle_producto_precio'], 0, ',', '.')?></td>
                                                        <td>
                                                            <?php
                                                            $this_price = 0;
                                                            echo " x ".$value2['pedidos_detalle_producto_cantidad']."<br>";
                                                            $this_price = $value2['pedidos_detalle_producto_precio']*$value2['pedidos_detalle_producto_cantidad'];
                                                            ?>
                                                        </td>
                                                        <td><b>$</b><?=number_format($this_price, 0, ',', '.')?></td>
                                                        <?php
                                                        if (intval($_SESSION['tipo_accesos'])===1 || intval($_SESSION['tipo_accesos'])===0) {
                                                            $total_vendedor = $total_vendedor+$this_price;
                                                        }else{
                                                            if($_SESSION['usuarios_id']==$value['productos_vendedor']){ $total_vendedor = $total_vendedor+$this_price; }
                                                        }
                                                        ?>
                                                    </tr>
                                        <?php
                                                    }
                                                endforeach;
                                            }
                                        endforeach;                                        
                                        ?>
                                        <tr>
                                            <td colspan="5" class="text-right">
                                                <strong>Envío:</strong>
                                            </td>
                                            <td>
                                            <?php
                                            $totalPedido = 0;
                                            if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) {
                                                $totalPedido = $pedido['pedido']['pedidos_precio_total'];
                                                echo "$ ".number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.');
                                            }else{
                                                $precioEnvio = 0;
                                                $thisLocal = 0;
                                                $thisNacional = 0;
                                                $cantidadMun = array();
                                                $pedido_departamento = 0;
                                                $pedido_municipio = 0;
                                                if ($pedido['pedido']['pedidos_direccion_envio_conf']==1) {
                                                    $pedido_departamento = $pedido['pedido']['pedidos_departamento_envio'];
                                                    $pedido_municipio = $pedido['pedido']['pedidos_municipio_envio'];
                                                }else{
                                                    $pedido_departamento = $pedido['pedido']['pedidos_departamento'];
                                                    $pedido_municipio = $pedido['pedido']['pedidos_municipio'];
                                                }
                                                foreach ($pedido['pedidos_productos'] as $key => $value) {
                                                    /* Verificación de envíos nacionales */
                                                    if ($value['pedidos_detalle_productos_envio_nacional']==1) {
                                                        /* Verificación de valor de envío nacional gratis */
                                                        if ($value['pedidos_detalle_productos_valor_envio_nacional'] > 0) {
                                                            if (isset($pedido_departamento) && isset($pedido_municipio)) {
                                                                /* Verificación del municipio session del cliente */
                                                                $tubicaciones = explode("/",$value['pedidos_detalle_productos_ubicaciones_envio']);
                                                                $p_ub = 0;
                                                                for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                                    $thisUbi = explode(",",$tubicaciones[$i2]);
                                                                    if ($thisUbi[0]==$pedido_departamento && $thisUbi[1]==$pedido_municipio) {
                                                                        $p_ub = 1;
                                                                    }                                                            
                                                                }
                                                                if ($p_ub==1) {
                                                                    if (floatval($value['pedidos_detalle_productos_valor_envio_local']) >= $thisLocal) {
                                                                        $thisLocal = floatval($value['pedidos_detalle_productos_valor_envio_local']);
                                                                    }
                                                                }else{
                                                                    if ($value['pedidos_detalle_productos_ubicaciones_envio']=="") {
                                                                        /* Verificación de municipio 0 */
                                                                        $ub_0 = 0;
                                                                        $valor_0 = 0;
                                                                        for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                            if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                $ub_0 = 1;
                                                                                if(floatval($value['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                    $cantidadMun[$ubi_0]['valor'] = floatval($value['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($ub_0==0) {
                                                                            array_push($cantidadMun,array(
                                                                                "municipio" => 0,
                                                                                "valor" => floatval($value['pedidos_detalle_productos_valor_envio_nacional'])
                                                                            ));
                                                                        }
                                                                    }else{
                                                                        /* Verificación de ubicaciones entre productos */
                                                                        $tubicaciones = explode("/",$value['pedidos_detalle_productos_ubicaciones_envio']);
                                                                        $p_ub = 0;
                                                                        for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                                            $thisUbi = explode(",",$tubicaciones[$i2]);
                                                                            for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                                                                if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                                                    $p_ub = 1;
                                                                                    if (floatval($value['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                                                        $cantidadMun[$cantvr]['valor'] = floatval($value['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                    }       
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($p_ub==0) {
                                                                            $thisUbi = explode(",",$tubicaciones[0]);
                                                                            if (isset($thisUbi[1])) {
                                                                                array_push($cantidadMun,array(
                                                                                    "municipio" => $thisUbi[1],
                                                                                    "valor" => floatval($value['pedidos_detalle_productos_valor_envio_nacional'])
                                                                                ));
                                                                            }else{
                                                                                $ub_0 = 0;
                                                                                $valor_0 = 0;
                                                                                for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                                    if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                        $ub_0 = 1;
                                                                                        if(floatval($value['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                            $cantidadMun[$ubi_0]['valor'] = floatval($value['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                        }
                                                                                    }
                                                                                }
                                                                                if ($ub_0==0) {
                                                                                    array_push($cantidadMun,array(
                                                                                        "municipio" => 0,
                                                                                        "valor" => floatval($value['pedidos_detalle_productos_valor_envio_nacional'])
                                                                                    ));
                                                                                }       
                                                                            }
                                                                        }

                                                                    }
                                                                }

                                                            }else{
                                                                /* Verificación de municipios entre productos */
                                                                $tubicaciones = explode("/",$value['pedidos_detalle_productos_ubicaciones_envio']);
                                                                $p_ub = 0;
                                                                for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                                    $thisUbi = explode(",",$tubicaciones[$i2]);
                                                                    for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                                                        if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                                            $p_ub = 1;
                                                                            if (floatval($value['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                                                $cantidadMun[$cantvr]['valor'] = floatval($value['pedidos_detalle_productos_valor_envio_nacional']);
                                                                            }       
                                                                        }
                                                                    }
                                                                }
                                                                if ($p_ub==0) {
                                                                    $thisUbi = explode(",",$tubicaciones[0]);
                                                                    if (isset($thisUbi[1])) {
                                                                        array_push($cantidadMun,array(
                                                                            "municipio" => $thisUbi[1],
                                                                            "valor" => floatval($value['pedidos_detalle_productos_valor_envio_nacional'])
                                                                        ));
                                                                    }else{
                                                                        $ub_0 = 0;
                                                                        $valor_0 = 0;
                                                                        for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                            if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                $ub_0 = 1;
                                                                                if(floatval($value['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                    $cantidadMun[$ubi_0]['valor'] = floatval($value['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($ub_0==0) {
                                                                            array_push($cantidadMun,array(
                                                                                "municipio" => 0,
                                                                                "valor" => floatval($value['pedidos_detalle_productos_valor_envio_nacional'])
                                                                            ));
                                                                        }       
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        $tubicaciones = explode("/",$value['pedidos_detalle_productos_ubicaciones_envio']);
                                                        $p_ub = 0;
                                                        for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                            $thisUbi = explode(",",$tubicaciones[$i2]);
                                                            for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                                                if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                                    $p_ub = 1;
                                                                    if (floatval($value['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                                        $cantidadMun[$cantvr]['valor'] = floatval($value['pedidos_detalle_productos_valor_envio_nacional']);
                                                                    }       
                                                                }
                                                            }
                                                        }
                                                        if ($p_ub==0) {
                                                            $thisUbi = explode(",",$tubicaciones[0]);
                                                            if (isset($thisUbi[1])) {
                                                                array_push($cantidadMun,array(
                                                                    "municipio" => $thisUbi[1],
                                                                    "valor" => floatval($value['pedidos_detalle_productos_valor_envio_nacional'])
                                                                ));
                                                            }else{
                                                                $ub_0 = 0;
                                                                $valor_0 = 0;
                                                                for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                    if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                        $ub_0 = 1;
                                                                        if(floatval($value['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                            $cantidadMun[$ubi_0]['valor'] = floatval($value['pedidos_detalle_productos_valor_envio_nacional']);
                                                                        }
                                                                    }
                                                                }
                                                                if ($ub_0==0) {
                                                                    array_push($cantidadMun,array(
                                                                        "municipio" => 0,
                                                                        "valor" => floatval($value['pedidos_detalle_productos_valor_envio_nacional'])
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
                                                echo "$ ".number_format($precioEnvio, 0, ',', '.');
                                                $totalPedido=$precioEnvio+$total_vendedor;
                                            }
                                            ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="5" class="text-right">
                                                <strong>Total:</strong> 
                                            </td>
                                            <td><?php echo "$ ".number_format($totalPedido, 0, ',', '.'); ?></td>
                                        </tr>
                                    </tbody>    
                                </table>
                            </div>
                            <div class="w-100 py-4 text-right">
                                <?php
                                if ($pedido['pedido']['pedidos_id']=="" || $pedido['pedido']['pedidos_id']==0 || $pedido['pedido']['pedidos_id']==NULL) { ?>
                                <button type="submit" class="btn btn-default py-1 btn-sm">Publicar</button>
                                <?php
                                }else {
                                    if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                                        <button type="submit" class="btn btn-default btn-sm">Guardar Cambios</button>
                                        <?php
                                    }else{
                                        if ($pedido['pedido']['pedidos_estatus']==1 || $pedido['pedido']['pedidos_estatus']=='4' || $pedido['pedido']['pedidos_estatus']=='En Preparación' || $pedido['pedido']['pedidos_estatus']=="En preparación" || $pedido['pedido']['pedidos_estatus']=="Enviado" || $pedido['pedido']['pedidos_estatus']=="Reembolsado") { ?>
                                        <button type="submit" class="btn btn-default btn-sm">Guardar Cambios</button>
                                        <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 py-4">
                    <div class="row">
                        <?php
                        $datos['notas_internas'] = $notas_internas; 
                        $this->load->view("themes/admin/pedidos/notas_internas", $datos); 
                        ?>
                    </div>
                </div>

            
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="ModalGallery" tabindex="-1" role="dialog" aria-labelledby="ModalGalleryLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content" id="content-gallery">

    </div>
  </div>
</div>

<!--  
<div class="modal fade" id="ModalGuia" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="ModalGuiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="card p-5">
                <div class="form-group">
                    <label for="nro_guia">Nro. de Guía<sup class="text-danger">*</sup></label>
                    <input type="text" id="nro_guia" class="form-control" value="">
                </div>
                <div class="form-group">
                    <label for="nombre_empresa">Nombre de la empresa transportadora<sup class="text-danger">*</sup></label>
                    <input type="text" id="nombre_empresa" class="form-control" value="">
                </div>
                <div class="col-12">
                    <p class="text-right">
                        <button onclick="editarGuia();" class="btn btn-default" id="btnGuiaEditar" type="button">Continuar</button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
-->

<div class="modal fade" id="ModalEnPreparacion" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="ModalGuiaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="card p-5">
                <div class="row">
                    <div class="col-12"><h3>Elige opcion de envio</h3></div>
                </div>
                <div class="row">
                    <div class="col-6 text-center">
                        <i class="fa fa-archive fa-5x" aria-hidden="true"></i>
                        <br>
                        <button onclick="dejarPreparacion();" class="btn btn-default" type="button">Por mi cuenta</button>
                    </div>
                    <div class="col-6 text-center">
                        <i class="fa fa-truck fa-5x" aria-hidden="true"></i>
                        <br>
                        <button onclick="envioCoordinadora();" class="btn btn-default" id="btnGuiaEditar" type="button">Por Coordinadora</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>