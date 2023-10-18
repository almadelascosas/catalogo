<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Permisos extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('permisos');
        $this->load->helper(array('commun'));
        $this->load->model("menu_model");
        $this->load->model("permisos_model");
    }

    public function index()
    {
      $datos = array();
      $datos['view'] = "permisos/index";
      $datos['css_data'] = array(
        base_url().'assets/js/plugins/dragula/dragula.css',
        base_url().'assets/css/plugins/boostrap-select/boostrap-select.min.css',
      );
      $datos['js_data'] = array(
        'assets/js/plugins/bootstrap-select/bootstrap-select.min.js',
        'assets/js/plugins/dragula/dragula.min.js',
        'assets/js/pages/permisos/index.js?v='.rand(99,9999),
      );
      $datos['tipo_accesos'] = $this->permisos_model->getAll();
      $datos['menu'] = $this->menu_model->getAllDash();
      $this->db->select_max('menu_dash_id');
      $res = $this->db->get('menu_dash');
      $datos['max_id'] = 0;
      foreach ($res->result_array() as $key => $value) {
        $datos['max_id'] = $value['menu_dash_id'];
      }
      $this->load->view('normal_view', $datos);
    }
    public function guardarMenu(){
        $datos = array();
        $batch = array();
        $borrado = $this->db->empty_table('menu_dash');
        if($borrado==true){
            for ($i=0; $i < count($_POST['menu_dash_id']); $i++) {
            array_push($batch, array(
                'menu_dash_id' => $_POST['menu_dash_id'][$i],
                'menu_dash_texto' => $_POST['menu_dash_texto'][$i],
                'menu_dash_enlace' => $_POST['menu_dash_enlace'][$i],
                'menu_dash_padre' => $_POST['menu_dash_padre'][$i],
                'menu_dash_permiso' => $_POST['menu_dash_permiso'][$i],
                'menu_dash_orden' => $i+1,
            ));
            }
            $menu = $this->db->insert_batch('menu_dash', $batch);
            if ($menu==true) {
            redirect(base_url()."permisos?mensaje=exito", $datos); 
            }else{
            redirect(base_url()."permisos?mensaje=Error", $datos); 
            }
        }else{
            redirect(base_url()."permisos?mensaje=Error", $datos);
        }
    }
}