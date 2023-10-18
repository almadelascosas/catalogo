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
    $urlActualNextPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequest;
    $urlActualPrevPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequestPrev;
}else{
    if ($varequest!="") {
        $urlActualNextPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequest."&page=2";
        $urlActualPrevPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?".$varequestPrev;
    }else{
        $urlActualNextPage = "https://".$_SERVER["HTTP_HOST"].$sinreq."?page=2";
        $urlActualPrevPage = "https://".$_SERVER["HTTP_HOST"].$sinreq;
    }
}
?>

<table class="table">
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
                $productosMostrar = array();
                $agregado = array();
                if ($pedidos_new["productos"]!=NULL && $pedidos_new["productos"]!="") {
                    if ($pedidos_new["productos"]->num_rows() > 0) {
                        foreach ($pedidos_new['pedidos_productos']->result_array() as $key2 => $value2) {
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
                foreach ($productosMostrar as $key4 => $value4) { ?>
                <p class="m-0"><?=$value4['productos_titulo']?><br>
                <span>$ <?=number_format($value4['productos_precio'], 0, ',', '.')?> x <?php
                echo $value4['productos_cantidad'];
                $thisPrice = $value4['productos_precio']*$value4['productos_cantidad'];
                $precioTotal = $precioTotal+$thisPrice;
                ?> = $ <?=number_format($thisPrice, 0, ',', '.')?></span>
                </p>
                <?php
                }
                ?></td>
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
                <p class="m-0">- <?=$value4['productos_vendedor_nombre']?><br></p>
                <?php
                }
                ?></td>
                <td><?=$value['pedidos_direccion']?></td>
                <td>$ <?=number_format($value['pedidos_precio_total'], 0, ',', '.')?></td>
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
                <td>
                    <a class="btn btn-warning text-white btn-sm" href="<?=base_url()?>pedidos/editar_new/<?=$value['pedidos_id']?>">Editar</a>
                </td>
            </tr>
        <?php
                
            }else{
                    ?>
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
                ?> = $ <?=number_format($thisPrice, 0, ',', '.')?></span>
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