<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_model extends CI_Model {
    public function getAll(){
        $this->db->select('*')
        ->join("categorias","categorias.categorias_id=menu.menu_categorias_id","left")
        ->join("medios","medios.medios_id=categorias.categorias_imagen","left");
        $menu['menu'] = $this->db->get('menu');
        
        $categorias = array();
        array_push($categorias, 0);

        foreach ($menu['menu']->result_array() as $key => $value) {
            array_push($categorias, $value['menu_categorias_id']);
        }

        $this->db->select('*')
        ->join("medios","medios.medios_id=categorias.categorias_imagen","left");
        $this->db->where_in('categorias_padre',$categorias);
        $menu['subcategorias'] = $this->db->get('categorias');

        return $menu;
    }
    public function getAllDash(){
        $this->db->select('*');
        $menu = $this->db->get('menu_dash');
        return $menu;
    }
    public function get_in($where='',$cons = array()){
      if($cons!=array() && $where!=''){
        $this->db->select('*')
        ->where_in($where, $cons);
        $menu = $this->db->get('menu');
        return $menu;
      }
    }
    public function single($id){
        $query = $this->db->query('SHOW COLUMNS FROM menu');
        $valores = $query->result_array();
        $this->db->select('*')->where("menu_id",$id);
        $query = $this->db->get('menu');
        $data = array();
        foreach ($query->result_array() as $key2 => $value2) {
            foreach ($valores as $key => $value) {
                $data += [$value["Field"]=>$value2[$value["Field"]]];
            }
        }
        return $data;
    }
    function vacio(){
        $query = $this->db->query('SHOW COLUMNS FROM menu');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $data += [$value["Field"]=>""];
        }
        return $data;
    }
    public function add(){
        $query = $this->db->query('SHOW COLUMNS FROM menu');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
        $field = $value["Field"];
        if ($value["Field"]!="menu_id" and $this->input->post($value["Field"])!=NULL) {
            $data += [$value["Field"]=>$this->input->post($value["Field"])];
        }
        }
        $ingresar['data'] = $this->db->insert('menu', $data);
        return $ingresar;
    }
    function save($guardado = array()){
        if ($guardado==array()) {
            $ingresar['data'] = NULL;
        }else{
            $query = $this->db->query('SHOW COLUMNS FROM menu');
            $valores = $query->result_array();
            $data = array();
            foreach ($valores as $key => $value) {
              $field = $value["Field"];
              if (isset($guardado[$value["Field"]]) and $value["Field"]!="menu_id" and $guardado[$value["Field"]]!=NULL) {
                $data += [$value["Field"]=>$guardado[$value["Field"]]];
              }
            }
            $ingresar['data'] = $this->db->insert('menu', $data);
        }
        return $ingresar;
    }
    function edit(){
        $query = $this->db->query('SHOW COLUMNS FROM menu');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="menu_id" and $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }
        $this->db->where('menu_id', $this->input->post('menu_id'));
        $ingresar['data'] = $this->db->update('menu', $data);
        return $ingresar;
    }

}
