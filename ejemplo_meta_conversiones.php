<?php

const PIXEL_ID = 'Pixel id aqui';
const ACCESS_TOKEN = 'Token de acceso aqui';

// Obtener el fb_login_id del usuario (Ejemplo: asumiendo que ya tienes implementado Facebook Login si se usa)
$fb_login_id = $_SESSION['fb_login_id']; // Reemplaza esta línea con la forma en que obtienes el fb_login_id del usuario

// Función para generar un event_id único
function generateUniqueEventId() {
    return uniqid();
}

// Función para obtener el event_source_url
function getEventSourceUrl() {
    return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
}

// Función para enviar el evento a Facebook
function sendEventToFacebook($event) {
    $data = [
        'data' => [$event],
        'test_event_code' => 'TEST60029', // Reemplazar por el código de evento de prueba de Facebook
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
            echo 'Success: ' . $response;
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
        'em' => hash('sha256', 'ejemplo@example.com'), // Aplicar función de hash al email
        'ph' => hash('sha256', '123456789'), // Aplicar función de hash al teléfono
    ],
    'custom_data' => [
        'external_event_id' => generateUniqueEventId(), // Agrega el external_event_id aquí
    ],
    'event_source_url' => getEventSourceUrl(), // Obtiene el event_source_url automáticamente
    'event_id' => generateUniqueEventId(), // Genera un event_id único
    'action_source' => 'website',
];

// Configuración del evento de Purchase
$purchaseEvent = [
    'event_name' => 'Purchase',
    'event_time' => time(),
    'user_data' => [
        'em' => hash('sha256', 'ejemplo@example.com'), // Aplicar función de hash al email
        'ph' => hash('sha256', '123456789'), // Aplicar función de hash al teléfono
        'client_ip_address' => $_SERVER['REMOTE_ADDR'],
        'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
    ],
    'custom_data' => [
        'currency' => 'USD', // Cambiar la divisa por el necesario sea manualmente o con algún arreglo de variable por ejemplo
        'value' => '123.45', // Cambiar el valor por el necesario sea manualmente o con algún arreglo de variable por ejemplo
        'external_event_id' => generateUniqueEventId(), // Agrega el external_event_id aquí
    ],
    'event_source_url' => getEventSourceUrl(), // Obtiene el event_source_url automáticamente
    'event_id' => generateUniqueEventId(), // Genera un event_id único
    'action_source' => 'website',
];

// Configuración del evento de AddToCart
$addToCartEvent = [
    'event_name' => 'AddToCart',
    'event_time' => time(),
    'user_data' => [
        'em' => hash('sha256', 'ejemplo@example.com'), // Aplicar función de hash al email
        'ph' => hash('sha256', '123456789'), // Aplicar función de hash al teléfono
        'client_ip_address' => $_SERVER['REMOTE_ADDR'],
        'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
    ],
    'custom_data' => [
        'currency' => 'USD',
        'value' => '123.45',
        'external_event_id' => generateUniqueEventId(), // Agrega el external_event_id aquí (esta generado automáticamente pero si hay algún id externo aquí se reemplaza)
    ],
    'event_source_url' => getEventSourceUrl(), // Obtiene el event_source_url automáticamente
    'event_id' => generateUniqueEventId(), // Genera un event_id único automáticamente
    'action_source' => 'website',
];

// Combinar los eventos en un arreglo de eventos
$events = [
    $pageViewEvent,
    // Agregar más eventos aquí si es necesario
];

// Enviar el evento de PageView a Facebook
sendEventToFacebook($pageViewEvent);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Tu página web</title>
</head>
<body>
    <!-- Botón para el evento de Purchase -->
    <button onclick="sendPurchaseEvent()">Comprar</button>

    <!-- Botón para el evento de AddToCart -->
    <button onclick="sendAddToCartEvent()">Agregar al carrito</button>

    <script>
        function sendPurchaseEvent() {
            <?php
            // Enviar el evento de Purchase a Facebook
            sendEventToFacebook($purchaseEvent);
            ?>
        }

        function sendAddToCartEvent() {
            <?php
            // Enviar el evento de AddToCart a Facebook
            sendEventToFacebook($addToCartEvent);
            ?>
        }
    </script>
</body>
</html>