<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Corporativos_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->library('phpmailing');
    }

    public function getAll($filtros=array(),$limite=NULL){
        $this->db->select('*');
        if ($limite!=NULL) {
            $this->db->limit($limite[0],$limite[1]);
        }
        $corporativos = $this->db->get("bandeja_corporativos");
        return $corporativos;
    }
    public function save(){
        $data = array(
            'bandeja_corporativos_nombre' => $this->input->post('nombre_completo'),
            'bandeja_corporativos_correo' => $this->input->post('correo_electronico'),
            'bandeja_corporativos_numero' => $this->input->post('numero_celular'),
            'bandeja_corporativos_mensaje' => $this->input->post('mensaje'),
        );
        $guardado = $this->db->insert('bandeja_corporativos', $data);
        if ($guardado) {
            return 1;
        }
    }
}