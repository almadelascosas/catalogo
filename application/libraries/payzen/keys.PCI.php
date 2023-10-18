<?php
/**
 * Get the client
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Define configuration
 */

/* Username, password and endpoint used for server to server web-service calls */
Lyra\Client::setDefaultUsername("80379999");
Lyra\Client::setDefaultPassword("prodpassword_6EYoQ8kx3ToTdM9CO5Oahac95Qn0iUwj61MDjMapHEwtx");
//Lyra\Client::setDefaultPassword("testpassword_z1zBmlhP4r4K7pl23jKU6H8Sr2SMJHOQbnSwvPnsUiyu6");
Lyra\Client::setDefaultEndpoint("https://api.payzen.lat/");

/* publicKey and used by the javascript client */
Lyra\Client::setDefaultPublicKey("80379999:publickey_F8y5rLeI6ZHcktdU74xRXCQvJlRrJOQCBSId14xj285Y0");
//Lyra\Client::setDefaultPublicKey("80379999:testpublickey_typOGRpuj5CCePMdSdkz2c5FqPJL7rtqyDe9VqC8hp5dI");

/* SHA256 key */
Lyra\Client::setDefaultSHA256Key("Yz19TrBIf93ABilE9EhpwsAtdJg5u12kHg4AJd3rLJHpM");
//Lyra\Client::setDefaultSHA256Key("Z8QAOV4MKho1W9tFVq5367hdCLbQcrZbVAxJGIJPqBdFj");
