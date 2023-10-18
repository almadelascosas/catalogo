<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class General_model extends CI_Model {
    public function bannerHome(){
        $datos = array();

        $datos['banner_enlace'] = "";
        $datos['banner_imagen'] = "";
        $datos['banner_imagen_mobile_id'] = "";
        $datos['banner_imagen_titulo'] = "";

        $this->db->select("*");
        $this->db->join("medios","medios.medios_id=banner_home.banner_imagen","inner");
        $banner = $this->db->get("banner_home");
        foreach ($banner->result_array() as $key => $value) {
            $datos['banner_enlace'] = $value['banner_enlace'];
            $datos['banner_imagen'] = $value['banner_imagen'];
            $datos['banner_imagen_mobile'] = $value['banner_imagen_mobile'];
            $datos['banner_imagen_url'] = $value['medios_url'];
            $datos['banner_imagen_titulo'] = $value['medios_titulo'];
        }
        return $datos;
    }

    public function bannerCorporativos(){
        $datos = array();

        $datos['banner_imagen'] = "";
        $datos['banner_imagen_mobile_id'] = "";
        $datos['banner_imagen_mobile'] = "";
        $datos['banner_imagen_titulo'] = "";

        $this->db->select("medios.*, banner_corporativos.*");
        $this->db->join("medios","medios.medios_id = banner_corporativos.banner_imagen","inner");
        $banner = $this->db->get("banner_corporativos")->result_array();

        foreach ($banner as $key => $value) {
            $datos['banner_imagen'] = $value['banner_imagen'];
            $datos['banner_imagen_mobile'] = $value['banner_imagen_mobile'];
            $datos['banner_imagen_url'] = $value['medios_url'];
            $datos['banner_imagen_titulo'] = $value['medios_titulo'];
            $datos['banner_enlace'] = $value['banner_enlace'];
            $datos['banner_target'] = $value['banner_target'];
        }
        return $datos;
    }

    public function bannerSecond(){
        $this->db->select("*");
        $this->db->join("medios","medios.medios_id=b.banner_home_noticias_imagen","left");
        $res = $this->db->get("banner_home_noticias as b");
        return $res;
    }

    public function obtenerDepartamentos_new(){
        return $this->db->get('departamentos')->result_array();
    }
    public function obtenerMunicipios_new($filtros){
        if (count($filtros['where'])===2) $this->db->where($filtros['where'][0], $filtros['where'][1]);
        return $this->db->get('municipios')->result_array();
    }

    public function obtenerDepartamentos(){
        $datos = array();        
        $this->db->select('*');
        $datos = $this->db->get('departamentos');
        return $datos;
    }   

    public function obtenerMunicipios($filtros){
        if (count($filtros['where'])===2) $this->db->where($filtros['where'][0], $filtros['where'][1]);
        $data = $this->db->get('municipios')->result_array();
        return $data;
    }
    public function obtenerDepartamentoById($id){
        $dato = array();
        $dato = $this->db->where('id_departamento', $id)->get('departamentos')->result_array()[0];
        return $dato;
    }
    public function obtenerMunicipioById($id){
        $dato = array();
        $dato = $this->db->where('id_municipio', $id)->get('municipios')->result_array()[0];
        return $dato;
    }
    public function obtenerMunicipioByIdArray($ids){
        $dato = array();
        $dato = $this->db->where_in('id_municipio', $ids)->get('municipios')->result_array();
        return $dato;
    }
    public function getMyOrders(){
        $datos = array();
        $datos['data'] = array();
        $this->db->select("*");
        $this->db->join("alma_pedidos","alma_pedidos_detalle.pedidos_detalle_pedidos_id=alma_pedidos.pedidos_id","inner");
        $this->db->join("productos","alma_pedidos_detalle.pedidos_detalle_producto=productos.productos_id","inner");
        $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","inner");
        $this->db->where("alma_pedidos.pedidos_usuarios_id",$_SESSION['usuarios_id']);
        $this->db->order_by("alma_pedidos.pedidos_fecha_modificacion","DESC");
        //$this->db->group_by("alma_pedidos.pedidos_id");
        $pedidos_detalle = $this->db->get("alma_pedidos_detalle");
        
        foreach ($pedidos_detalle->result_array() as $key => $value) {
            
            $fecha = $value['pedidos_fecha_creacion'];
            $fecha = fecha_esp($fecha);
  
            $precio_total_seteado = "$ ".number_format($value['pedidos_precio_total'], 0, ',', '.');
            
            if($value['pedidos_estatus']==6){ 
                $estatus = "Rechazado"; 
              }elseif ($value['pedidos_estatus']==5) {
                  $estatus = "Rechazado"; 
              }elseif ($value['pedidos_estatus']==4) {
                  $estatus = "En Preparación"; 
              }elseif ($value['pedidos_estatus']==1) {
                  $estatus = "Esperando confirmación de pago"; 
              }elseif ($value['pedidos_estatus']=="1") {
                  $estatus = "Esperando confirmación de pago"; 
              }else {
                  switch ($value['pedidos_estatus']) {
                      case 'Enviado':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 'En preparación':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 'Esperando confirmación de pago':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 'Esperando confirmación de Pago':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 1:
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 1:
                          $estatus = "Esperando confirmación de Pago";
                          break;
                      case "1":
                          $estatus = "Esperando confirmación de Pago";
                          break;
                      case 'Cancelado':
                              $estatus = $value['pedidos_estatus'];
                          break;
                      default:
                          $estatus = $value['pedidos_estatus'];
                          break;
                  }
              }
  
            $titulo_producto = $value['productos_titulo'];
            $agg = 1;
            for ($i=0; $i < count($datos['data']); $i++) {
                if ($datos['data'][$i]['pedidos_id']==$value['pedidos_id']) {
                    $agg = 0;
                    $datos['data'][$i]['pedidos_mas_productos'] = $datos['data'][$i]['pedidos_mas_productos']+1;
                }
            }
            $imagen = $value['medios_url'];
  
            if ($agg == 1) {
                array_push($datos['data'], array(
                    "pedidos_id" => $value['pedidos_id'],
                    "pedidos_fecha" => $fecha,
                    "pedidos_imagen_producto" => $imagen,
                    "pedidos_titulo_producto" => $titulo_producto,
                    "pedidos_mas_productos" => 0,
                    "pedidos_precio_total" => $value['pedidos_precio_total'],
                    "pedidos_precio_total_seteado" => $precio_total_seteado,
                    "pedidos_estatus" => $estatus,
                ));
            }
        }
        return $datos;
    }

    public function getOrderDetails($id=0,$correo=""){
        $datos['data'] = array(); 
        if ($id!=0) {
            $this->db->select("*");
            $this->db->join("alma_pedidos","alma_pedidos.pedidos_id=alma_pedidos_detalle.pedidos_detalle_pedidos_id","inner");
            $this->db->join("departamentos","alma_pedidos.pedidos_departamento=departamentos.id_departamento","inner");
            $this->db->join("municipios","alma_pedidos.pedidos_municipio=municipios.id_municipio","inner");
            $this->db->join("productos","productos.productos_id=alma_pedidos_detalle.pedidos_detalle_producto","inner");
            $this->db->join("medios","productos.productos_imagen_destacada=medios.medios_id","left");
            $this->db->where("alma_pedidos.pedidos_id", $id);
            if ($correo!="")  $this->db->where("alma_pedidos.pedidos_correo",$correo);
            $pedido = $this->db->get("alma_pedidos_detalle");
            
            $this->db->where("pedidos_id", $id);
            $estatus_cons = $this->db->get("pedidos_estatus_productos");
            
            if ($pedido->num_rows() > 0) {
                $datos['data']['productos'] = array();
            }
            
            foreach ($pedido->result_array() as $key => $value) {
                $estatus = ""; 
                $fecha = $value['pedidos_fecha_creacion'];
                $fecha = fecha_esp($fecha);
                if($value['pedidos_estatus']==6){ 
                    $estatus = "Rechazado"; 
                }elseif ($value['pedidos_estatus']==5) {
                    $estatus = "Rechazado"; 
                }elseif ($value['pedidos_estatus']==4) {
                    $estatus = "En Preparación"; 
                }elseif ($value['pedidos_estatus']==1) {
                    $estatus = "Esperando confirmación de pago"; 
                }elseif ($value['pedidos_estatus']=="1") {
                    $estatus = "Esperando confirmación de pago"; 
                }else {
                    switch ($value['pedidos_estatus']) {
                        case 'Enviado':
                            $estatus = $value['pedidos_estatus'];
                            break;
                        case 'En preparación':
                            $estatus = $value['pedidos_estatus'];
                            break;
                        case 'Esperando confirmación de pago':
                            $estatus = $value['pedidos_estatus'];
                            break;
                        case 'Esperando confirmación de Pago':
                            $estatus = $value['pedidos_estatus'];
                            break;
                        case 1:
                            $estatus = $value['pedidos_estatus'];
                            break;
                        case 1:
                            $estatus = "Esperando confirmación de Pago";
                            break;
                        case "1":
                            $estatus = "Esperando confirmación de Pago";
                            break;
                        case 'Cancelado':
                                $estatus = $value['pedidos_estatus'];
                            break;
                        default:
                            $estatus = $value['pedidos_estatus'];
                            break;
                    }
                }
                
                $estatus_productos = array(
                    'fecha_confirmado'=>'',
                    'fecha_preparacion'=>'',
                    'fecha_reembolsado'=>'',
                    'fecha_enviado'=>'',
                    'fecha_entregado'=>'',
                    'nro_guia'=>'',
                    'empresa'=>'',
                );
                foreach ($estatus_cons->result_array() as $key2 => $value2) {
                    if ($value2['productos_id']==$value['productos_id']) {
                        $fecha_confirmado = "";
                        $fecha_preparacion = "";
                        $fecha_reembolsado = "";
                        $fecha_enviado = "";
                        $fecha_entregado = "";
                        
                        if ($value2["estatus"]=="Confirmado") {
                            $fecha_confirmado = $value2['fecha_modificacion'];
                            $estatus_productos['fecha_confirmado']=$fecha_confirmado; 
                            $estatus_productos['nro_guia']=$value2['nro_guia'];
                            $estatus_productos['empresa']=$value2['nombre_empresa'];
                        }
                        if ($value2["estatus"]=="En preparación") {
                            $fecha_preparacion = $value2['fecha_modificacion'];
                            $estatus_productos['fecha_preparacion']=$fecha_preparacion; 
                            $estatus_productos['nro_guia']=$value2['nro_guia'];
                            $estatus_productos['empresa']=$value2['nombre_empresa'];
                        }
                        if ($value2["estatus"]=="Reembolsado" || $value2["estatus"]=="Rechazado" || $value2["estatus"]=="Cancelado") {
                            $fecha_reembolsado = $value2['fecha_modificacion'];
                            $estatus_productos['fecha_reembolsado']=$fecha_reembolsado; 
                            $estatus_productos['nro_guia']=$value2['nro_guia'];
                            $estatus_productos['empresa']=$value2['nombre_empresa'];
                        }
                        if ($value2["estatus"]=="Enviado") {
                            $fecha_enviado = $value2['fecha_modificacion'];
                            $estatus_productos['fecha_enviado']=$fecha_enviado; 
                            $estatus_productos['nro_guia']=$value2['nro_guia'];
                            $estatus_productos['empresa']=$value2['nombre_empresa'];
                        }
                        if ($value2["estatus"]=="Entregado") {
                            $fecha_entregado = $value2['fecha_modificacion'];
                            $estatus_productos['fecha_entregado']=$fecha_entregado; 
                            $estatus_productos['nro_guia']=$value2['nro_guia'];
                            $estatus_productos['empresa']=$value2['nombre_empresa'];
                        }
                    }
                }
                $precio_seteado = "$ ".number_format($value['pedidos_detalle_producto_precio'], 0, ',', '.');
                $precio_total_seteado = "$ ".number_format($value['pedidos_precio_total'], 0, ',', '.');
                $imagen = image($value['medios_url']);
                
                $datos['data']['pedidos_id'] = $id;
                $datos['data']['pedidos_nombre_cliente'] = $value['pedidos_nombre'];
                
                $datos['data']['pedidos_estatus'] = $estatus; 
                $datos['data']['pedidos_fecha'] = $fecha;
                $datos['data']['pedidos_precio_total'] = $precio_total_seteado;
                array_push($datos['data']['productos'],array(
                    'productos_id' => $value['productos_id'],
                    'productos_imagen' => $imagen,
                    'productos_titulo' => $value['productos_titulo'],
                    'productos_cantidad' => $value['pedidos_detalle_producto_cantidad'],
                    'productos_precio' => $value['pedidos_detalle_producto_precio'],
                    'productos_precio_seteado' => $precio_seteado,
                    'productos_estatus' => $estatus_productos,
                ));
                
                $datos['data']['pedidos_direccion'] = $value['pedidos_direccion'];
                $datos['data']['pedidos_departamento'] = $value['departamento'];
                $datos['data']['pedidos_municipio'] = $value['municipio'];

            }

            $datos['mensaje'] = "Consulta realizada con Éxito"; 
            $datos['error'] = 0;
        }else{
            $datos['mensaje'] = "Debe enviar un ID de pedido válido."; 
            $datos['error'] = 1;
        }
        return $datos;
    }

}
