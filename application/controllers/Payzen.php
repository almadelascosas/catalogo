<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Payzen extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('payzen');
        $this->load->helper(array('commun'));
        $this->load->model("menu_model");
        $this->load->model("categorias_model");
        $this->load->model("productos_model");
        $this->load->model("pedidos_model");
        $this->load->model("usuarios_model");
        $this->load->model("general_model");
        $this->load->model("vendor_model");
    }

    public function index(){
    }
    public function pruebaPCI(){
        /**
         * REST API example creating a payment method token
         * 
         * To run the example, go to 
         * hhttps://github.com/lyra/rest-php-example
         */

        /**
         * I initialize the PHP SDK
         */
        require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/vendor/autoload.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/keys.PCI.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/helpers.php';

        $username = 80379999;
        $password = "testpassword_z1zBmlhP4r4K7pl23jKU6H8Sr2SMJHOQbnSwvPnsUiyu6";

        $header = "Authorization: Basic " . base64_encode($username . ':' . $password);

        /** 
         * Initialize the SDK 
         * see keys.php
         */
        $client = new Lyra\Client();

        /**
         * Define the card to use
         */
        $card = array(
        "paymentMethodType" => "CARD",
        "pan" => "4970100000000014",
        "expiryMonth" => "11",
        "expiryYear" => "26",
        "securityCode" => "123"
        );

        /**
         * starting to create a transaction
         */
        $store = array(
        "amount" => 250000, 
        "currency" => "COP",
        "formAction" => "REGISTER_PAY",
        "paymentForms" => array($card),
        "customer" => array(
            "email" => "jcslr99@gmail.com",
            "orderId" => uniqid("12529")
        ));

        /**
         * do the web-service call
         */
        $response = $client->post("V4/Charge/CreatePayment", $store);

        /* I check if there are some errors */
        if ($response['status'] != 'SUCCESS') {
            /* an error occurs, I throw an exception */
            display_error($response);
            $error = $response['answer'];
            throw new Exception("error " . $error['errorCode'] . ": " . $error['errorMessage'] );
        }
        echo '
        <div class="container">  
            <h2>web-service request:</h2>
            <pre><code class="json">'.json_encode($store, JSON_PRETTY_PRINT).'</code></pre>

            <h2>web-service answer:</h2>
            <pre><code class="json">'.print json_encode($response, JSON_PRETTY_PRINT).'</code></pre>
        </div>';
        if ($response['answer']['orderStatus'] != 'PAID') {
            $title = "Transaction not paid !";
        }else{
            $title = "Transaction paid !";
        }
        echo '
        <h1>'.$title.'</h1>
        <h1><a href="/">Back to demo menu</a></h1>';

    }

    public function pruebaFormIncrustado(){

        require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/vendor/autoload.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/keys.PCI.php';
        require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/helpers.php';
        /** 
         * Initialize the SDK 
         * see keys.php
         */
        $client = new Lyra\Client();

        /**
         * I create a formToken
         */
        $store = array("amount" => 250000, 
        "currency" => "COP", 
        "orderId" => "Pedido Nro ", 
        "orderId" => uniqid("15287"),
        "customer" => array(
        "email" => "softmenaca@gmail.com"
        ));
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
        ?>
            <!DOCTYPE html>
            <html>
            <head>
            <meta name="viewport" 
            content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge" /> 


            <!-- Javascript library. Should be loaded in head section -->
            <script 
            src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/stable/kr-payment-form.min.js"
            kr-public-key="<?php echo $client->getPublicKey(); ?>"
            kr-post-url-success="paid.php">
            </script>


            <!-- theme and plugins. should be loaded after the javascript library -->
            <!-- not mandatory but helps to have a nice payment form out of the box -->
            <link rel="stylesheet" 
            href="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic-reset.css">
            <script 
            src="<?php echo $client->getClientEndpoint();?>/static/js/krypton-client/V4.0/ext/classic.js">
            </script>
            </head>
            <body style="padding-top:20px">
            <!-- payment form -->
            <div class="kr-embedded"
            kr-form-token="<?php echo $formToken;?>">


                <!-- payment form fields -->
                <div class="kr-pan"></div>
                <div class="kr-expiry"></div>
                <div class="kr-security-code"></div>  

                <!-- payment form submit button -->
                <button class="kr-payment-button"></button>


                <!-- error zone -->
                <div class="kr-form-error"></div>
            </div>  
            </body>
            </html>
        <?php
    }

}