<?php
$precioTotal = 0;
$productosMostrar = array();
$total=0;
$total_envio=0;
$agregado = array();
if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
    if ($pedido["productos"]->num_rows() > 0) {
        foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
            if ($value2['pedidos_detalle_pedidos_id']==$pedido['pedido']['pedidos_id']) {
                $agregado[$key2] = 0;
                foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
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
/* Pixel */
$content_ids = "";
$contents = "";
$num_items = 0;
$value_pixel = 0;
$cont = 0;
foreach ($productosMostrar as $key4 => $value4) {
    $cont++;
    $num_items = $num_items+floatval($value4['productos_cantidad']);
    $value_pixel = $value_pixel+floatval($value4['productos_precio']);
    if ($cont==1) {
        $content_ids = "'".$value4['productos_id']."'";
        $contents = "{'id': '".$value4['productos_id']."', 'quantity':".$value4['productos_cantidad']."}";
    }else{
        $content_ids .= ",'".$value4['productos_id']."'";
        $contents .= ",{'id': '".$value4['productos_id']."', 'quantity':".$value4['productos_cantidad']."}";
    }
}

if (isset($_REQUEST["external_reference"])) {
    $estatus = 1;
    if ($_REQUEST['status']=="success") {
        $estatus = 4;
    }
    if ($_REQUEST['status']=="pending") {
        $estatus = "Pendiente";
    }
    if ($_REQUEST['status']=="failure") {
        $estatus = 6;
    }
    $this->db->set('pedidos_estatus', $estatus);
    $this->db->where('pedidos_id', $_POST['external_reference']);
    $this->db->update('alma_pedidos');

    if ($estatus==6 || $estatus==4) {
        $datos = array();
        $this->db->select("pedidos_detalle_producto as producto");
        $this->db->where("pedidos_detalle_pedidos_id",$_POST['external_reference']);
        $consulta = $this->db->get("alma_pedidos_detalle");

        $productos = array();

        foreach ($consulta->result_array() as $key => $value) {
        array_push($productos,$value['producto']);
        }
        for ($i=0; $i < count($productos); $i++) {
        $this_estatus = "Rechazado";
        if ($estatus==6) {
            $this_estatus = "Rechazado";
        }
        elseif ($estatus==4) {
            $this_estatus = "En preparación";
        }
        array_push($datos, array(
            'productos_id' => $productos[$i],
            'pedidos_id' => $_POST['extra1'],
            'estatus' => $this_estatus, 
            'nro_guia' => "", 
            'nombre_empresa' => "", 
            'addons' => "", 
            'cambio_usuarios_id' => 0 
        ));
        }
        $this->db->insert_batch('pedidos_estatus_productos', $datos);
    }
}

?>
<main class="col-12 cuerpo float-left">
    <div class="row">
        <div class="col-12 text-center">
            <div class="row">
                <?php
                if (isset($_REQUEST['lapTransactionState'])) {
                    if ($_REQUEST['lapTransactionState']=="APPROVED") { ?>
                    <div class="col-12 thanks-approved">
                        <h4 class="mb-0 py-4">Pago exitoso.</h4>
                        <p>Felicitaciones tu transacción fue exitosa.<br> Enviaremos a tu mail la confirmación de la compra</p>
                    </div>
                    <script type="text/javascript">
                        fbq('track', 'Purchase', {content_ids:[<?=$content_ids?>],contents:[<?=$contents?>],num_items:<?=$num_items?>,currency:'COP',value:<?=$value_pixel?>});
                    </script>
                    <?php
                    }elseif($_REQUEST['lapTransactionState']=="DECLINED"){ ?>
                    <div class="col-12 thanks-declined">
                        <h4 class="mb-0 py-4">Rechazado</h4>
                        <p>Lo sentimos, su pago ha sido rechazado.</p>
                    </div>
                    <?php
                    }elseif($_REQUEST['lapTransactionState']=="ERROR"){ ?>
                    <div class="col-12 thanks-error">
                        <h4 class="mb-0 py-4">Error.</h4>
                        <p>Ha ocurrido un error con su pago.</p>
                    </div>
                    <?php
                    }elseif ($_REQUEST['lapTransactionState']=="PENDING") { ?>
                    <div class="col-12 thanks-pending">
                        <h4 class="mb-0 py-4">Pago en proceso</h4>
                        <p>Felicitaciones tu orden esta en proceso.<br> Notificaremos a tu correo electrónico la confirmación de tu compra</p>
                    </div>
                    <script type="text/javascript">
                        fbq('track', 'Purchase', {content_ids:[<?=$content_ids?>],contents:[<?=$contents?>],num_items:<?=$num_items?>,currency:'COP',value:<?=$value_pixel?>});
                    </script>
                    <?php
                        actualizarPedido($_REQUEST['extra1'],"Pendiente");
                    }
                }
                if (isset($_REQUEST["external_reference"])) {
                    if ($_REQUEST['status']=="success") { ?>
                    <div class="col-12 thanks-approved">
                        <h4 class="mb-0 py-4">Pago exitoso.</h4>
                        <p>Felicitaciones tu transacción fue exitosa.<br> Enviaremos a tu mail la confirmación de la compra</p>
                    </div>
                    <script type="text/javascript">
                        fbq('track', 'Purchase', {content_ids:[<?=$content_ids?>],contents:[<?=$contents?>],num_items:<?=$num_items?>,currency:'COP',value:<?=$value_pixel?>});
                    </script>
                    <?php
                    }elseif($_REQUEST['status']=="failure"){ ?>
                    <div class="col-12 thanks-declined">
                        <h4 class="mb-0 py-4">Rechazado</h4>
                        <p>Lo sentimos, su pago ha sido rechazado.</p>
                    </div>
                    <?php
                    }elseif($_REQUEST['status']=="pending") { ?>
                    <div class="col-12 thanks-pending">
                        <h4 class="mb-0 py-4">Pago en proceso</h4>
                        <p>Felicitaciones tu orden esta en proceso.<br> Notificaremos a tu correo electrónico la confirmación de tu compra</p>
                    </div>
                    <script type="text/javascript">
                        fbq('track', 'Purchase', {content_ids:[<?=$content_ids?>],contents:[<?=$contents?>],num_items:<?=$num_items?>,currency:'COP',value:<?=$value_pixel?>});
                    </script>
                    <?php
                        actualizarPedido($_REQUEST['external_reference'],"Pendiente");
                    }
                }
                ?>
                <a class="btn btn-green-alma" href="<?=base_url()?>">Volver a la página de inicio</a>
            </div>
        </div>
        <div class="col-12 pt-5">
        </div>
    </div>
</main>
        