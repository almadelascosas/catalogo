<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once(APPPATH.'core/classes/User.php');

class Permisos_model extends CI_Model {
    public function getAll(){
        return $this->db->get('tipos_accesos');
    }

    public function getActivos(){
        return $this->db->where('estado', 1)->get('tipos_accesos')->result_array();
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
