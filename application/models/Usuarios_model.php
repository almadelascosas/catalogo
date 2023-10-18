<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuarios_model extends CI_Model {
    
    public function getAll($filtros=array(),$page=1,$limite=NULL){
        $this->db->select('*');
        if (isset($filtros['where_in']) && $filtros['where_in']!=NULL && $filtros['where_in']!=""
        && isset($filtros['where_in_name']) && $filtros['where_in_name']!=NULL && $filtros['where_in_name']!=""
        ) {
            $this->db->where_in($filtros['where_in_name'],$filtros['where_in']);
        }
        if (isset($filtros['where']) && $filtros['where']!=NULL && $filtros['where']!="") {
            if(!is_array($filtros['where'][1])){
                $this->db->where($filtros['where'][0],$filtros['where'][1]);
            }else{
                $this->db->where_in($filtros['where'][0],$filtros['where'][1]);
            }            
        }
        if (isset($_REQUEST['search']) && $_REQUEST['search']!=NULL && $_REQUEST['search']!="") {
            $this->db->like("name", $_REQUEST['search']);
        }
        $this->db->order_by("name", "ASC");
        if ($limite==NULL) {
            $limit = 24;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(24,$limit);
        }else{
            $this->db->limit($limite[0],$limite[1]);
        }
        $usuarios = $this->db->get('usuarios');
        //print $this->db->last_query();
        return $usuarios;
    }

    public function getUserById($id){
        return $this->db->select('user.*, m.medios_url as imagen_perfil, dpto.departamento, muni.municipio')
                           ->join("medios as m","m.medios_id = user.image_profile","left")
                           ->join("departamentos as dpto","dpto.id_departamento = user.usuarios_departamento","left")
                           ->join("municipios as muni","muni.id_municipio = user.usuarios_municipio","left")
                           ->where("user.usuarios_id", $id)
                           ->get('usuarios as user')->result_array();
    }

    public function single($id){
        $query = $this->db->query('SHOW COLUMNS FROM usuarios');
        $valores = $query->result_array();
        $query =  $this->db->select('user.*, m.medios_url as imagen_perfil, dpto.departamento, muni.municipio')
                           ->join("medios as m","m.medios_id = user.image_profile","left")
                           ->join("departamentos as dpto","dpto.id_departamento = user.usuarios_departamento","left")
                           ->join("municipios as muni","muni.id_municipio = user.usuarios_municipio","left")
                           ->where("user.usuarios_id", $id)
                           ->get('usuarios as user')->result_array();
        //print $this->db->last_query();
        if(!isset($query[0]['usuarios_id'])){
            return [];
        }
        
        $data = array();
        foreach ($query as $key2 => $value2) {
            foreach ($valores as $key => $value) {
                $data += [$value["Field"]=>$value2[$value["Field"]]];
            }
            $data += ["imagen_perfil"=>$value2["imagen_perfil"]];
        }  

        $data += ["imagen_banner"=>""];

        $this->db->select('medios_url');
        $this->db->where('medios_id', $data['usuarios_banner']);
        $query = $this->db->get('medios');
        
        foreach ($query->result_array() as $key => $value) {
            $data["imagen_banner"] = $value['medios_url'];
        }

        return $data;
    }
    function vacio(){
        $query = $this->db->query('SHOW COLUMNS FROM usuarios');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $data += [$value["Field"]=>""];
        }
        return $data;
    }
    public function add(){
        $ingresar['mensaje_error']="";
        $query = $this->db->query('SHOW COLUMNS FROM usuarios');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="usuarios_id" and $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }
        $ingresar['data'] = $this->db->insert('usuarios', $data);
        return $ingresar;
    }
    function save(){
        
        $ingresar['mensaje_error']="";
        $this->db->select('usuarios_id');
        $this->db->where('email', $_POST['email']);
        $usuario = $this->db->get('usuarios');
        if ($usuario->num_rows() > 0) {
            $ingresar['mensaje_error']="Este usuario ya éxiste, intenta con otro email.";
            $ingresar['data'] = NULL;
        }else{

            $query = $this->db->query('SHOW COLUMNS FROM usuarios');
            $valores = $query->result_array();
            $data = array();
            foreach ($valores as $key => $value) {
              $field = $value["Field"];
              if ($value["Field"]!="password" and $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
                
              }
            }
            $data += ["password"=>encodeItem($this->input->post("password"))];
            $ingresar['data'] = $this->db->insert('usuarios', $data);
        }
        return $ingresar;
        
    }

    function getByEmail($email){
        return $this->db->where('email', $email)->get('usuarios')->result_array();
    }
    
    function edit(){
        $query = $this->db->query('SHOW COLUMNS FROM usuarios');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="password" and $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }
        if ($this->input->post("password")!="") {
            $data += ["password" => encodeItem($this->input->post("password"))];
        }
        $this->db->where('usuarios_id', $this->input->post('usuarios_id'));
        $ingresar['data'] = $this->db->update('usuarios', $data);
        return $ingresar;
    }

    function editData($data){
        $this->db->where('usuarios_id', $data['usuarios_id'])->update('usuarios', $data);
    }

    function insertUserMediaSocial($data){
        $this->db->insert('usuarios', $data);
        $id = $this->db->insert_id();
        return $id;
    }

    function getUserSocialMedia($proveedorId, $email){
        return $this->db->where('socialmedia_providerid', $proveedorId)
                        ->where('email', $email)
                        ->get('usuarios')->result_array();
    }    
    function setUserSocialMedia($data){

    }


}