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
        $this->load->library('upload');
    }

    public function index()
    {
      $datos = [
        'view' => 'catalogo/index',
        'catalogos' => $this->mdCata->ultimos()
      ];
      
      $this->load->view('normal_view', $datos);
      
    }

    public function agregar()
    {
      $datos = [
        'view' => 'catalogo/add_edit',
        'action' => 'catalogo/add'
      ];
      
      $this->load->view('normal_view', $datos);
    }

    public function add(){
      $data=[
        'catalogo_admin' => $_SESSION['usuarios_id'],
        'catalogo_nombre' => $this->input->post('txtNom'),
        'catalogo_descripcion' => $this->input->post('txtDes'),
        'catalogo_fecha_inicio' => $this->input->post('txtIni'),
        'catalogo_fecha_final' => $this->input->post('txtFin')
      ];
      $id = $this->mdCata->add($data);
      $msjFile='';

      $rutaElemento='assets/uploads/catalogo/'.$id.'.pdf';
      if (move_uploaded_file($_FILES["filCat"]["tmp_name"], $rutaElemento)) {
        $msjFile='<br>* Carga de archivo exitoso';
      }

      if(isset($_FILES["filCat"]) && $_FILES["filPor"]!==''){
        $rutaImagen='assets/uploads/catalogo/'.$id.'.png';
        if (move_uploaded_file($_FILES["filPor"]["tmp_name"], $rutaImagen)) {
          $msjFile.='<br>* Carga de archivo imagen';
        }
      }

      $this->session->set_flashdata('success', '!Excelente, se ha creado el registro'.$msjFile);
      redirect(base_url('catalogo'), $datos);
    }



    public function editar($id, $nombre)
    {
      $datos = [
        'view' => 'catalogo/add_edit',
        'datoCatalogo' => $this->mdCata->single($id),
        'action' => 'catalogo/update/'.$id
      ];
      
      $this->load->view('normal_view', $datos);
    }

    public function update($id){
      $data=[
        'catalogo_nombre' => $this->input->post('txtNom'),
        'catalogo_descripcion' => $this->input->post('txtDes'),
        'catalogo_fecha_inicio' => $this->input->post('txtIni'),
        'catalogo_fecha_final' => $this->input->post('txtFin')
      ];
      $this->mdCata->update($id, $data);

      $msjFile='';
      if(isset($_FILES["filCat"]) && $_FILES["filCat"]!==''){
        $rutaElemento='assets/uploads/catalogo/'.$id.'.pdf';
        if (move_uploaded_file($_FILES["filCat"]["tmp_name"], $rutaElemento)) {
          $msjFile='<br>* Carga de archivo exitoso';
        }
      }

      if(isset($_FILES["filPor"]) && $_FILES["filPor"]!==''){
        $rutaImagen='assets/uploads/catalogo/'.$id.'.png';
        if (move_uploaded_file($_FILES["filPor"]["tmp_name"], $rutaImagen)) {
          $msjFile.='<br>* Carga de archivo imagen';
        }
      }
      

      $this->session->set_flashdata('success', '!Excelente, se ha modificado el registro'.$msjFile);
      redirect(base_url('catalogo'), $datos);
    }

    public function descargar($id, $nombre){
      $rutaArchivo = "assets/uploads/catalogo/".$id.".pdf";
      if(file_exists($rutaArchivo)){
        $nombreArchivo = basename($nombre.'.pdf');
        # Algunos encabezados que son justamente los que fuerzan la descarga
        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=$nombreArchivo");
        # Leer el archivo y sacarlo al navegador
        readfile($rutaArchivo);
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
