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
                            $image=image($value3['medios_url']);
                            array_push($productosMostrar, array(
                                'productos_id' => $value3['productos_id'],
                                'productos_imagen' => $image,
                                'productos_titulo' => $value3['productos_titulo'],
                                'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                'productos_ubicaciones_envio' => $value3['productos_ubicaciones_envio'],
                                'productos_envio_local' => $value3['productos_envio_local'],
                                'productos_envio_nacional' => $value3['productos_envio_nacional'],
                                'productos_valor_envio_local' => $value3['productos_valor_envio_local'],
                                'productos_valor_envio_nacional' => $value3['productos_valor_envio_nacional'],
                                'productos_vendedor' => $value3['productos_vendedor'],
                            ));
                            $agregado[$key2] = 1;
                        }
                        if (count($productosMostrar)<=0) {
                            $image = "";
                            $image=image($value3['medios_url']);
                            array_push($productosMostrar, array(
                                'productos_id' => $value3['productos_id'],
                                'productos_imagen' => $image,
                                'productos_titulo' => $value3['productos_titulo'],
                                'productos_precio' => floatval($value2['pedidos_detalle_producto_precio']),
                                'productos_cantidad' => floatval($value2['pedidos_detalle_producto_cantidad']),
                                'productos_ubicaciones_envio' => $value3['productos_ubicaciones_envio'],
                                'productos_envio_local' => $value3['productos_envio_local'],
                                'productos_envio_nacional' => $value3['productos_envio_nacional'],
                                'productos_valor_envio_local' => $value3['productos_valor_envio_local'],
                                'productos_valor_envio_nacional' => $value3['productos_valor_envio_nacional'],
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

require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/vendor/autoload.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/keys.PCI.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/helpers.php';
/** 
    * Initialize the SDK 
    * see keys.php
    */
$client = new Lyra\Client();
$precio = floatval($pedido['pedido']['pedidos_precio_total']."00");
/**
    * I create a formToken
    */
$store = array(
    "amount" => $precio, 
    "currency" => "COP", 
    "transactionOptions" => array(
        "cardOptions" => array(
            "installmentNumber" => 4  
        )
    ),
    "orderId" => "Pedido Nro ".$pedido['pedido']['pedidos_id'], 
    "customer" => array(
        "email" => $pedido['pedido']['pedidos_correo'],
        "billingDetails" => array(
            "language" => "ES"
        )
    )
);
$response = $client->post("V4/Charge/CreatePayment", $store);

/* I check if there are some errors */
if ($response['status'] != 'SUCCESS') {
    /* an error occurs, I throw an exception */
    display_error($response);
    $error = $response['answer'];
    throw new Exception("error " . $error['errorCode'] . ": " . $error['errorMessage']);
}

/* everything is fine, I extract the formToken */
$formToken = $response["answer"]["formToken"];

$nropedido = $pedido['pedido']['pedidos_id'];

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
    $this_price = 0;
    $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
    $total=$total+$this_price;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Finalizar Compra con Payzen -  Alma de las Cosas</title>
    <meta name="description" content="Si es un regalo especial, tiene alma y si tiene alma, está aquí. | Regalos Personalizados | Regalos Únicos | Envios Nacionales |">
    <meta name="author" content="Web Alma de las Cosas - Regalos y más.">
    <link rel="icon" type="image/x-icon" href="<?=base_url()?>assets/img/icon.jpg"/>
    <script 
    src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
    kr-public-key="<?php echo $client->getPublicKey(); ?>"
    kr-language = "es-ES"
    kr-post-url-refused	= "<?=base_url('checkout/thanks/rechazado/pedido-'.$pedido['pedido']['pedidos_id'].'/')?>/"
    kr-post-url-success = "<?=base_url('checkout/thanks/exitoso/pedido-'.$pedido['pedido']['pedidos_id'].'/')?>">
    </script>
    <!-- theme and plugins. should be loaded after the javascript library -->
    <!-- not mandatory but helps to have a nice payment form out of the box -->
    <link rel="stylesheet" 
    href="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.css">
    <script 
    src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.js">
    </script>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link loading="lazy" rel="stylesheet" href="<?=base_url()?>/assets/bootstrap/css/bootstrap.min.css">
    <link loading="lazy" rel="stylesheet" href="<?=base_url()?>/assets/css/style.css?v=<?=rand(1,999)?>">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <?php
    //If CSS Data exists, load files
    if(isset($css_data)){
        foreach($css_data AS $css_file){
            echo '<link href="'.$css_file.'" rel="stylesheet">';
        }
    }

    ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-151183385-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
        dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'UA-151183385-1');
    </script>

    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '558712582247474');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=558712582247474&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
    <script language="javascript">
           var base_url = "<?php echo base_url();?>";
    </script>
</head>
<body>
    <header class="w-100 bg-white float-left p-2">
        <div class="col-md-10 col-12 offset-md-1 px-0">
            <div class="col-12 p-relative float-left">
                <div class="row">
                    <div class="col-md-1 col-6 p-md-0">
                        <div class="logo">
                            <a href="<?=base_url()?>">
                                <img src="<?=base_url()?>assets/img/logo.png" alt="Logo">
                            </a>
                        </div>
                    </div>
                    <div class="col-md-9 d-md-block d-none">
                        
                    </div>
                    <div class="col-md-2 col-6 p-md-0">
                        <div class="w-100 h-100 d-table">
                            <div class="d-table-cell align-middle">
                                <h5 class="h5-header"><img class="logo-pago-seguro" src="<?=base_url()?>/assets/img/methods/logo-pagos-seguros-alma-de-las-cosas.png" alt="Pago Seguro" srcset="<?=base_url()?>/assets/img/methods/logo-pagos-seguros-alma-de-las-cosas.png"></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <?php
    if (isMobile()) { ?>
    <div class="col-12 text-center px-0">
        <div class="kr-embedded m-auto w-100 py-4 form-checkout mb-5" kr-form-token="<?php echo $formToken;?>">
            <div class="col-12 text-center">
                <h4>Completa los datos en tu tarjeta</h4>
            </div>
            <div class="w-100 col-12 card-metodo-pago mb-5">
                <!-- payment form fields -->
                <div class="row">
                    <div class="col-12">
                        <div class="kr-card-holder-name"></div>
                    </div>
                    <div class="col-12">
                        <div class="kr-installment-number"></div>
                    </div>
                    <div class="col-12">
                        <div class="kr-first-installment-delay"></div>
                    </div>
                    <div class="col-12">
                        <div class="kr-pan"></div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <label class="w-100 float-left">
                                Fecha de vencimiento
                            </label>
                            <div class="col-6 float-left pr-2">
                                <div class="kr-expiry"></div>
                            </div>
                            <div class="col-6 pl-0 float-left">
                                <div class=" kr-security-code"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 float-left">
                        <div class="kr-identity-document-type"></div>
                    </div>
                    <div class="col-12 float-left">
                        <div class="kr-identity-document-number"></div>
                    </div>
                    <div class="col-12"></div>
                </div>
                
                <!-- error zone -->
                <div class="kr-form-error"></div>
            </div>
            
            <div class="col-12 caja-btn-fixed-bottom">
                <!-- payment form submit button -->
                <button class="kr-payment-button"></button>
            </div>

        </div>
    </div>
    <?php
    }else{ ?>
    <div class="col-md-10 col-12 offset-md-1 my-5">
        <div class="row">
            <div class="col-11 offset-md-1 mb-5">
                <a class="btn-back-check mb-3" href="#back"><span class="icon-arrow-left2"></span> <span class="d-md-inline-block d-none">Volver</span></a>
            </div>
            <div class="col-md-6 offset-md-1 col-12 card-metodo-pago mb-5 bg-white form-checkout">
                <div class="row">
                    <div class="col-12 my-4 text-center">
                        <h4>Completa los datos en tu tarjeta</h4>
                    </div>
                    <div class="col-12 px-5 kr-embedded" kr-form-token="<?php echo $formToken;?>">
                        <div class="row">
                            <div class="w-100 col-12">
                                <!-- payment form fields -->
                                <div class="row">
                                    <div class="w-50 pr-2 float-left">
                                        <div class="pr-2 kr-identity-document-type"></div>
                                    </div>
                                    <div class="w-50 float-left">
                                        <div class="kr-identity-document-number"></div>
                                    </div>
                                    <div class="w-100">
                                        <div class="kr-card-holder-name"></div>
                                    </div>
                                    <div class="w-100">
                                        <div class="kr-installment-number"></div>
                                    </div>
                                    <div class="w-100">
                                        <div class="kr-first-installment-delay"></div>
                                    </div>
                                    <div class="w-100">
                                        <div class="kr-pan"></div>
                                    </div>
                                    <div class="w-100 float-left">
                                        <label class="w-100 mb-2 text-capitalize float-left">
                                            Fecha de vencimiento
                                        </label>
                                        <div class="w-50 float-left pr-2">
                                            <div class="kr-expiry"></div>
                                        </div>
                                        <div class="w-50 float-left">
                                            <div class=" kr-security-code"></div>
                                        </div>
                                    </div>
                                    <div class="w-100"></div>
                                </div>
                                
                                <!-- error zone -->
                                <div class="kr-form-error"></div>
                            </div>
                            
                            <div class="col-12 text-center w-100 float-left my-4">
                                <!-- payment form submit button -->
                                <button class="kr-payment-button m-auto"></button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <?php
            if (!isMobile()) {
                ?>
                <div class="col-md-4 col-12 mb-5">
                    <div class="col-11 offset-md-1 card-metodo-pago p-md-4 form-checkout resumen-pago mb-4">
                        <h4 class="mt-2">Resumen de Compra</h4>
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
                        ?>
                        <input type="hidden" name="productos_cantidad_cart[]" id="productos_cantidad_cart" value="<?=$productos_cantidad?>">
                        <input type="hidden" name="productos_precio_cart[]" id="productos_precio_cart" value="<?=$productos_precio?>">
                        <input type="hidden" name="productos_addons_cart[]" id="productos_addons_cart" value="<?=$productos_addons?>">
                        <input type="hidden" name="productos_id_cart[]" id="productos_id_cart" value="<?=$productos_id?>">
                        <?php
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
                        <input type="hidden" value="<?=$value['productos_id']?>" name="pedidos_productos[]">
                        <div class="row">
                            <div class="col-6">
                                <p class="producto-item mb-0"><?=$value['productos_titulo']?> $ <?=number_format($value['productos_precio'], 0, ',', '.')?> <strong>x <?php
                                echo $value['productos_cantidad'];
                                $thisPrice = $value['productos_precio']*$value['productos_cantidad'];
                                $precioTotal = $precioTotal+$thisPrice;
                                ?></strong>
                                <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_cantidad[]">
                                </p>
                                <input type="hidden" value="<?=$value['productos_cantidad']?>" name="pedidos_productos_extras[]">
                            </div>
                            <div class="col-6 text-right">
                                <p class="producto-precio mb-0"><strong>$ <?=number_format($thisPrice, 0, ',', '.')?></strong></p>
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
                                <p class="mb-0">Subtotal</p>
                            </div>
                            <div class="col-6 text-right">
                                <p class="subtotal-precio mb-0"><strong>$ <?=number_format($precioTotal, 0, ',', '.')?></strong></p>
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
                                <p class="envio-precio mb-0"><strong> FIJO: $ <?=number_format($precioEnvio, 0, ',', '.')?></strong></p>
                                <input type="hidden" name="pedidos_precio_envio" value="<?=$precioEnvio?>">
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <h5 class="total-h5precio mb-0"><strong>Total</strong></h5>
                            </div>
                            <div class="col-6 text-right">
                                <?php
                                if (count($cantidadVendor) > 1) {
                                    echo "<p class='m-0'>Su pedido está compuesto por productos de distintos origenes.</p>";
                                }
                                ?>
                                <h5 class="total-h5precio mb-0">$ <?=number_format($precioTotal+$precioEnvio, 0, ',', '.')?></h5>
                                <input type="hidden" name="pedidos_precio_subtotal" value="<?=$precioTotal?>">
                                <input type="hidden" name="pedidos_precio_total" value="<?=$precioTotal+$precioEnvio?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    <?php
    }
    ?>
    
    <style>
        @media (max-width:767px){
            .card-metodo-pago {
                box-shadow: 0px 8px 24px #7090B026!important;
                padding: 25px!important;
                border-radius: 20px!important;
                width: 100%!important;
                display: block!important;
                background: #fff!important;
                margin: auto!important;
                margin-top: 30px!important;
                margin-bottom: 30px!important;
                position: relative!important;
                height: 100%!important;
            }
            .form-checkout h4 {
                font-size: 20px!important;
                margin-bottom: 24px!important;
                color: #5F5F5F!important;
                font-weight: 700!important;
                font-family: 'Lora'!important;
            }
            .form-checkout label {
                font-size: 14px!important;
                font-weight: 600!important;
                font-family: 'Karla'!important;
                margin-bottom: 15px!important;
                position: relative!important;
                width: 100%;
                float: left!important;
            }
            .col-8 {
                -ms-flex: 0 0 66.666667%;
                flex: 0 0 66.666667%;
                max-width: 66.666667%!important;
                float: left!important;
            }
            .col-4 {
                -ms-flex: 0 0 33.333333%!important;
                flex: 0 0 33.333333%!important;
                max-width: 33.333333%!important;
                float: left!important;
            }
            .col-3 {
                -ms-flex: 0 0 25%;
                flex: 0 0 25%;
                max-width: 25%!important;
                float: left!important;
            }
            .col-7 {
                -ms-flex: 0 0 58.333333%;
                flex: 0 0 58.333333%;
                max-width: 58.333333%!important;
                float: left!important;
            }
            .col-9 {
                -ms-flex: 0 0 75%;
                flex: 0 0 75%;
                max-width: 75%!important;
                float: left!important;
            }
            *, ::after, ::before {
                box-sizing: border-box!important;
            }
            .caja-btn-fixed-bottom {
                position: fixed!important;
                bottom: 0px!important;
                left: 0px!important;
                background-color: #fff!important;
                text-align: center!important;
                z-index: 99999!important;
                width: 100%!important;
                padding: 20px!important;
                padding-bottom: 15px!important;
                box-shadow: 0px 8px 24px #7090B026!important;
            }
            .h5-header{
                font-family: 'KARLA';
                font-weight: 600;
                color: #888888;
                margin: 0px;
            }
            header{
                position: fixed;
                background: transparent;
                box-shadow: 0px 0px 15px 0px rgb(0 0 0 / 50%);
            }
            body{
                padding-top: 80px!important;
            }
            .kr-embedded .kr-field-wrapper.kr-select-wrapper.kr-real-select select{
                padding-top: 0px!important;
                padding-bottom: 0px!important;
                font-size: 16px!important;
                font-family: "Karla"!important;
            }
            body{
                padding-bottom: 100px!important;
            }
        }
        @media (min-width:768px){
            *, ::after, ::before {
                box-sizing: border-box!important;
            }     
        }
        body, html{
            overflow-x: hidden!important;
        }
        input, input:active, input:focus, input:focus-within, input:hover, input:visited {
            font-size: 16px!important;
        }
        @media screen and (-webkit-min-device-pixel-ratio:0) {
            select:focus,
            textarea:focus,
            input:focus {
                font-size: 16px!important;
            }
        }
        .kr-embedded .kr-select-wrapper.kr-custom-select .kr-select {
            border: none!important;
            font-size: 16px;
            font-family: "Karla";           
            color: #5F5F5F!important;
        }
        .kr-embedded .kr-field-wrapper.kr-select-wrapper.kr-custom-select .kr-select span.kr-selected-option{
            font-size: 16px;
            font-family: "Karla";           
            color: #5F5F5F!important;
        }
        .kr-embedded input.kr-input-field {
            font-size: 16px!important;
            font-family: "Karla"!important;
            width: 100%!important;
            height: 100%!important;
        }
        .kr-embedded input.kr-input-field:focus {
            background: transparent!important;
        }
        .kr-embedded input.kr-input-field {
            font-size: 16px!important;
            font-family: "Karla"!important;
            width: 100%!important;
            height: 100%!important;
        }
        .kr-pan[kr-resource="kr-resource-2"] {
            padding-top: 3px!important;
        }
    </style>
</body>
</html>