<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categorias_model extends CI_Model {
    public function getAll($filtros=array()){
        $this->db->select('*');
        if (isset($filtros['where'])) {
            $this->db->group_start();
            $this->db->where($filtros['where'][0],$filtros['where'][1]);
            $this->db->group_end();
        }
        if (isset($filtros['where_in'])) {
            $this->db->group_start();
            $this->db->where_in($filtros['where_in'][0],$filtros['where_in'][1]);
            $this->db->group_end();
        }

        if (isset($filtros['where_arr'])) {
            $this->db->group_start();
            foreach ($filtros['where_arr'] as $key => $value) {
                $this->db->where($key,$value);
            }
            $this->db->group_end();
        }
        if (isset($filtros['limit'])) {
            $this->db->limit($filtros['limit'][0],$filtros['limit'][1]);
        }
        $this->db->join("medios","medios.medios_id=categorias.categorias_imagen","left");
        
        //No incluir Categoria (ID=15) Sin Categoria
        $this->db->where('categorias_status', 1)->where_not_in('categorias_id', 15);

        $categorias = $this->db->get('categorias');
        return $categorias;
    }
    public function single($id){
        $query = $this->db->query('SHOW COLUMNS FROM categorias');
        $valores = $query->result_array();
        $this->db->select('*')->where("categorias_id",$id)->join("medios","medios.medios_id=categorias.categorias_imagen","left");
        $query = $this->db->get('categorias');
        $data = array();
        $data['categorias_imagen_enlace'] = "";
        foreach ($query->result_array() as $key2 => $value2) {
            $data['categorias_imagen_enlace'] = $value2['medios_url'];
            foreach ($valores as $key => $value) {
                $data += [$value["Field"]=>$value2[$value["Field"]]];
            }
        }
        $data['categorias_banner_desktop_enlace'] = "";
        $data['categorias_banner_mobile_enlace'] = "";
        
        $this->db->select("medios_id,medios_url")
        ->where_in("medios_id",array($data['categorias_banner_desktop'],$data['categorias_banner_mobile']));
        $res = $this->db->get("medios");
        
        foreach ($res->result_array() as $key => $value) {
            if ($value['medios_id']==$data['categorias_banner_desktop']) {
                $data['categorias_banner_desktop_enlace'] = $value['medios_url'];
            }
            if ($value['medios_id']==$data['categorias_banner_mobile']) {
                $data['categorias_banner_mobile_enlace'] = $value['medios_url'];
            }
        }

        return $data;
    }
    function vacio(){
        $query = $this->db->query('SHOW COLUMNS FROM categorias');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $data += [$value["Field"]=>""];
        }
        return $data;
    }
    public function add(){
        $query = $this->db->query('SHOW COLUMNS FROM categorias');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
        $field = $value["Field"];
        if ($value["Field"]!="categorias_id" and $this->input->post($value["Field"])!=NULL) {
            $data += [$value["Field"]=>$this->input->post($value["Field"])];
        }
        }
        $ingresar['data'] = $this->db->insert('categorias', $data);
        return $ingresar;
    }
    function save(){
        $query = $this->db->query('SHOW COLUMNS FROM categorias');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
          $field = $value["Field"];
          if ($value["Field"]!="categorias_id" and $this->input->post($value["Field"])!=NULL) {
            $data += [$value["Field"]=>$this->input->post($value["Field"])];
          }
        }
        $data += ["categorias_slug"=>limpiarUri($this->input->post("categorias_nombre"))];
        $ingresar['data'] = $this->db->insert('categorias', $data);
        return $ingresar;
    }
    function edit(){
        $query = $this->db->query('SHOW COLUMNS FROM categorias');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="categorias_id" and $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }
        $data += ["categorias_slug"=>limpiarUri($this->input->post("categorias_nombre"))];
        $this->db->where('categorias_id', $this->input->post('categorias_id'));
        $ingresar['data'] = $this->db->update('categorias', $data);
        return $ingresar;
    }

}