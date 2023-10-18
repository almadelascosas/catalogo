<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Medios_model extends CI_Model {
    public function getAll($page=1,$limite=NULL){
        
        $this->db->select('*');
        if ($_SESSION['tipo_accesos']!=0) {
            $this->db->where('medios_user',$_SESSION['usuarios_id']);
        }
        if ($limite==NULL) {
            $limit = 10;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(10, $limit);
        }
        $this->db->order_by('medios_id', "DESC");
        $medios = $this->db->get('medios')->result_array();
        return $medios;
    }

    public function get_in($where='',$cons = array()){
      if($cons!=array() && $where!=''){
        $this->db->select('*')
        ->where_in($where, $cons);
        $medios = $this->db->get('medios');
        return $medios;
      }
    }
    public function single($id){
        $query = $this->db->query('SHOW COLUMNS FROM medios');
        $valores = $query->result_array();
        $this->db->select('*')->where("medios_id",$id);
        $query = $this->db->get('medios');
        $data = array();
        foreach ($query->result_array() as $key2 => $value2) {
            foreach ($valores as $key => $value) {
                $data += [$value["Field"]=>$value2[$value["Field"]]];
            }
        }
        return $data;
    }
    function vacio(){
        $query = $this->db->query('SHOW COLUMNS FROM medios');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $data += [$value["Field"]=>""];
        }
        return $data;
    }
    public function add(){
        $query = $this->db->query('SHOW COLUMNS FROM medios');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
        $field = $value["Field"];
        if ($value["Field"]!="medios_id" and $this->input->post($value["Field"])!=NULL) {
            $data += [$value["Field"]=>$this->input->post($value["Field"])];
        }
        }
        $ingresar['data'] = $this->db->insert('medios', $data);
        return $ingresar;
    }

    function save($guardado = array()){
        if ($guardado==array()) {
            $ingresar['data'] = NULL;
        }else{
            $query = $this->db->query('SHOW COLUMNS FROM medios');
            $valores = $query->result_array();
            $data = array();
            foreach ($valores as $key => $value) {
              $field = $value["Field"];
              if (isset($guardado[$value["Field"]]) and $value["Field"]!="medios_id" and $guardado[$value["Field"]]!=NULL) {
                $data += [$value["Field"]=>$guardado[$value["Field"]]];
              }
            }
            $ingresar['data'] = $this->db->insert('medios', $data);
        }
        return $ingresar;
    }

    function edit($imgGuardada = array()){
        $query = $this->db->query('SHOW COLUMNS FROM medios');
        $valores = $query->result_array();
        $data = array();

        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="medios_id" && $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }

        //Caso de cargar nueva imagen... borramos antiguas
        if(isset($imgGuardada['medios_url'])){

            if(file_exists($data['medios_url'])) unlink($data['medios_url']);
            $data['medios_url']=$imgGuardada['medios_url'];

            if(file_exists($data['medios_enlace_miniatura'])) unlink($data['medios_enlace_miniatura']);
            $data['medios_enlace_miniatura']=$imgGuardada['medios_enlace_miniatura'];
            
            $data['medios_titulo']=$imgGuardada['medios_titulo'];
            $data['medios_user']  =$imgGuardada['medios_user'];
            $data['medios_fecha_registro']=date('Y-m-d H:i:s');
        }

        $this->db->where('medios_id', $this->input->post('medios_id'));
        $ingresar['data'] = $this->db->update('medios', $data);
        return $ingresar;
    }

}
