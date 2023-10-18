<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Municipios_model extends CI_Model {

    public function getAll(){
        return $this->db->get('municipios')->result_array();
    }

    public function getById($id){
        return $this->db->where('id_municipio', $id)->get('municipios')->result_array();
    }

    public function getAllByIdDpto($id){
        return $this->db->where('departamento_id', $id)->get('municipios')->result_array();
    }
}