<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendor_model extends CI_Model {
    
    public function obtenerCategoriasVendor(){
        $return = array();
        $this->db->select('usuarios_categorias_id');
        $vendor = $this->db->get('usuarios');
        $return['error'] = 0;
        $return['first_cat'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();
        $categoriasArr = array();
        foreach ($vendor->result_array() as $key => $value) {
            if (!in_array($value['usuarios_categorias_id'], $categoriasArr)) {
                array_push($categoriasArr, $value['usuarios_categorias_id']);
            }
        }
        $this->db->select('*');
        $this->db->where_in('categorias_id',$categoriasArr);
        $categorias = $this->db->get("categorias");
        $cont = 0;
        foreach ($categorias->result_array() as $key => $value) {
            $cont++;
            if ($cont==1) {
                $return['first_cat'] = $value['categorias_id'];
            }
            array_push($return["data"], array(
                'categorias_id' => $value['categorias_id'],
                'categorias_nombre' => $value['categorias_nombre']
            ));
        }
        return $return;
    }
        
    public function obtenerVendedorPorCategoria($categorias_id=0){
        if (isset($categorias_id) && $categorias_id!=0) {
            $return = array();
            $return['error'] = 0;
            $return['mensaje'] = "Consulta realizada con exito";
            $return['data'] = array();
            
            $this->db->select('*');
            $this->db->join("medios","medios.medios_id=usuarios.image_profile","left");
            $this->db->where('usuarios_categorias_id',$categorias_id);
            $this->db->limit(4);
            $vendor = $this->db->get("usuarios");
            foreach ($vendor->result_array() as $key => $value) {
                $imagen = '';
                if ($value['medios_url']==NULL || $value['medios_url']=="") {
                    $imagen = base_url()."assets/img/Not-Image.png";
                }else{
                    $imagen = base_url().$value['medios_url'];
                }
                array_push($return["data"], array(
                    'usuarios_id' => $value['usuarios_id'],
                    'nombre_vendedor' => $value['name'],
                    'apellido_vendedor' => $value['lastname'],
                    'imagen_perfil' => $imagen
                ));
            }
        }else{
            $return = array();
            $return['error'] = 1;
            $return['mensaje'] = "Debes enviar el ID de la categoria";
            $return['data'] = array();

        }
        return $return;    
    }
}
?>
