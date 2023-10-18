<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package     CodeIgniter
 * @author      Yeisson Patarroyo Guapacho <yepagu@gmail.com>
 * @copyright   Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license     http://codeigniter.com/user_guide/license.html
 * @link        http://codeigniter.com
 * @since       Version 1.0
 * @date        09/02/2023
 */

class Address_model extends CI_Model {

    function tableName() {
        return 'direccion_usuario';
    }
    
    public function getAddresByUser($idUser){
        $this->db->select($this->tableName().'.*, departamentos.departamento, municipios.municipio')
                 ->from($this->tableName())
                 ->join('departamentos', 'departamentos.id_departamento = '.$this->tableName().'.id_dpto')
                 ->join('municipios', 'municipios.id_municipio = '.$this->tableName().'.id_muni')
                 ->where($this->tableName().'.id_usuario', $idUser);
        
        return $this->db->get()->result_array();
    }

    public function getAddresById($id){
        $this->db->select($this->tableName().'.*, departamentos.departamento, municipios.municipio')
                 ->from($this->tableName())                 
                 ->join('departamentos', 'departamentos.id_departamento = '.$this->tableName().'.id_dpto')
                 ->join('municipios', 'municipios.id_municipio = '.$this->tableName().'.id_muni')
                 ->where($this->tableName().'.id_dir', $id);
        return $this->db->get()->result_array();
    }

    public function addAddress($data){
        return $this->db->insert($this->tableName(), $data);
    }

    public function updateAddressMultiple($data, $filter){
        return $this->db->where($filter[0], $filter[1])->update($this->tableName(), $data);
    }

    public function updateAddress($data, $id){
        return $this->db->where('id_dir', $id)->update($this->tableName(), $data);
    }

    public function deleteAddress($filter){
        foreach($filter as $key => $data) $this->db->where($data[0], $data[1]);
        return $this->db->delete($this->tableName());
    }
}