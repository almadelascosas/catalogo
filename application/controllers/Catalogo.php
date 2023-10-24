<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');

class Catalogo extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('catalogo');
        $this->load->helper(array('commun'));
        $this->load->model("catalogo_model", "mdCata");
    }

    public function index()
    {
      $datos = [
        'view' => 'catalogo/index',
        'catalogos' => $this->mdCata->ultimos()
      ];
      
      $this->load->view('normal_view', $datos);
      
    }

    public function notificarPedidoRetrasado(){
      
      if (isset($_POST['pedidos_id'])) {
        $pedido = $this->pedidos_model->singleNew($_POST['pedidos_id']);
        $receptores = array();
        foreach ($pedido['productos']->result_array() as $key => $value) {
          array_push($receptores,array(
            "correo" => $value['email'],
            "nombre" => $value['name'],
          ));
        }
        /*
        array_push($receptores,array(
          "correo" => "softmenaca@gmail.com",
          "nombre" => "Softmena",
        ));
        array_push($receptores,array(
          "correo" => "felipe.alamdelascosas@gmail.com",
          "nombre" => "Felipe",
        ));
        */

        $enviarMail = $this->mailing_model->pedidoRetrasado($pedido,$receptores);

        echo json_encode($enviarMail);

      }

    }



    public function pruebaSendGrid(){
      //require_once $_SERVER['DOCUMENT_ROOT']."/application/libraries/Sendgrid/sendgrid-php.php";
      $apiKey = getenv("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");
      require_once $_SERVER['DOCUMENT_ROOT']."/application/libraries/Sendgrid/sendgrid-php.php";
      
      $email = new \SendGrid\Mail\Mail();
      $email->setFrom("info@almadelascosas.co", "Example User");
      $email->setSubject("Sending with Twilio SendGrid is Fun");
      $email->addTo("softmenaca@gmail.com", "Example User");
      $email->addContent("text/plain", "and easy to do anywhere, even with PHP");
      $email->addContent(
          "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
      );
      $sendgrid = new \SendGrid(getenv('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'));
      try {
          $response = $sendgrid->send($email);
          debug($response);
          print $response->statusCode() . "\n";
          print_r($response->headers());
          print $response->body() . "\n";
      } catch (Exception $e) {
          echo 'Caught exception: '. $e->getMessage() ."\n";
      }
    }

}
