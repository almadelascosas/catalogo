<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <base href="<?=base_url();?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?=(isset($title))?$title:'Regalo para toda Ocasión en Alma de las Cosas ¡Sorprende!'?></title>
    <meta name="description" content="<?=(isset($descripcion))?$descripcion:''?>">

    <?php
    if(isset($ogfb)){
        $host= $_SERVER["HTTP_HOST"];
        $url= $_SERVER["REQUEST_URI"];
        $descrip = "La aplicación ALMA DE LAS COSAS una aplicación para dispositivos móviles propiedad de propiedad de ALMA DE COLOMBIA S.A.S., persona jurídica, domiciliada en Colombia, regida por la ley colombiana y los presentes Términos y Condiciones, los cuales el USUARIO se obliga a respetar desde el momento de ingreso al sitio. Si el USUARIO no está de acuerdo con cualquiera de los presentes Términos y Condiciones deberá abstenerse de ingresar al sitio web o hacer uso de sus servicios.";
    ?>
    <meta property="fb:app_id" content="963040887954308"/>
    <meta property="og:site_name" content="<?=base_url()?>">
    <meta property="og:title" content="<?=(isset($title)) ? $title : 'Regalo para toda Ocasión en Alma de las Cosas ¡Sorprende!'?>" />
    <meta property="og:type" content="page">
    <meta property="og:image" content="<?=(isset($ogfbimage)) ? base_url($ogfbimage) : base_url('assets/img/logo-alma-header.jpg')?>">
    <meta property="og:url" content="<?="https://".$host.$url;?>">
    <meta property="og:description" content="<?=(isset($ogfbdetalle) && $ogfbdetalle!=='')?$ogfbdetalle:$descrip?>">
    <meta property="og:locale" content="es_CO">
    <?php } ?>

    <meta name="author" content="Alma de las Cosas">
    <link rel="icon" type="image/x-icon" href="assets/img/icon.jpg"/>
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link loading="lazy" rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css?<?=rand()?>">
    <!-- END GLOBAL MANDATORY STYLES -->

    <!-- BEGIN PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <!-- END PAGE LEVEL PLUGINS/CUSTOM STYLES -->
    <?php
    //If CSS Data exists, load files
    if(isset($css_data)) foreach($css_data AS $css_file) echo '<link href="'.$css_file.'" rel="stylesheet">';
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
    <meta name="google-site-verification" content="gcfjhFEByVMiQD1fdwb_p09n_qa7tiDCXgNASJNW97M" />

    <?php if(!isset($dataPixel) || $dataPixel!=='purchase'){ ?>
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
    <noscript>
    <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=558712582247474&ev=PageView&noscript=1"/>
    </noscript>
    <!-- End Meta Pixel Code -->
    <?php } ?>

    
    <?php
    /*
    print '<!-- INICIO - API CONVERSIONES META -->';

    const PIXEL_ID = '558712582247474';
    const ACCESS_TOKEN = 'EAAJNZCDpjuLIBAKVDdDkMUKfft84CQXJcodL5QZADg8eptDtn3WoWtXkjACA4IOUpBABZBDo76ELOtX1BXl829Tbbr5RAkiNkqZAqtIZCxUfVghNnUJq8xZBsTO7uDkW1A4nETtAIRhndGiZBzYIAALNuhgbQj4eShBdOnOEaPG4HKaCbQcVbKP4TUCobGHFLMZD';
    const EMAIL_PIXEL = 'roldanfelipezuluaga@gmail.com';

    // Obtener el fb_login_id del usuario (Ejemplo: asumiendo que ya tienes implementado Facebook Login si se usa)
    //$fb_login_id = $_SESSION['fb_login_id']; // Reemplaza esta línea con la forma en que obtienes el fb_login_id del usuario

    $events = [];

    // Función para generar un event_id único
    function generateUniqueEventId() {
        return uniqid();
    }

    // Función para obtener el event_source_url
    function getEventSourceUrl() {
        //return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

        $pageURL = 'http';
        if (!empty($_SERVER['HTTPS'])) { $pageURL .= "s"; }
        return $pageURL.'://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }


    // Función para enviar el evento a Facebook
    function sendEventToFacebook($event) {
        $data = [
            'data' => [$event],
            'test_event_code' => 'TEST53886',
        ];

        $url = 'https://graph.facebook.com/v16.0/' . PIXEL_ID . '/events?access_token=' . ACCESS_TOKEN;
        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Error: ' . curl_error($ch);
        } else {
            $response_data = json_decode($response, true);
            if (isset($response_data['error'])) {
                echo 'Error: ' . $response_data['error']['message'];
            } else {
                //echo 'Success: ' . $response;
            }
        }

        curl_close($ch);
    }

    // Configuración del evento de PageView
    $pageViewEvent = [
        'event_name' => 'PageView',
        'event_time' => time(),
        'user_data' => [
            'client_ip_address' => $_SERVER['REMOTE_ADDR'],
            'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'em' => hash('sha256', EMAIL_PIXEL) // Aplicar función de hash al email
        ],
        'custom_data' => [
            'external_event_id' => generateUniqueEventId() // Agrega el external_event_id aquí
        ],
        'event_source_url' => getEventSourceUrl(), // Obtiene el event_source_url automáticamente
        'event_id' => generateUniqueEventId(), // Genera un event_id único
        'action_source' => 'website',
    ];

    $events=$pageViewEvent;
    

    // ----- VER CONTENIDO - (tienda/single/...) -----
    if(isset($dataPixel) && $dataPixel==='inicioPago'){
        $totalCarrito=0;
        $idProds=[];
        if(isset($_SESSION['cart'])){
            foreach ($_SESSION['cart'] as $llave => $carr):
                if(!in_array($carr['productos_id'], $idProds)){
                    $idProds[]=$carr['productos_id'];
                    $producto = $mdProducto->single($carr['productos_id']);
                    $totalCarrito += $carr['productos_cantidad'] * $producto['productos_precio']; 
                }            
            endforeach;
        }

        $InitiateCheckoutevent  = [
            'event_name' => 'InitiateCheckout',
            'event_time' => time(),
            'user_data' => [
                'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'em' => hash('sha256', EMAIL_PIXEL)
            ],
            'custom_data' => [
                'currency' => 'COP',
                'value' => $totalCarrito.'.00', 
                'content_type' => 'product',
                'num_items'=> count($idProds),
                'external_event_id' => generateUniqueEventId()
            ],
            'event_source_url' => getEventSourceUrl(),
            'event_id' => generateUniqueEventId(),
            'action_source' => 'website',
        ];
        $events=$InitiateCheckoutevent ;
    }


    // ----- COMPRA - (checkout/finalizar-compra) -----

    if(isset($dataPixel) && $dataPixel==='purchase'){
        $Purchase  = [
            'event_name' => 'Purchase',
            'event_time' => time(),
            'user_data' => [
                'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'em' => hash('sha256', EMAIL_PIXEL)
            ],
            'custom_data' => [
                'currency' => 'COP',
                'value' => $pedido['pedido']['pedidos_precio_total'].'.00', 
                'content_type' => 'product',
                'num_items'=> $pedido['cantitems'],
                'external_event_id' => generateUniqueEventId()
            ],
            'event_source_url' => getEventSourceUrl(),
            'event_id' => generateUniqueEventId(),
            'action_source' => 'website',
        ];
        //print_r($Purchase);
        $events=$Purchase ;
    }

    // ----- VER CONTENIDO - (tienda/single/...) -----
    
    if(isset($dataPixel) && $dataPixel==='viewContent'){
        $viewContent = [
            'event_name' => 'ViewContent',
            'event_time' => time(),
            'user_data' => [
                'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'em' => hash('sha256', EMAIL_PIXEL)
            ],
            'event_source_url' => getEventSourceUrl(),
            'event_id' => generateUniqueEventId(),
            'action_source' => 'website',
        ];
        $events=$viewContent;
    }

    sendEventToFacebook($events);

    // FINAL - API CONVERSIONES META -->
    */
    ?>
    

    <script language="javascript">
           var base_url = "<?=base_url();?>";
    </script>
</head>
<body>
<audio id="cart_add_sound" controls="" preload="auto" hidden="hidden">
    <source src="assets/uploads/cart_add.wav" type="audio/wav">
</audio>