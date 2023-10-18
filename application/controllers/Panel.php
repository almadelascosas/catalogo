<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Panel extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('panel');
        ini_set('display_errors', 0);
        $this->load->helper(array('commun'));
        $this->load->model("pedidos_model");
        $this->load->model("productos_model");
        $this->load->model("mailing_model");

    }

    public function index()
    {
      $datos = [
        'view' => 'dashboard/index',
        'css_data' => ['assets/plugins/snackbar/snackbar.min.css'],
        'js_data' => [ 'assets/plugins/snackbar/snackbar.min.js' ]
      ];

      $parametros = array(
        "tipo_consulta" => "pedidos_atrasados",
      );

      $page = 1;
      $limit = 12;
      $limite = array();
      if (!isset($_GET['page'])) {
        $limite=array($limit,$page-1);
      }else{
        $page = $_GET['page']-1;
        $paginado = $limit*$page;
        $limite=array($limit,$paginado);
      }

      $datos['pedidos'] = $this->pedidos_model->getOrders($parametros,$page,$limite);
      
      $param = array();
      $page_prod = 1;
      $limit = 12;
      $limite = array();
      if (!isset($_GET['page_prod'])) {
        $limite=array($limit,$page_prod-1);
      }else{
        $page_prod = $_GET['page_prod']-1;
        $paginado = $limit*$page_prod;
        $limite=array($limit,$paginado);
      }
      $datos['productos_agotados'] = $this->productos_model->getAgotados($param,$page_prod,$limite);
      
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
