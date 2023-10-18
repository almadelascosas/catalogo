<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class General extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('general');
        $this->load->helper(array('commun'));
        $this->load->model("menu_model");
        $this->load->model("general_model");
        $this->load->model("categorias_model");
        $this->load->model("productos_model");
    }

    public function index()
    {
      $datos = array();
      $datos['view'] = "general/index";
      $datos['css_data'] = array(
        base_url().'assets/js/plugins/dragula/dragula.css',
        base_url().'assets/plugins/dropify/dropify.min.css',
      );
      $datos['js_data'] = array(
        'assets/js/plugins/dragula/dragula.min.js',
        'assets/plugins/dropify/dropify.min.js',
        'assets/js/pages/general/index.js?v='.rand(99,9999),
      );

      $this->db->select("*");
      $datos['whatsapp_plugin'] = $this->db->get("whatsapp_plugin");

      $datos['menu'] = $this->menu_model->getAll();
      $datos['banner_home'] = $this->general_model->bannerHome();
      $datos['banner_home']['banner_imagen_mobile_url'] = '';
      
      $this->db->select('*');
      $datos['metodos_pagos'] = $this->db->get('alma_metodos_pagos');
      
      $this->db->select('medios_url');
      $this->db->where('medios_id', $datos['banner_home']['banner_imagen_mobile']);
      $res = $this->db->get('medios');
      foreach ($res->result_array() as $key => $value)  $datos['banner_home']['banner_imagen_mobile_url'] = $value['medios_url'];
      
      $datos['banner_corporativos'] = $this->general_model->bannerCorporativos();
      $datos['banner_corporativos']['banner_imagen_mobile_url'] = '';
      
      $this->db->select('medios_url');
      $this->db->where('medios_id', $datos['banner_corporativos']['banner_imagen_mobile']);
      $res = $this->db->get('medios');
      foreach ($res->result_array() as $key => $value) $datos['banner_corporativos']['banner_imagen_mobile_url'] = $value['medios_url'];

      $this->db->select_max('menu_id');
      $res = $this->db->get('menu');
      $datos['max_id'] = 0;
      foreach ($res->result_array() as $key => $value) {
        $datos['max_id'] = $value['menu_id'];
      }

      $filter = array();
      $page=1;
      $limite = array(100000,0);
      
      $filter_cat = array();
      $filter_cat['where'] = array("categorias_padre",0);

      $datos['categorias'] = $this->categorias_model->getAll();
      $datos['categorias_menu'] = $this->categorias_model->getAll($filter_cat);
      $datos['productos'] = $this->productos_model->getAll($filter,$page,$limite);

      $this->db->select('banner_app.*,medios.medios_url as image_url');
      $this->db->join('medios','medios.medios_id=banner_app.banner_app_imagen_url','left');
      $datos['banner_app'] = $this->db->get('banner_app');

      $this->db->select('banner_home_noticias.*,medios.medios_url as image_url');
      $this->db->join('medios','medios.medios_id=banner_home_noticias.banner_home_noticias_imagen','left');
      $datos['bannerMensajes'] = $this->db->get('banner_home_noticias');

      $datos['productos'] = $this->productos_model->getAll($filter,$page,$limite);
      
      $this->load->view('normal_view', $datos);
    
    }

    public function guardarWhatsapp(){
      $datos = array();
      $batch = array();
      $borrado = $this->db->empty_table('whatsapp_plugin');
      
      for ($i=0; $i < count($_POST['whatsapp_plugin_id']); $i++) {
        $status = 0;
        if (isset($_POST['whatsapp_plugin_estatus'][$i]) && $_POST['whatsapp_plugin_estatus'][$i]==1) {
          $status = 1;
        }
        array_push($batch, array(
          'whatsapp_plugin_id' => $_POST['whatsapp_plugin_id'][$i],
          'whatsapp_plugin_telefono' => $_POST['whatsapp_plugin_telefono'][$i],
          'whatsapp_plugin_nombre' => $_POST['whatsapp_plugin_nombre'][$i],
          'whatsapp_plugin_mensaje' => $_POST['whatsapp_plugin_mensaje'][$i],
          'whatsapp_plugin_cantidad' => $_POST['whatsapp_plugin_cantidad'][$i],
          'whatsapp_plugin_estatus' => $status
        ));
      }
      $menu = $this->db->insert_batch('whatsapp_plugin', $batch);
      if ($menu==true) {
        redirect(base_url()."general?mensaje=exito", $datos); 
      }else{
        redirect(base_url()."general?mensaje=Error", $datos); 
      }
    }

    public function guardarMetodos(){
      $datos = array();
      $batch = array();
      $borrado = $this->db->empty_table('alma_metodos_pagos');
      
      for ($i=0; $i < count($_POST['alma_metodos_pagos_id']); $i++) {
        $status = 0;
        if (isset($_POST['alma_metodos_pagos_estatus'][$i]) && $_POST['alma_metodos_pagos_estatus'][$i]==1) {
          $status = 1;
        }
        array_push($batch, array(
          'alma_metodos_pagos_id' => $_POST['alma_metodos_pagos_id'][$i],
          'alma_metodos_pagos_titulo' => $_POST['alma_metodos_pagos_titulo'][$i],
          'alma_metodos_pagos_estatus' => $status
        ));
      }
      $menu = $this->db->insert_batch('alma_metodos_pagos', $batch);
      if ($menu==true) {
        redirect(base_url()."general?mensaje=exito", $datos); 
      }else{
        redirect(base_url()."general?mensaje=Error", $datos); 
      }
    }

    public function guardarMenu(){
      $datos = array();
      $batch = array();
      $borrado = $this->db->empty_table('menu');
      if($borrado==true){
        for ($i=0; $i < count($_POST['menu_id']); $i++) {
          array_push($batch, array(
            'menu_id' => $_POST['menu_id'][$i],
            'menu_texto' => $_POST['menu_texto'][$i],
            'menu_enlace' => $_POST['menu_enlace'][$i],
            'menu_padre_id' => $_POST['menu_padre'][$i],
            'menu_categorias_id' => $_POST['menu_categorias_id'][$i],
            'menu_orden' => $i+1,
          ));
        }
        $menu = $this->db->insert_batch('menu', $batch);
        ($menu==true) ? $this->session->set_flashdata('success', '!Excelente, se ha guardado Menu') : $this->session->set_flashdata('error', '!Ups, no fue posible guardar Menu');
      }else{
        $this->session->set_flashdata('error', '!Ups, no fue posible guardar registro de Menu');
      }
      redirect(base_url("general#menu"), $datos);
    }
    
    public function guardarBannerHome(){
      $datos = array();
      $batch = array();

      $borrado = $this->db->empty_table('banner_home');
      
      if($borrado==true){
        
        array_push($batch, array(
          'banner_imagen' => $_POST['banner_imagen'],
          'banner_imagen_mobile' => $_POST['banner_imagen_mobile'],
          'banner_enlace' => $_POST['banner_enlace']
        ));
        $menu = $this->db->insert_batch('banner_home', $batch);
        ($menu==true) ? $this->session->set_flashdata('success', '!Excelente, se ha guardado registro de Banner Home') : $this->session->set_flashdata('error', '!Ups, no fue posible guardar registro de Banner Home');

      }else{
        $this->session->set_flashdata('error', '!Ups, no fue posible guardar registro de Banner Home');
      }
      redirect(base_url("general#bannerhome"), $datos);
    }

    public function guardarBannerCorporativos(){
      $datos = array();
      $batch = array();

      $borrado = $this->db->empty_table('banner_corporativos');
      
      if($borrado==true){        
        array_push($batch, array(
          'banner_imagen' => $_POST['banner_imagen'],
          'banner_imagen_mobile' => $_POST['banner_imagen_mobile'],
          'banner_enlace' => $_POST['banner_enlace'],
          'banner_target' => $_POST['banner_target']
        ));
        $menu = $this->db->insert_batch('banner_corporativos', $batch);
        ($menu==true) ? $this->session->set_flashdata('success', '!Excelente, se ha guardado registro de Banner Corporativos') : $this->session->set_flashdata('error', '!Ups, no fue posible guardar registro de Banner Corporativos');
        
      }else{
        $this->session->set_flashdata('error', '!Ups, no fue posible guardar registro de Banner Corporativos');
      }
      redirect(base_url("general#corporativos"), $datos);
    }
    
    public function guardarBannerApp(){
      $datos = array();
      $batch = array();
      $borrado = $this->db->empty_table('banner_app');
      if($borrado==true){
        for ($i=0; $i < count($_POST['banner_app_imagen']); $i++) {
          array_push($batch, array(
            'banner_app_imagen_url' => $_POST['banner_app_imagen'][$i],
            'banner_app_texto' => $_POST['banner_app_texto'][$i],
            'banner_app_tipo' => $_POST['banner_app_tipo'][$i],
            'banner_app_id_redireccion' => $_POST['banner_app_id_redireccion'][$i],
            'banner_app_enlace_redireccion' => $_POST['banner_app_enlace_redireccion'][$i]
          ));
        }
        $menu = $this->db->insert_batch('banner_app', $batch);
        if ($menu==true) {
          redirect(base_url()."general?mensaje=exito", $datos); 
        }else{
          redirect(base_url()."general?mensaje=Error", $datos); 
        }
      }else{
        redirect(base_url()."general?mensaje=Error", $datos);
      }
    }

    public function guardarBannerMensajes(){
      $datos = array();
      for ($i=0; $i < count($_POST['banner_home_noticias_id']); $i++) {
        $data = array(
          'banner_home_noticias_texto' => $_POST['banner_home_noticias_texto'][$i],
          'banner_home_noticias_imagen' => $_POST['banner_home_noticias_imagen'][$i]
        );
  
        $this->db->where('banner_home_noticias_id', $_POST['banner_home_noticias_id'][$i]);
        $this->db->update('banner_home_noticias', $data);
      }
      redirect(base_url()."general?mensaje=exito", $datos);

    }

    public function delete()
    {
      $datos = array();
      $id=$this->input->post("id");
      $query = $this->db->delete('productos', array('productos_id' => $id));
     /* if ($query) {
        $this->session->set_userdata('message_tipo', "success");
        $this->session->set_userdata('message', "Guardado Éxitoso");
        redirect(base_url()."usuarios", $datos);
    }else {
        $this->session->set_userdata('message_tipo', "error");
        $this->session->set_userdata('message', "Error al registrar.");
        redirect(base_url()."usuarios", $datos);
    }*/
      if ($query) {
        $datos['mensaje']="Eliminado con éxito";
        $datos['result']=1;
      }else {
        $datos['mensaje']="Hubo un error, intente de nuevo!";
        $datos['result']=0;
      }
      echo json_encode($datos);
    }
    public function subirvideo()
    {
      $datos = array();
      $nombre_fichero =  $_SERVER['DOCUMENT_ROOT']."/assets/uploads/videosproductos/".limpiarUri(date("m-Y"));

      if (file_exists($nombre_fichero)) {
        if (move_uploaded_file($_FILES["productos_video"]["tmp_name"], $nombre_fichero."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video']['name']))) {
              $datos["medios_url"] = "assets/uploads/videosproductos/".limpiarUri(date("m-Y"))."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video']['name']);
              $nombreEx = explode(".",$_FILES["productos_video"]["name"]);
              $datos["medios_titulo"] = "";
              for ($i=0; $i < count($nombreEx); $i++) {
                  if ($i!=count($nombreEx)-1) {
                      $datos["medios_titulo"] .= $nombreEx[$i];
                  }
              }
              $datos["medios_user"] = $_SESSION['usuarios_id'];
              $datos["success"] = 1;

          } else {
              $datos["success"] = 0;
          }
      } else {
          mkdir($_SERVER['DOCUMENT_ROOT']."/assets/uploads/videosproductos/".limpiarUri(date("m-Y")), 0777);
          if (move_uploaded_file($_FILES["productos_video"]["tmp_name"], $nombre_fichero."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video']['name']))) {
              $datos["medios_url"] = "assets/uploads/videosproductos/".limpiarUri(date("m-Y"))."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video']['name']);
              $nombreEx = explode(".",$_FILES["productos_video"]["name"]);
              $datos["medios_titulo"] = "";
              for ($i=0; $i < count($nombreEx); $i++) {
                  if ($i!=count($nombreEx)-1) {
                      $datos["medios_titulo"] .= $nombreEx[$i];
                  }
              }
              $datos["medios_user"] = $_SESSION['usuarios_id'];
              $datos["success"] = 1;
          } else {
              $datos["success"] = 0;
          }
      }
      return $datos;
    }

    public function obtenerMunicipios($id_departamento=0,$id_municipio=0){
      $datos = array();
      $filter = array();
      if ($id_departamento!=0) {
        $filter['where'] = array('departamento_id', $id_departamento);
      }
      $datos['id_municipio'] = $id_municipio;
      $datos['municipios'] = $this->general_model->obtenerMunicipios($filter);
      $this->load->view("themes/admin/municipios/options", $datos);
    }
    
    public function obtenerDepartamentos($id_departamento=0){
      $datos = array();

      $filter = array();
      $datos['id_departamento'] = $id_departamento;
      $datos['departamentos'] = $this->general_model->obtenerDepartamentos();
      $this->load->view("themes/admin/departamentos/options", $datos);
    }


}
