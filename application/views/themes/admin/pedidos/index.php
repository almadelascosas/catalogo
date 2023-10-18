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
                            <div class="col-6 text-left">
                                <label for="">
                                    12
                                    <!--<select name="" id="">
                                        <option value="">25</option>
                                        <option value="">50</option>
                                        <option value="">75</option>
                                        <option value="">100</option>
                                    </select>-->
                                    Items por página 
                                </label>
                            </div>
                            <?php
                            
                            if ($_SESSION['tipo_accesos']==0) { ?>
                            <div class="col-12">
                                <p>
                                    <a class="btn btn-success" href="<?=base_url('pedidos/exportarPedidos')?>">Exportar Pedidos</a>
                                </p>
                            </div>
                            <?php
                            }
                            
                            ?>
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
                                            <?php if (intval($_SESSION['tipo_accesos'])===0 || intval($_SESSION['tipo_accesos'])===1) { ?>
                                            <option <?php if($searchType==2){ echo "selected"; } ?> value="2">Por Vendedor</option>
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
                <div class="col-12 listado mt-5">
                    
                    <div class=" table-responsive">
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
                                foreach ($pedidos['pedidos'] as $key => $value) {
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
                                        if ($pedidos["productos"]!=NULL && $pedidos["productos"]!="") {
                                            if ($pedidos["productos"]->num_rows() > 0) {
                                                foreach ($pedidos["pedidos_productos"]->result_array() as $key2 => $value2) {
                                                    if ($value2['pedidos_productos_pedido_id']==$value['pedidos_id']) {
                                                        $agregado[$key2] = 0;
                                                        foreach ($pedidos["productos"]->result_array() as $key3 => $value3) {
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
                                                                        'productos_fecha_programada' => $value2['pedidos_productos_cantidad']
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
                                                                        'productos_fecha_programada' => $value2['pedidos_productos_cantidad']
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
                                        if ($pedidos["productos"]!=NULL && $pedidos["productos"]!="") {
                                            if ($pedidos["productos"]->num_rows() > 0) {
                                                foreach ($pedidos["pedidos_productos"]->result_array() as $key2 => $value2) {
                                                    if ($value2['pedidos_productos_pedido_id']==$value['pedidos_id']) {
                                                        $agregado[$key2] = 0;
                                                        foreach ($pedidos["productos"]->result_array() as $key3 => $value3) {
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
                                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
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
                                        <td><?=$value['pedidos_fecha']?></td>
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
                                                    echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
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
                                        <td><?=$value['pedidos_metodo_pago']?></td>
                                        <td>
                                            <a class="btn btn-warning text-white btn-sm" href="<?=base_url()?>pedidos/editar/<?=$value['pedidos_id']?>">Editar</a>
                                        </td>
                                    </tr>
                                <?php
                                        
                                    }else{
                                        $this_price = 0;
                                        $permiso_this_order = 0;
                                        $productos = explode(",",$value['pedidos_productos']);
                                        for ($i=0; $i < count($productos); $i++) {
                                            foreach ($pedidos['productos']->result_array() as $key2 => $value2) {
                                                if ($value2['productos_id']==$productos[$i] && $value2['productos_vendedor']==$_SESSION['usuarios_id']) {
                                                    $permiso_this_order = 1;
                                                } 
                                            }
                                        } 
                                        if ($permiso_this_order==1) {
                                            $conteo++; 
                                            ?>
                                    <tr>
                                        <td>#<?=$value['pedidos_id']?></td>
                                        <td><?php
                                        $productos = explode(",",$value['pedidos_productos']);
                                        $productos_cantidad = explode(",",$value['pedidos_productos_cantidad']);
                                        for ($i=0; $i < count($productos); $i++) {
                                            foreach ($pedidos['productos']->result_array() as $key2 => $value2) {
                                                if ($value2['productos_id']==$productos[$i] && $value2['productos_vendedor']==$_SESSION['usuarios_id']) {
                                                    echo $value2['productos_titulo']." x ".$productos_cantidad[$i]."<br>";
                                                    $this_price=$this_price+$value2['productos_precio'];
                                                }
                                            }
                                        }
                                        ?></td>
                                        <td><?=$_SESSION['name']?></td>
                                        <td><?=$value['pedidos_direccion']?></td>
                                        <td>$ <?=number_format($this_price, 0, ',', '.')?></td>
                                        <td><?=$value['pedidos_fecha']?></td>
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
                                                case 'Esperando confirmación de Pago':
                                                    echo "<span class='order-status order-status-on-hold'>".$value['pedidos_estatus']."</span>";
                                                    break;  
                                                case 'Cancelado':
                                                    echo "<span class='order-status order-status-cancelled'>".$value['pedidos_estatus']."</span>";
                                                    break;
                                                default:
                                                    break;
                                            }
                                        }
                                        ?></td>
                                        <td><?=$value['pedidos_metodo_pago']?></td>
                                        <td>
                                            <a class="btn btn-warning text-white btn-sm" href="<?=base_url()?>pedidos/editar/<?=$value['pedidos_id']?>">Editar</a>
                                            
                                        </td>
                                    </tr>
                                        <?php
                                        }
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
                            if (count($pedidos['pedidos']) == 12) { ?>
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