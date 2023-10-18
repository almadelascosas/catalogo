<?php
if (isset($_REQUEST['page']) && $_REQUEST['page']!=NULL ) {
    $page = $_REQUEST['page'];
    $pageNext = $page+1;
    $pagePrev = $page-1;
}else{
    $page = 1;
    $pageNext = 2;
    $pagePrev = 0;
}
$uri = explode("?",$_SERVER["REQUEST_URI"]);
$sinreq = $uri[0];
$varequest = "";
$varequestPrev = "";
$conteo = 0;
foreach ($_REQUEST as $key => $value) {
    if ($key!="ci_session") {
        $conteo++;
        if (is_array($value)) {
            if ($conteo==1) {
                $conteo2=0;
                foreach ($value as $key2 => $value2) {
                    $conteo2++;
                    if ($conteo2==1) {
                        $varequest.=$key."%5B%5D=".$value2;
                        $varequestPrev.=$key."%5B%5D=".$value2;
                    }else{
                        $varequest.="&".$key."%5B%5D=".$value2;
                        $varequestPrev.="&".$key."%5B%5D=".$value2;
                    }
                }
            }else{
                foreach ($value as $key2 => $value2) {
                    $varequest.="&".$key."%5B%5D=".$value2;
                    $varequestPrev.="&".$key."%5B%5D=".$value2;
                }
            }
        }else{
            if ($conteo==1) {
                if ($key=="page") {
                    $nvalue=$value+1;
                    $varequest.=$key."=".$nvalue;
                    $pvalue=$value-1;
                    $varequestPrev.=$key."=".$pvalue;
                }else{
                    $varequest.=$key."=".$value;
                    $varequestPrev.=$key."=".$value;
                }
            }else{
                if ($key=="page") {
                    $nvalue=$value+1;
                    $varequest.="&".$key."=".$nvalue;
                    $pvalue=$value-1;
                    $varequestPrev.="&".$key."=".$pvalue;
                }else{
                    $varequest.="&".$key."=".$value;
                    $varequestPrev.="&".$key."=".$value;
                }
            }
        }
    }
}
if (array_key_exists('page',$_REQUEST)) {
    /*
    $urlActualNextPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequest;
    $urlActualPrevPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequestPrev;
    */

    $urlActualNextPage = base_url($sinreq."?".$varequest);
    $urlActualPrevPage = base_url($sinreq."?".$varequestPrev);
}else{
    if ($varequest!="") {
        /*
        $urlActualNextPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequest."&page=2";
        $urlActualPrevPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequestPrev;
        */
        $urlActualNextPage = base_url($sinreq."?".$varequest."&page=2");
        $urlActualPrevPage = base_url($sinreq."?".$varequestPrev);
    }else{
        /*
        $urlActualNextPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?page=2";
        $urlActualPrevPage = "https://".$_SERVER["HTTP_HOST"].$sinreq;
        */
        $urlActualNextPage = base_url($sinreq."?page=2");
        $urlActualPrevPage = base_url($sinreq);
    }
}
?>

<div class="content">
    <div class="col-12">
        <div class="wcfm-page-headig">
            <span class="wcfmfa fa-chalkboard"></span>
            <span class="wcfm-page-heading-text">Pedidos</span>
            <span class="wcfm_menu_toggler wcfmfa fa-bars text_tip" data-tip="Mostrar / ocultar el menú" data-hasqtip="0"></span>
            <span class="wcfm-store-name-heading-text"><a href="#" target="_blank">Mi tienda</a></span>
            <div class="wcfm_header_panel">
                <a href="#" class="wcfm_header_panel_profile ">
                    <img class="wcfm_header_panel_profile_img  text_tip" src="<?=base_url()?>assets/img/LOGO_ALMA.png" data-tip="Perfil " data-hasqtip="1">
                </a>
                <a href="#" class="wcfm_header_panel_messages text_tip " data-tip="Tablero de avisos" data-hasqtip="2">
                <i class="wcfmfa icon-bell"></i>
                <span class="unread_notification_count message_count">0</span>
                <div class="notification-ring"></div>
                </a>
            </div>	
        </div>
        <div class="col-12 my-5">
            <div class="wcfm-container simple variable external grouped booking">
                <div class="heading">
                    <div class="col-12">
                        <div class="row">
                            
                            <?php
                            if (intval($_SESSION['tipo_accesos'])===1) { ?>
                            <div class="col-12">
                                <!-- <form class="col-12" method="post" action="<?php //prin base_url('pedidos/exportarPedidosNew')?>"> -->
                                <form class="col-12" method="post" action="<?=base_url('pedidos/exportarPedidosMejorado')?>">
                                    <input type="hidden" id="baseurl" name="baseurl" value="<?=base_url()?>">
                                    <div class="row">
                                        <div class="form-group col-4">
                                            <input type="date" name="pedidos_fecha_inicio" id="pedidos_fecha_inicio" class="form-control">
                                        </div>
                                        <div class="form-group col-4">
                                            <input type="date" name="pedidos_fecha_final" id="pedidos_fecha_final" class="form-control" readonly>
                                        </div>
                                        <div class="form-group col-2">
                                            <button class="ml-2 btn btn-success" type="submit">Exportar Pedidos</button>
                                        </div>
                                    </div>
                                </form>
                                <!-- 
                                <form class="col-12 mt3" method="post" action="<?=base_url('pedidos/exportarPedidos')?>">
                                    <div class="row">
                                        <div class="form-group col-6">
                                            <label for="pedidos_fecha_inicio_ant">Fecha Inicio</label>
                                            <input type="date" name="pedidos_fecha_inicio" id="pedidos_fecha_ant" class="form-control">
                                        </div>
                                        <div class="form-group col-6">
                                            <label for="pedidos_fecha_final_ant">Fecha Final</label>
                                            <input type="date" name="pedidos_fecha_final" id="pedidos_fecha_final_ant" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <button class="ml-2 btn btn-success" type="submit">Exportar Pedidos</button>
                                        </div>
                                    </div>
                                </form>
                                -->

                            </div>
                            <?php } ?>

                            <div class="col-6 text-left">
                                <label for="">
                                    30
                                    <!--<select name="" id="">
                                        <option value="">25</option>
                                        <option value="">50</option>
                                        <option value="">75</option>
                                        <option value="">100</option>
                                    </select>-->
                                    Items por página 
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-sm-6 col-md-3">
                                <a onclick="actualizarPedidosMercadoPago()" class="btn btn-success my-4" href="javascript:void(0)">Actualizar Pedidos</a>
                            </div>  
                            <div class="col-sm-6 col-md-9">
                                <div class="span1 d-none">
                                    <i class="fa fa-refresh fa-spin fa-3x fa-fw" style="color:red" ></i>
                                    <span class="">Enviando solicitud a MercadoPago...</spa n>
                                </div>
                                <div class="span2 d-none">
                                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="color:blue" ></i>
                                    <span class="">Actualizando pedidos...</span>
                                </div>
                                <div class="span3 d-none">
                                    <i class="fa fa-check-square-o  fa-3x" style="color:green" aria-hidden="true"></i>
                                    <span class="">!Wow... todo salio bien, en espera de 2 segundos para actualizar pagina</span>
                                </div>
                                
                            </div>                      
                        </div>
                    </div>
                    <div class="col-12 mt-5">
                        <form action="<?=base_url('pedidos')?>" method="get" class="col-12">
                            <div class="row">
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="w-100">
                                            Buscador
                                        </label>
                                        <?php
                                        $search="";
                                        if (isset($_GET['search'])) {
                                            $search=$_GET['search'];
                                        }
                                        ?>
                                        <input class="form-control" type="text" name="search" value="<?=$search?>">
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="w-100" for="search-type">
                                            Buscar por:
                                        </label>
                                        <?php
                                        $searchType = "";
                                        if (isset($_REQUEST['search-type'])) {
                                            $searchType = $_REQUEST['search-type'];
                                        }
                                        ?>
                                        <select class="form-control" name="search-type" id="search-type">
                                            <option <?=(intval($searchType)===0)?"selected":"";?> value="0">Por Nro de Pedido</option>
                                            <option <?=(intval($searchType)===1)?"selected":"";?> value="1">Por Cliente</option>
                                            <?php if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) { ?>
                                            <option <?=(intval($searchType)===2)?"selected":""; ?> value="2">Por Vendedor</option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-12">
                                    <div class="form-group">
                                        <label class="w-100">
                                            --
                                        </label>
                                        <button type="submit" class="btn btn-success btn-sm">Buscar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="cajetin-pedidos">
                    
                    <div class="col-12 pedidos-top">
                        <ul class="list-inline">
                            <li>
                                <a class="mostrar_pedidos active" href="#pedidos_nuevos">Pedidos</a>
                            </li>

                        </ul>
                    </div>

                    <div class="col-12 listado card" id="pedidos_nuevos">
                        <div style="" class=" table-responsive">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>Nro Pedido</th>
                                        <th>Cliente</th>
                                        <th>Comprado</th>
                                        <th>Vendidor por</th>
                                        <th>Dirección</th>
                                        <th>Monto Total</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Metodo de Pago</th>
                                        <th>Canal</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $conteo = 0;
                                    foreach ($pedidos_new['pedidos'] as $key => $value) {
                                        if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) {
                                            $conteo++;
                                            ?>
                                        <tr>
                                            <td>#<?=$value['pedidos_id']?></td>
                                            <td><?=$value['pedidos_nombre']?></td>
                                            <td><?php
                                                $precioTotal = 0;
                                                $productosMostrar = [];
                                                $agregado = array();
                                                if ($pedidos_new["productos"]!=NULL && $pedidos_new["productos"]!="") {
                                                    if ($pedidos_new["productos"]->num_rows() > 0) {
                                                        foreach ($pedidos_new['pedidos_productos']->result_array() as $key2 => $value2) {
                                                            if ($value2['pedidos_detalle_pedidos_id']==$value['pedidos_id']) {
                                                                $agregado[$key2] = 0;
                                                                foreach ($pedidos_new["productos"]->result_array() as $key3 => $value3):
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
                                                                        
                                                                        $image = "";
                                                                        if ($agregado[$key2]!==1) {
                                                                            if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) $image=base_url($value3['medios_url']);                                                                        
                                                                        }
                                                                        if (count($productosMostrar)<=0) {                                                                        
                                                                            if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) $image=base_url($value3['medios_url']);
                                                                        }
                                                                        $productosMostrar[]=[
                                                                            'productos_id' => $value3['productos_id'],
                                                                            'productos_imagen' => $image,
                                                                            'productos_titulo' => $value3['productos_titulo'],
                                                                            'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                                                            'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                                                            'productos_vendedor' => $value3['productos_vendedor'],
                                                                            'productos_fecha_programada' => $value2['pedidos_detalle_fechaprogramada']
                                                                        ];
                                                                        $agregado[$key2] = 1;
                                                                    }
                                                                endforeach;
                                                            }
                                                        }
                                                    }
                                                    
                                                }
                                                foreach ($productosMostrar as $key4 => $value4) { ?>
                                                <p class="m-0">
                                                    <?=$value4['productos_titulo']?>
                                                    <br>
                                                    <span>
                                                        $<?=number_format($value4['productos_precio'], 0, ',', '.')?> x <?=$value4['productos_cantidad'];?>
                                                        <?php 
                                                        $thisPrice = $value4['productos_precio']*$value4['productos_cantidad'];
                                                        $precioTotal = $precioTotal+$thisPrice;
                                                        ?> = $ <?=number_format($thisPrice, 0, ',', '.')?>
                                                        <?php
                                                        if($value4['productos_fecha_programada']!==NULL && $value4['productos_fecha_programada']!=='' && $value4['productos_fecha_programada']!=='0000-00-00'){
                                                            date_default_timezone_set("America/Bogota");
                                                            setlocale(LC_TIME, "spanish");
                                                            setlocale(LC_ALL,"es_ES");

                                                            $newDate = date("d-m-Y", strtotime($value4['productos_fecha_programada'])); 
                                                            print '<br><span style="font-size:12px; color:#D27070"><b>Fecha Programada:</b> '.utf8_encode(strftime("%A, %d %B %Y", strtotime($newDate))).'</span>';
                                                        }
                                                        ?>
                                                    </span>
                                                    <hr>
                                                </p>
                                                <?php
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                            $precioTotal = 0;
                                            $productosMostrar = array();
                                            $agregado = array();
                                            if ($pedidos_new["productos"]!=NULL && $pedidos_new["productos"]!="") {
                                                if ($pedidos_new["productos"]->num_rows() > 0) {
                                                    foreach ($pedidos_new["pedidos_productos"]->result_array() as $key2 => $value2) {
                                                        if ($value2['pedidos_detalle_pedidos_id']==$value['pedidos_id']) {
                                                            $agregado[$key2] = 0;
                                                            foreach ($pedidos_new["productos"]->result_array() as $key3 => $value3) {
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
                                                                            'productos_vendedor_nombre' => $value3['name'],
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
                                                                            'productos_vendedor_nombre' => $value3['name'],
                                                                        ));
                                                                        $agregado[$key2] = 1;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                            }
                                                foreach ($productosMostrar as $key4 => $value4) { ?>
                                                    <p class="m-0">* <?=$value4['productos_vendedor_nombre']?></p>
                                                <?php } ?>
                                            </td>
                                            <td><?=$value['pedidos_direccion']?></td>
                                            <td>$<?=number_format($value['pedidos_precio_total'], 0, ',', '.')?></td>
                                            <td><?=$value['pedidos_fecha_creacion']?></td>
                                            <td><?php
                                            if($value['pedidos_estatus']==6){ 
                                                echo "<span class='order-status order-status-refunded'>Rechazado</span>"; 
                                            }
                                            elseif ($value['pedidos_estatus']==4) {
                                                echo "<span class='order-status order-status-processing'>En Preparación</span>"; 
                                            }
                                            else {
                                                switch ($value['pedidos_estatus']) {
                                                    case 'Enviado':
                                                        echo "<span class='order-status order-status-completed'>".$value['pedidos_estatus']."</span>";
                                                        break;
                                                    case 'En preparación':
                                                        echo "<span class='order-status order-status-processing'>".$value['pedidos_estatus']."</span>";
                                                        break;
                                                    case 'Esperando confirmación de pago':
                                                        echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
                                                        break;  
                                                    case 1:
                                                        echo "<span class='order-status order-status-on-hold'>Esperando confirmación de pago</span>";
                                                        break;  
                                                    case 'Esperando confirmación de Pago':
                                                        echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
                                                        break;  
                                                    case 'Cancelado':
                                                            echo "<span class='order-status order-status-cancelled'>".$value['pedidos_estatus']."</span>";
                                                        break;
                                                    default:
                                                        echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
                                                        break;  
                                                        
                                                }
                                            }
                                            ?></td>
                                            <td><?php
                                            if ($value['pedidos_metodo_pago']==0) {
                                                echo "Transferencia Bancaria";
                                            }elseif ($value['pedidos_metodo_pago']==1) {
                                                echo "Payzen";
                                            }elseif ($value['pedidos_metodo_pago']==2) {
                                                echo "PayU Latam";
                                            }
                                            elseif ($value['pedidos_metodo_pago']==3) {
                                                echo "MercadoPago";
                                            }
                                            elseif ($value['pedidos_metodo_pago']==4) {
                                                echo "PSE - Payzen";
                                            }
                                            else{
                                                echo $value['pedidos_metodo_pago'];
                                            }
                                            ?></td>
                                            <td><?=$value['pedidos_canal'];?></td>
                                            <td>
                                                <a class="btn btn-warning text-white btn-sm" href="<?=base_url('pedidos/editar_new/'.$value['pedidos_id'])?>">Editar</a>
                                            </td>
                                        </tr>
                                        <?php }else{ ?>
                                        <tr>
                                            <td>#<?=$value['pedidos_id']?></td>
                                            <td><?=$value['pedidos_nombre']?></td>
                                            <td><?php
                                            $precioTotal = 0;
                                            $productosMostrar = array();
                                            $agregado = array();
                                            if ($pedidos_new["productos"]!=NULL && $pedidos_new["productos"]!="") {
                                                if ($pedidos_new["productos"]->num_rows() > 0) {
                                                    foreach ($pedidos_new["pedidos_productos"]->result_array() as $key2 => $value2) {
                                                        if ($value2['pedidos_detalle_pedidos_id']==$value['pedidos_id']) {
                                                            $agregado[$key2] = 0;
                                                            foreach ($pedidos_new["productos"]->result_array() as $key3 => $value3) {
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
                                            $precioTotal=0;
                                            foreach ($productosMostrar as $key4 => $value4) {
                                                if ($value4['productos_vendedor']==$_SESSION['usuarios_id']) {
                                                    $conteo++;
                                            ?>
                                            <p class="m-0"><?=$value4['productos_titulo']?><br>
                                            <span>$ <?=number_format($value4['productos_precio'], 0, ',', '.')?> x <?php
                                            echo $value4['productos_cantidad'];
                                            $thisPrice = $value4['productos_precio']*$value4['productos_cantidad'];
                                            $precioTotal = $precioTotal+$thisPrice;
                                            ?> = $<?=number_format($thisPrice, 0, ',', '.')?></span>
                                            </p>
                                            <?php
                                                }
                                            }
                                            ?></td>
                                            <td><?=$_SESSION['name']?></td>
                                            <td><?=$value['pedidos_direccion']?></td>
                                            <td>$ <?php
                                            $precioEnvio = 0;
                                            $thisLocal = 0;
                                            $thisNacional = 0;
                                            $cantidadMun = array();
                                            $pedido_departamento = 0;
                                            $pedido_municipio = 0;
                                            if ($value['pedidos_direccion_envio_conf']==1) {
                                                $pedido_departamento = $value['pedidos_departamento_envio'];
                                                $pedido_municipio = $value['pedidos_municipio_envio'];
                                            }else{
                                                $pedido_departamento = $value['pedidos_departamento'];
                                                $pedido_municipio = $value['pedidos_municipio'];
                                            }
                                            foreach ($pedidos_new['pedidos_productos']->result_array() as $key17 => $value17) {
                                                if ($value17['pedidos_detalle_pedidos_id']==$value['pedidos_id']) {
                                                    /* Verificación de envíos nacionales */
                                                    if ($value17['pedidos_detalle_productos_envio_nacional']==1) {
                                                        /* Verificación de valor de envío nacional gratis */
                                                        if ($value17['pedidos_detalle_productos_valor_envio_nacional'] > 0) {
                                                            if (isset($pedido_departamento) && isset($pedido_municipio)) {
                                                                /* Verificación del municipio session del cliente */
                                                                $tubicaciones = explode("/",$value17['pedidos_detalle_productos_ubicaciones_envio']);
                                                                $p_ub = 0;
                                                                for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                                    $thisUbi = explode(",",$tubicaciones[$i2]);
                                                                    if ($thisUbi[0]==$pedido_departamento && $thisUbi[1]==$pedido_municipio) {
                                                                        $p_ub = 1;
                                                                    }                                                            
                                                                }
                                                                if ($p_ub==1) {
                                                                    if (floatval($value17['pedidos_detalle_productos_valor_envio_local']) >= $thisLocal) {
                                                                        $thisLocal = floatval($value17['pedidos_detalle_productos_valor_envio_local']);
                                                                    }
                                                                }else{
                                                                    if ($value17['pedidos_detalle_productos_ubicaciones_envio']=="") {
                                                                        /* Verificación de municipio 0 */
                                                                        $ub_0 = 0;
                                                                        $valor_0 = 0;
                                                                        for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                            if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                $ub_0 = 1;
                                                                                if(floatval($value17['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                    $cantidadMun[$ubi_0]['valor'] = floatval($value17['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($ub_0==0) {
                                                                            array_push($cantidadMun,array(
                                                                                "municipio" => 0,
                                                                                "valor" => floatval($value17['pedidos_detalle_productos_valor_envio_nacional'])
                                                                            ));
                                                                        }
                                                                    }else{
                                                                        /* Verificación de ubicaciones entre productos */
                                                                        $tubicaciones = explode("/",$value17['pedidos_detalle_productos_ubicaciones_envio']);
                                                                        $p_ub = 0;
                                                                        for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                                            $thisUbi = explode(",",$tubicaciones[$i2]);
                                                                            for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                                                                if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                                                    $p_ub = 1;
                                                                                    if (floatval($value17['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                                                        $cantidadMun[$cantvr]['valor'] = floatval($value17['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                    }       
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($p_ub==0) {
                                                                            $thisUbi = explode(",",$tubicaciones[0]);
                                                                            if (isset($thisUbi[1])) {
                                                                                array_push($cantidadMun,array(
                                                                                    "municipio" => $thisUbi[1],
                                                                                    "valor" => floatval($value17['pedidos_detalle_productos_valor_envio_nacional'])
                                                                                ));
                                                                            }else{
                                                                                $ub_0 = 0;
                                                                                $valor_0 = 0;
                                                                                for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                                    if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                        $ub_0 = 1;
                                                                                        if(floatval($value17['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                            $cantidadMun[$ubi_0]['valor'] = floatval($value17['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                        }
                                                                                    }
                                                                                }
                                                                                if ($ub_0==0) {
                                                                                    array_push($cantidadMun,array(
                                                                                        "municipio" => 0,
                                                                                        "valor" => floatval($value17['pedidos_detalle_productos_valor_envio_nacional'])
                                                                                    ));
                                                                                }       
                                                                            }
                                                                        }

                                                                    }
                                                                }

                                                            }else{
                                                                /* Verificación de municipios entre productos */
                                                                $tubicaciones = explode("/",$value17['pedidos_detalle_productos_ubicaciones_envio']);
                                                                $p_ub = 0;
                                                                for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                                    $thisUbi = explode(",",$tubicaciones[$i2]);
                                                                    for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                                                        if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                                            $p_ub = 1;
                                                                            if (floatval($value17['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                                                $cantidadMun[$cantvr]['valor'] = floatval($value17['pedidos_detalle_productos_valor_envio_nacional']);
                                                                            }       
                                                                        }
                                                                    }
                                                                }
                                                                if ($p_ub==0) {
                                                                    $thisUbi = explode(",",$tubicaciones[0]);
                                                                    if (isset($thisUbi[1])) {
                                                                        array_push($cantidadMun,array(
                                                                            "municipio" => $thisUbi[1],
                                                                            "valor" => floatval($value17['pedidos_detalle_productos_valor_envio_nacional'])
                                                                        ));
                                                                    }else{
                                                                        $ub_0 = 0;
                                                                        $valor_0 = 0;
                                                                        for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                            if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                                $ub_0 = 1;
                                                                                if(floatval($value17['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                                    $cantidadMun[$ubi_0]['valor'] = floatval($value17['pedidos_detalle_productos_valor_envio_nacional']);
                                                                                }
                                                                            }
                                                                        }
                                                                        if ($ub_0==0) {
                                                                            array_push($cantidadMun,array(
                                                                                "municipio" => 0,
                                                                                "valor" => floatval($value17['pedidos_detalle_productos_valor_envio_nacional'])
                                                                            ));
                                                                        }       
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }else{
                                                        $tubicaciones = explode("/",$value17['pedidos_detalle_productos_ubicaciones_envio']);
                                                        $p_ub = 0;
                                                        for ($i2=0; $i2 < count($tubicaciones); $i2++) {
                                                            $thisUbi = explode(",",$tubicaciones[$i2]);
                                                            for ($cantvr=0; $cantvr < count($cantidadMun); $cantvr++) {
                                                                if (isset($thisUbi[1]) && $thisUbi[1]==$cantidadMun[$cantvr]['municipio']) {
                                                                    $p_ub = 1;
                                                                    if (floatval($value17['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$cantvr]['valor']) {
                                                                        $cantidadMun[$cantvr]['valor'] = floatval($value17['pedidos_detalle_productos_valor_envio_nacional']);
                                                                    }       
                                                                }
                                                            }
                                                        }
                                                        if ($p_ub==0) {
                                                            $thisUbi = explode(",",$tubicaciones[0]);
                                                            if (isset($thisUbi[1])) {
                                                                array_push($cantidadMun,array(
                                                                    "municipio" => $thisUbi[1],
                                                                    "valor" => floatval($value17['pedidos_detalle_productos_valor_envio_nacional'])
                                                                ));
                                                            }else{
                                                                $ub_0 = 0;
                                                                $valor_0 = 0;
                                                                for ($ubi_0=0; $ubi_0 < count($cantidadMun); $ubi_0++) {
                                                                    if ($cantidadMun[$ubi_0]['municipio']==0) {
                                                                        $ub_0 = 1;
                                                                        if(floatval($value17['pedidos_detalle_productos_valor_envio_nacional']) >= $cantidadMun[$ubi_0]['valor']){
                                                                            $cantidadMun[$ubi_0]['valor'] = floatval($value17['pedidos_detalle_productos_valor_envio_nacional']);
                                                                        }
                                                                    }
                                                                }
                                                                if ($ub_0==0) {
                                                                    array_push($cantidadMun,array(
                                                                        "municipio" => 0,
                                                                        "valor" => floatval($value17['pedidos_detalle_productos_valor_envio_nacional'])
                                                                    ));
                                                                }       
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            for ($i74=0; $i74 < count($cantidadMun); $i74++) {
                                                $thisNacional = $thisNacional+floatval($cantidadMun[$i74]['valor']);
                                            }
                                            $precioEnvio = $precioEnvio+$thisLocal+$thisNacional;
                                            $precioTotal=$precioEnvio+$precioTotal;
                                            echo number_format($precioTotal, 0, ',', '.');
                                            ?></td>
                                            <td><?=$value['pedidos_fecha_creacion']?></td>
                                            <td><?php
                                            if($value['pedidos_estatus']==6){ 
                                                echo "<span class='order-status order-status-refunded'>Rechazado</span>"; 
                                            }elseif ($value['pedidos_estatus']==4) {
                                                echo "<span class='order-status order-status-processing'>En Preparación</span>"; 
                                            }else {
                                                switch ($value['pedidos_estatus']) {
                                                    case 'Enviado':
                                                        echo "<span class='order-status order-status-completed'>".$value['pedidos_estatus']."</span>";
                                                        break;
                                                    case 'En preparación':
                                                        echo "<span class='order-status order-status-processing'>".$value['pedidos_estatus']."</span>";
                                                        break;
                                                    case 'Esperando confirmación de pago':
                                                        echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
                                                        break;  
                                                    case 1:
                                                        echo "<span class='order-status order-status-on-hold'>Esperando confirmación de pago</span>";
                                                        break;  
                                                    case 'Esperando confirmación de Pago':
                                                        echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
                                                        break;  
                                                    case 'Cancelado':
                                                            echo "<span class='order-status order-status-cancelled'>".$value['pedidos_estatus']."</span>";
                                                        break;
                                                    default:
                                                        echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
                                                        break;  
                                                        
                                                }
                                            }
                                            ?></td>
                                            <td><?php
                                            if ($value['pedidos_metodo_pago']==0) {
                                                echo "Transferencia Bancaria";
                                            }elseif ($value['pedidos_metodo_pago']==1) {
                                                echo "Payzen";
                                            }elseif ($value['pedidos_metodo_pago']==2) {
                                                echo "PayU Latam";
                                            }elseif ($value['pedidos_metodo_pago']==3) {
                                                echo "MercadoPago";
                                            }else{
                                                echo $value['pedidos_metodo_pago'];
                                            }
                                            ?></td>
                                            <td><?=$value['pedidos_canal'];?></td>
                                            <td>
                                                <a class="btn btn-warning text-white btn-sm" href="<?=base_url()?>pedidos/editar_new/<?=$value['pedidos_id']?>">Editar</a>
                                            </td>
                                        </tr>
                                            <?php
                                        }
                                    }
                                    if ($conteo <= 0) { ?>
                                    <tr>
                                        <td colspan="20" class="text-center">No hay registros</td>
                                    </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12 text-center py-4">
                            <div class="paginado">
                                <ul class="list-inline">
                                <?php
                                if (isset($_GET['page']) && $_GET['page'] > 1) { 
                                    ?>
                                <li>
                                    <a href="<?=$urlActualPrevPage?>">Anterior</a>
                                </li>
                                <?php
                                }
                                if (isset($_GET['page'])) {
                                    echo '<span class="btn rounded border mx-2">'.$_GET['page'].'</span>';
                                }else{
                                    echo '<span class="btn rounded border mx-2">1</span>';
                                }
                                if (count($pedidos_new['pedidos']) == 30) { ?>
                                <li>
                                    <a href="<?=$urlActualNextPage?>">Siguiente</a>
                                </li>
                                <?php  } ?>
                                </ul>
                            </div>
                        </div>
                    </div>



                </div>

                
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ModalDel" tabindex="-1" role="dialog" aria-labelledby="ModalDelLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <h5 class=" text-center">Estás seguro que deseas eliminar este producto?</h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button id="btnEliminar" type="button" class="btn btn-danger">Eliminar</button>
      </div>
    </div>
  </div>
</div>
