<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Categorias extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('categorias');
        $this->load->helper(array('commun'));
        $this->load->model("categorias_model");
    }

    public function index()
    {
      $datos = array();
      $datos = array();
      $datos['view'] = "categorias/index";
      $datos['css_data'] = array();
      $datos['js_data'] = array(
          'assets/js/pages/categorias/index.js?v='.rand(99,9999),
      );
      $filtros = array();
      //$filtros['where'] = array("categorias_padre",105);
      $datos['categorias'] = $this->categorias_model->getAll($filtros);
      $this->load->view('normal_view', $datos);
    }
    public function agregar()
    {
      $datos = array();
      $datos = array();
      $datos['view'] = "categorias/add";
      $datos['css_data'] = array(
        base_url().'assets/plugins/dropify/dropify.min.css',
      );
      $datos['js_data'] = array(
        'assets/plugins/dropify/dropify.min.js',
        'assets/js/pages/categorias/add.js?v='.rand(99,9999),
      );
      $datos['css_data_req'] = array();
      $datos['js_data_req'] = array();
      $datos['categorias'] = $this->categorias_model->getAll();
      $datos['categoria'] = $this->categorias_model->vacio();
      $this->load->view('normal_view', $datos);
    }
    public function editar($id,$nombre)
    {
      $datos = array();
      $datos = array();
      $datos['view'] = "categorias/add";
      $datos['css_data'] = array(
        base_url().'assets/plugins/dropify/dropify.min.css',
      );
      $datos['js_data'] = array(
        'assets/plugins/dropify/dropify.min.js',
        'assets/js/pages/categorias/add.js?v='.rand(99,9999),
      );
      $datos['css_data_req'] = array();
      $datos['js_data_req'] = array();
      $datos['categorias'] = $this->categorias_model->getAll();
      $datos['categoria'] = $this->categorias_model->single($id);
      $this->load->view('normal_view', $datos);
    }

    public function guardar(){
      $datos=array();
        if ($this->input->post('categorias_id')!="" and $this->input->post('categorias_id')!=0) {
            $ingresar = $this->categorias_model->edit();
        }else {
            $ingresar = $this->categorias_model->save();
        }
        if ($ingresar['data']) {
            $this->session->set_userdata('message_tipo', "success");
            $this->session->set_userdata('message', "Guardado Éxitoso");
            redirect(base_url()."categorias", $datos);
        }else {
            $this->session->set_userdata('message_tipo', "error");
            $this->session->set_userdata('message', "Error al registrar.");
            redirect(base_url()."categorias", $datos);
        }
    }
    public function delete()
    {
      $datos = array();
      $id=$this->input->post("id");
      $query = $this->db->delete('categorias', array('categorias_id' => $id));
     /* if ($query) {
        $this->session->set_userdata('message_tipo', "success");
        $this->session->set_userdata('message', "Guardado Éxitoso");
        redirect(base_url()."categorias", $datos);
    }else {
        $this->session->set_userdata('message_tipo', "error");
        $this->session->set_userdata('message', "Error al registrar.");
        redirect(base_url()."categorias", $datos);
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
}
