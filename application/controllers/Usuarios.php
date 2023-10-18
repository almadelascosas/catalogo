<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Usuarios extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('usuarios');
        $this->load->helper(array('commun'));
        $this->load->model("usuarios_model");
        $this->load->model("permisos_model");
        $this->load->model("categorias_model");
        $this->load->model("general_model");
    }

    public function index()
    {
      $datos = array();
      $datos['view'] = "usuarios/index";
      $datos['css_data'] = array();
      $datos['js_data'] = array(
          'assets/js/pages/usuarios/index.js?v='.rand(99,9999),
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
      $filtros = array();
      $datos['usuarios'] = $this->usuarios_model->getAll($filtros,$page,$limite);
      
      $this->load->view('normal_view', $datos);
    }
    public function agregar()
    {
      $filtrosCat = [];
      $filtrosCat['where'] = array("categorias_padre",0);

      $filt_muni = [];
      $filt_muni['where'] = array("departamento_id",0);

      $datos = [
        'view' => "usuarios/add",
        'css_data_req' => [],
        'js_data_req' => [],
        'js_data' => [
                    'assets/plugins/dropify/dropify.min.js',
                    'assets/js/pages/usuarios/add.js?'.rand()
                  ],
        'tipo_accesos' => $this->permisos_model->getActivos(),
        'usuario' => $this->usuarios_model->vacio(),
        'departamentos' => $this->general_model->obtenerDepartamentos(),
        'categorias' => $this->categorias_model->getAll($filtrosCat),
        'municipios' => $this->general_model->obtenerMunicipios($filt_muni)
      ];

      $this->load->view('normal_view', $datos);
    }

    public function editar($id, $nombre="")
    {
      $filtros =[];
      $filtros['where'] = array("categorias_padre",0);

      $usuario=$this->usuarios_model->single($id);
      
      $filt_muni = [];
      $filt_muni['where'] = array("departamento_id",$usuario['usuarios_departamento']);

      $datos = [
        'view' => "usuarios/add",
        'texto' => 'Modificar datos',
        'css_data_req' => [],
        'js_data_req' => [],
        'js_data' => [
              'assets/plugins/dropify/dropify.min.js',
              'assets/js/pages/usuarios/add.js?'.rand()
            ],
        'tipo_accesos' => $this->permisos_model->getActivos(),
        'usuario' => $usuario,
        'departamentos' => $this->general_model->obtenerDepartamentos(),
        'categorias' => $this->categorias_model->getAll($filtros),
        'municipios' => $this->general_model->obtenerMunicipios($filt_muni)
      ];

      $this->load->view('normal_view', $datos);
    }

    public function guardar(){
      $datos = array();

      if (intval($this->input->post('editar'))===1) {
          $ingresar = $this->usuarios_model->edit();
          $this->session->set_flashdata('success', 'Se ha actualizado con exito Usuario');
      }else {
          $ingresar = $this->usuarios_model->save();
          $this->session->set_flashdata('success', 'Se ha creado con exito Usuario');
      }

      if (!$ingresar['data'])  $this->session->set_flashdata('error', 'No fue posible crear Usuario');
      redirect(base_url("usuarios"), $datos);
    }
    public function delete()
    {
      $datos = array();
      $id=$this->input->post("id");
      $query = $this->db->delete('usuarios', array('usuarios_id' => $id));
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
}
