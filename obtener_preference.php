<?php
$order = [
    "items" => [
        array(
            "title" => "Pedido #".$_REQUEST['pedidos_id'],
            "quantity" => 1,
            "currency_id" => "COP",
            "unit_price" => intval($_REQUEST['pedidos_precio_total'])
        )
    ],
    "payer" => array(
        "email" => $_REQUEST['pedidos_correo'],
    ),
    "external_reference" => $_REQUEST['pedidos_id']
];
$order = json_encode($order, JSON_UNESCAPED_UNICODE);

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => $order,
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer TEST-737037781546252-072613-6ec8668d0de2667b89832928644e31a0-1166475224'
    ),
));
$response = curl_exec($curl);
curl_close($curl);
?>