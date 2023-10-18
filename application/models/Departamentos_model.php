<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departamentos_model extends CI_Model {

    public function getAll(){
        return $this->db->get('departamentos')->result_array();
    }

    public function getById($id){
        return $this->db->where('id_departamento', $id)->get('departamentos')->result_array();
    }
}