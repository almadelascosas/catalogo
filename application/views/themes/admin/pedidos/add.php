<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text"><?=($pedido['pedido']['pedidos_id']=="" || $pedido['pedido']['pedidos_id']==0 || $pedido['pedido']['pedidos_id']==NULL)?'Añadir':'Actualizar';?> Pedido</span>
             
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="https://almadelascosas.com/wp-content/plugins/wc-frontend-manager/assets/images/user.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfmfa fa-bell"></i>
                <span class="unread_notification_count message_count">0</span>
                <div class="notification-ring"></div>
                </a>
                </div>
        </div>
        <form enctype="multipart/form-data" id="form-pedido" class="col-12 my-5 contact-form" action="<?=base_url()?>pedidos/guardar" method="post">
            <div class="wcfm-container simple variable external grouped booking">
                <?php
                
                if (isset($_SESSION['message_tipo'])) {
                    if ($_SESSION['message_tipo']=="success") { ?>
                    <div class="alert alert-success" role="alert">
                    <?=$_SESSION['message']?>
                </div>
                <?php
                    }
                    if ($_SESSION['message_tipo']=="error") { ?>
                    <div class="alert alert-danger" role="alert">
                    <?=$_SESSION['message']?>
                    </div>
                    <?php
                    }
                }
                unset($_SESSION['message_tipo']);
                unset($_SESSION['message']);
                ?>
                <div class="col-12">
                    <div class="row">
                        <input type="hidden" name="pedidos_id" id="pedidos_id" value="<?=$pedido['pedido']['pedidos_id']?>">
                        <input type="hidden" name="pedidos_fecha" id="pedidos_fecha" value="<?=$pedido['pedido']['pedidos_fecha']?>">
                        <input type="hidden" name="pedidos_productos" id="pedidos_productos" value="<?=$pedido['pedido']['pedidos_productos']?>">
                        
                        <?php
                        if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <p>FECHA DEL PEDIDO: <?=$pedido['pedido']['pedidos_fecha']?></p>
                                    <p>
                                        ESTADO DEL PEDIDO : 
                                        <select name="pedidos_estatus" id="pedidos_estatus">
                                            <?php
                                            if ($pedido['pedido']["pedidos_estatus"]!="Enviado") { ?>
                                            <option <?php if($pedido['pedido']['pedidos_estatus']==6 || $pedido['pedido']['pedidos_estatus']=="Rechazado" ){ echo "selected"; }?> value="Rechazado">Rechazado</option>
                                            <option <?php if($pedido['pedido']['pedidos_estatus']=="Esperando confirmación de Pago"){ echo "selected"; }  ?> value="Esperando confirmación de Pago">Esperando confirmación de Pago</option>
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
                                        Pago a través de <?=$pedido['pedido']['pedidos_metodo_pago']?>.</p>
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
                                        <label for="pedidos_pais">País / Región <sup style="color:red;">*</sup></label>
                                        <select class="form-control"  name="pedidos_pais" id="pedidos_pais">
                                            <option value="Colombia">Colombia</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_direccion">Dirección de la calle <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_direccion" id="pedidos_direccion" value="<?=$pedido['pedido']['pedidos_direccion']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_localidad">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                        <input disabled required class="form-control" type="text" name="" id="pedidos_localidad" value="<?=$pedido['pedido']['municipio']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_departamento">Departamento <sup style="color:red;">*</sup></label>
                                        <input disabled required class="form-control" type="text" name="" id="pedidos_departamento" value="<?=$pedido['pedido']['departamento']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_codigo_postal">Código Postal (Opcional)</label>
                                        <input class="form-control" type="text" name="pedidos_codigo_postal" id="pedidos_codigo_postal" value="<?=$pedido['pedido']['pedidos_codigo_postal']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_telefono">Télefono <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="text" name="pedidos_telefono" id="pedidos_telefono" value="<?=$pedido['pedido']['pedidos_telefono']?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_correo">Correo <sup style="color:red;">*</sup></label>
                                        <input required class="form-control" type="email" name="pedidos_correo" id="pedidos_correo" value="<?=$pedido['pedido']['pedidos_correo']?>">
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
                                            <label for="pedidos_correo_envio">NOMBRE COMPLETO <sup style="color:red;">*</sup></label>
                                            <input class="form-control required" type="text" name="pedidos_correo_envio" id="pedidos_correo_envio" value="<?=$pedido['pedido']['pedidos_correo_envio']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_pais_envio">País / Región <sup style="color:red;">*</sup></label>
                                            <select class="form-control"  name="pedidos_pais_envio" id="pedidos_pais_envio">
                                                <option value="Colombia">Colombia</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_direccion_envio">Dirección de la calle <sup style="color:red;">*</sup></label>
                                            <input class="required form-control mb-3" type="text" name="pedidos_direccion_envio" id="pedidos_direccion_envio" value="<?=$pedido['pedido']['pedidos_direccion_envio']?>">
                                            <input class="form-control" type="text" name="pedidos_nro_habitacion_envio" id="pedidos_nro_habitacion_envio" value="<?=$pedido['pedido']['pedidos_nro_habitacion_envio']?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_localidad_envio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                            <input  disabled class="required form-control" type="text" name="" id="pedidos_localidad_envio" value="<?=$pedido['pedido']['municipio_envio']?>">
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
                                    <p>FECHA DEL PEDIDO: <?=$pedido['pedido']['pedidos_fecha']?></p>
                                    <p>
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
                                        <input type="hidden" name="pedidos_estatus" value="<?=$pedido['pedido']['pedidos_estatus']?>">
                                    <p>
                                        Pago a través de <?=$pedido['pedido']['pedidos_metodo_pago']?>.
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
                                        <label for="pedidos_pais">País / Región <sup style="color:red;">*</sup></label>
                                        <p>Colombia</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_direccion">Dirección de la calle <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_direccion']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_departamento">Departamento <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['departamento']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_localidad">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['municipio']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_codigo_postal">Código Postal (Opcional)</label>
                                        <p><?=$pedido['pedido']['pedidos_codigo_postal']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_telefono">Télefono <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_telefono']?></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="pedidos_correo">Correo <sup style="color:red;">*</sup></label>
                                        <p><?=$pedido['pedido']['pedidos_correo']?></p>
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
                                            <label for="pedidos_correo_envio">NOMBRE COMPLETO <sup style="color:red;">*</sup></label>
                                            <p><?=$pedido['pedido']['pedidos_correo_envio']?></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_pais_envio">País / Región <sup style="color:red;">*</sup></label>
                                            <p>Colombia</p>
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
                                            <label for="pedidos_localidad_envio">Localidad / Ciudad <sup style="color:red;">*</sup></label>
                                            <p><?=$pedido['pedido']['municipio_envio']?></p>
                                        </div>
                                        <div class="form-group">
                                            <label for="pedidos_codigo_postal_envio">Código Postal (Opcional)</label>
                                            <p><?=$pedido['pedido']['pedidos_codigo_postal_envio']?></p>
                                        </div>

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
                                <p>Artículos del pedido</p>
                            </div>
                            <div class="card-body">
                                <table class="table table-alma">
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
                                        foreach ($pedido['pedidos_productos']->result_array() as $key2 => $value2) {
                                            if ($value2['pedidos_productos_cantidad'] > 0) {
                                                foreach ($pedido['productos']->result_array() as $key => $value) {
                                                    if ($value2['pedidos_productos_producto_id']==$value['productos_id']) {
                                                        ?>
                                                    <tr class="<?php if ($_SESSION['tipo_accesos']!=0 && $_SESSION['tipo_accesos']!=1 && $value['productos_vendedor']!=$_SESSION['usuarios_id']) { echo "d-none"; } ?>">
                                                        <td>
                                                            <img style="max-width:50px;" class="miniatura" src="<?=base_url().$value['medios_url']?>" alt="Image">
                                                        </td>
                                                        <td>
                                                            <a href="<?=base_url()?>/productos/editar/<?=$value['productos_id']?>/<?=limpiarUri($value['productos_titulo'])?>"><?=$value['productos_titulo']?></a>
                                                            <p class="addons">
                                                                
                                                            <?php
                                                            $addons = explode("],[",$value2['pedidos_productos_addons']);
                                                            for ($i=0; $i < count($addons); $i++) {
                                                                $childs = explode("/,/", $addons[$i]);
                                                                foreach ($pedido['addons']->result_array() as $key3 => $value3) {
                                                                    if ($value3['addons_id']==$childs[0]) {
                                                                        echo "<p><strong>".$value3['addons_titulo'].":</strong></p>";
                                                                    }
                                                                }
                                                                if (isset($childs[1])) {
                                                                    
                                                                    if ($childs[1]=="short_text" || $childs[1]=="long_text") {
                                                                        if (!isset($childs[3]) || $childs[3]=="") {
                                                                            echo "<p>---</p>";
                                                                        }else{
                                                                            echo "<p>".$childs[3]."</p>";
                                                                        }
                                                                    }elseif ($childs[1]=="multiple") {
                                                                        if ($childs[2]=="dropdowns") {
                                                                            if (isset($childs[3]) && $childs[3]!=NULL && $childs[3]!="") {
                                                                                $opcionSel = explode(",",$childs[3]);
                                                                                $seleccion = str_replace("opcion-","",$opcionSel);
                                                                                echo "<p>";
                                                                                if (isset($opcionSel[1]) && $opcionSel[1]!="") {
                                                                                    echo $opcionSel[1];
                                                                                }
                                                                                if (isset($opcionSel[3]) && $opcionSel[3]!="") {
                                                                                    echo "+ $ ".number_format($opcionSel[3], 0, ',', '.');
                                                                                }
                                                                                echo "</p>";
                                                                            }else{
                                                                                echo "<p>---</p>";
                                                                            }
                                                                            
                                                                        }else{
                                                                            if (isset($childs[3]) && $childs[3]!=NULL && $childs[3]!="") {
                                                                                $seleccion = str_replace("/./opcion-","",$childs[3]);
                                                                                $seleccion = floatval($seleccion);
                                                                                echo "<p>";
                                                                                foreach ($pedido['addons']->result_array() as $key3 => $value3) {
                                                                                    if ($value3['addons_id']==$childs[0]) {
                                                                                        $opciones = explode("],[", $value3['addons_opciones']);
                                                                                        if (isset($opciones[$seleccion]) && $opciones[$seleccion]!="") {
                                                                                            $valores = explode("/,/", $opciones[$seleccion]);
                                                                                            echo $valores[0];
                                                                                            if (isset($valores[2]) && $valores[2]!="") {
                                                                                                echo "+ $ ".number_format($valores[2], 0, ',', '.');
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                echo "</p>";
                                                                            }else{
                                                                                echo "<p>---</p>";
                                                                            }
                                                                        }
                                                                        
                                                                    }elseif ($childs[1]=="checkboxes") {
                                                                        
                                                                        if (isset($childs[3]) && $childs[3]!=NULL && $childs[3]!="") {
                                                                            $seleccionados = explode("/./", $childs[3]);
                                                                            
                                                                            for ($i2=1; $i2 < count($seleccionados); $i2++) {
                                                                                $seleccion = str_replace("opcion-","",$seleccionados[$i2]);
                                                                                $seleccion = floatval($seleccion);
                                                                                echo "<p>";
                                                                                foreach ($pedido['addons']->result_array() as $key3 => $value3) {
                                                                                    if ($value3['addons_id']==$childs[0]) {
                                                                                        $opciones = explode("],[", $value3['addons_opciones']);
                                                                                        if (isset($opciones[$seleccion]) && $opciones[$seleccion]!="") {
                                                                                            $valores = explode("/,/", $opciones[$seleccion]);
                                                                                            echo $valores[0];
                                                                                            if (isset($valores[2]) && $valores[2]!="") {
                                                                                                echo "+ $ ".number_format($valores[2], 0, ',', '.');
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                                
                                                                                echo "</p>";
                                                                            }
                                                                        }else{
                                                                            echo "<p>---</p>";
                                                                        }
                                                                    }    
                                                                    
                                                                }
                                                            }
                                                            
                                                            ?>
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <input type="hidden" name="addons[]" value="<?=$value2['pedidos_productos_id']?>">
                                                            <input type="hidden" name="estatus_productos_producto_id[]" value="<?=$value['productos_id']?>">
                                                            <?php
                                                            $this_estatus = "";
                                                            $this_guia = "";
                                                            $this_empresa = "";
                                                            
                                                            foreach ($pedido['estatus']->result_array() as $key3 => $value3) {
                                                                if ($value3['productos_id']==$value['productos_id'] && $value3['addons']==$value2['pedidos_productos_id']) {
                                                                    $this_estatus = $value3['estatus'];
                                                                    $this_guia = $value3['nro_guia'];
                                                                    $this_empresa = $value3['nombre_empresa'];
                                                                }
                                                            }
                                                            
                                                            if ($pedido['pedido']['pedidos_estatus']==4 || $pedido['pedido']['pedidos_estatus']=="En preparación" || $pedido['pedido']['pedidos_estatus']=="Enviado" || $pedido['pedido']['pedidos_estatus']=="Reembolsado") { ?>
                                                            <a class="btn-edit-guia <?php if($this_estatus!="Enviado"){ echo "d-none"; } ?>" href="#" onclick="popEmpresa($(this).siblings('.pedidos_productos_estatus'))">Editar Guia</a>
                                                            <select name="pedidos_productos_estatus[]"  class="pedidos_productos_estatus form-control" onchange="popEmpresa(this);">
                                                                <?php
                                                                if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                                                                <option <?php if($this_estatus=="En preparación"){ echo "selected"; } ?> value="En preparación">En preparación</option>
                                                                <option <?php if($this_estatus=="Enviado"){ echo "selected"; } ?> value="Enviado">Enviado</option>
                                                                <option <?php if($this_estatus=="Reembolsado"){ echo "selected"; } ?> value="Reembolsado">Reembolsado</option>
                                                                <?php
                                                                }else{
                                                                    if ($this_estatus!="Reembolsado" && $this_estatus!="Enviado") { 
                                                                    ?>
                                                                <option <?php if($this_estatus=="En preparación"){ echo "selected"; } ?> value="En preparación">En preparación</option>
                                                                <option <?php if($this_estatus=="Enviado"){ echo "selected"; } ?> value="Enviado">Enviado</option>
                                                                <?php
                                                                    }else{ ?>
                                                                        <option selected value="<?=$this_estatus?>"><?=$this_estatus?></option>
                                                                    <?php
                                                                    }
                                                                }
                                                                ?>
                                                            </select>
                                                            <input type="hidden" name="nro_guia[]" value="<?=$this_guia?>">
                                                            <input type="hidden" name="nombre_empresa[]" value="<?=$this_empresa?>">
                                                            <?php
                                                            }
                                                            ?>
                                                        </td>
                                                        <td>$ <?=number_format($value2['pedidos_productos_precio'], 0, ',', '.')?></td>
                                                        <td>
                                                            <?php
                                                            $this_price = 0;
                                                            echo " x ".$value2['pedidos_productos_cantidad']."<br>";
                                                            $this_price = $value2['pedidos_productos_precio']*$value2['pedidos_productos_cantidad'];
                                                            ?>
                                                        </td>
                                                        <td>$ <?=number_format($this_price, 0, ',', '.')?></td>
                                                        <?php
                                                        if ($_SESSION['tipo_accesos']==1 || $_SESSION['tipo_accesos']==0) {
                                                            $total_vendedor = $total_vendedor+$this_price;
                                                        }else{
                                                            if ($_SESSION['usuarios_id']==$value['productos_vendedor']) {
                                                                $total_vendedor = $total_vendedor+$this_price;
                                                            }
                                                        }
                                                        ?>
                                                    </tr>
                                                    <?php
                                                    }
                                                }
                                            }
                                        }
                                        
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
                                                echo "$ 9.900";
                                                $totalPedido=9900+$total_vendedor;
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
                        </div>
                    </div>
                </div>
            
                <div class="col-12 mt-4 text-right">
                    <?php
                    if ($pedido['pedido']['pedidos_id']=="" || $pedido['pedido']['pedidos_id']==0 || $pedido['pedido']['pedidos_id']==NULL) { ?>
                    <button onclick="valFormPedido();" type="submit" class="btn btn-default py-1 btn-sm">Publicar</button>
                    <?php
                    }else {
                        if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                            <button onclick="valFormPedido();" type="submit" class="btn btn-default btn-sm">Guardar Cambios</button>
                            <?php
                        }else{
                            if ($pedido['pedido']['pedidos_estatus']==1 || $pedido['pedido']['pedidos_estatus']=='4' || $pedido['pedido']['pedidos_estatus']=='En Preparación' || $pedido['pedido']['pedidos_estatus']=="En preparación" || $pedido['pedido']['pedidos_estatus']=="Enviado" || $pedido['pedido']['pedidos_estatus']=="Reembolsado") { ?>
                            <button onclick="valFormPedido();" type="submit" class="btn btn-default btn-sm">Guardar Cambios</button>
                            <?php
                            }
                        }
                        ?>
                        <?php
                    }
                    ?>
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
                        <button onclick="editarGuia();" class="btn btn-default" id="btnGuiaEditar" type="button">
                            Continuar
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>