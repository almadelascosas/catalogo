<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Catalogo_model extends CI_Model {

    public function __construct(){
    }

    public function getTable(){ return 'catalogo'; }

    public function ultimos(){
        return $this->db->order_by('catalogo_id', 'desc')
                        ->limit(30)
                        ->get($this->getTable())
                        ->result_array();
    }

    public function getOrders($parametros = array(),$page = 1,$limite=array()){
        if (isset($parametros['tipo_consulta']) && $parametros['tipo_consulta']=="pedidos_atrasados") {
            $pedidos_ids = array(0);
            $fecha_actual = date("Y-m-d H:s:i");
            
            $this->db->select("alma_pedidos_detalle.*,alma_pedidos.*,productos.productos_entrega_local,productos.*");
            $this->db->join("alma_pedidos","alma_pedidos.pedidos_id=alma_pedidos_detalle.pedidos_detalle_pedidos_id","inner");
            $this->db->join("productos","productos.productos_id=alma_pedidos_detalle.pedidos_detalle_producto","inner");
            if($_SESSION['tipo_accesos']!=0 && $_SESSION['tipo_accesos']!=1) $this->db->where("productos.productos_vendedor",$_SESSION['usuarios_id']);
            $this->db->where("alma_pedidos.pedidos_estatus","4");
            $this->db->or_where("alma_pedidos.pedidos_estatus","En preparación");
            $pedidos_detalle = $this->db->get("alma_pedidos_detalle");

            foreach ($pedidos_detalle->result_array() as $key => $value) {
                $fecha = $value['pedidos_fecha_creacion'];
                $add_nacional = 0;
                $productos_entrega = array(1, 1, 3, 4, 5, 6, 8);
                if ($value['productos_envio_nacional']==0) {
                    $add_nacional = 0;
                    $productos_entrega = array(1, 1, 3, 4, 5, 6, 8);
                }else{
                    $ubicaciones = explode("/",$value['productos_ubicaciones_envio']);
                    $municipios = array();
                    for ($i=0; $i < count($ubicaciones); $i++) {
                        $ub = explode(",", $ubicaciones[$i]);
                        if (isset($ub[1])) {
                            array_push($municipios, $ub[1]);
                        }
                    }
                    if (in_array($value['pedidos_municipio'],$municipios)) {
                        $add_nacional = 0;
                        $productos_entrega = array(1, 1, 3, 4, 5, 6, 8);
                    }else{
                        $add_nacional = 1;
                        $productos_entrega = array(3, 3, 4, 5, 6, 8);
                    }
                }
                if ($add_nacional==1) {
                    $fecha = strtotime($fecha."+ ".$productos_entrega[$value['productos_entrega_nacional']]." days");
                    $fecha = date("Y-m-d H:s:i", $fecha);
                }else{
                    $fecha = strtotime($fecha."+ ".$productos_entrega[$value['productos_entrega_local']]." days");
                    $fecha = date("Y-m-d H:s:i", $fecha);
                }
                if($fecha < $fecha_actual){
                    array_push($pedidos_ids,$value['pedidos_id']);
                }
            }

            $this->db->select("*");
            $this->db->join("alma_pedidos","alma_pedidos.pedidos_id=alma_pedidos_detalle.pedidos_detalle_pedidos_id","inner");
            $this->db->join("municipios","municipios.id_municipio=alma_pedidos.pedidos_municipio","left");
            $this->db->join("productos","productos.productos_id=alma_pedidos_detalle.pedidos_detalle_producto","inner");
            $this->db->join("medios","productos.productos_imagen_destacada=medios.medios_id","left");
            $this->db->where_in("alma_pedidos.pedidos_id",$pedidos_ids);
            if($_SESSION['tipo_accesos']!=0 && $_SESSION['tipo_accesos']!=1){
                $this->db->where("productos.productos_vendedor",$_SESSION['usuarios_id']);
            }
            $this->db->group_start();
            $this->db->where("alma_pedidos.pedidos_estatus","4");
            $this->db->or_where("alma_pedidos.pedidos_estatus","En preparación");
            $this->db->group_end();
            $this->db->group_by("alma_pedidos.pedidos_id");
            if ($limite==NULL) {
                $limit = 24;
                $page = $page-1;
                $limit = $limit*$page;
                $this->db->limit(24,$limit);
            }else{
                $this->db->limit($limite[0],$limite[1]);
            }
            $pedidos_detalle = $this->db->get("alma_pedidos_detalle");
            return $pedidos_detalle;
            
        }
    }

    /* New  */
    public function getAllforVendorNew($filtros=array(),$page=1,$limite=NULL){
        
        $pedidos_ids = array(0);
        $productos = array(0);
        
        $this->db->select('alma_pedidos_detalle.*');
        $this->db->where('alma_pedidos_detalle.pedidos_detalle_vendedor',$_SESSION['usuarios_id']);
        $cons_prod = $this->db->get('alma_pedidos_detalle');

        foreach ($cons_prod->result_array() as $key => $value) {
            array_push($pedidos_ids, $value['pedidos_detalle_pedidos_id']);
            array_push($productos, $value['pedidos_detalle_producto']);
        }

        $this->db->select('alma_pedidos.*,
        (SELECT departamento FROM departamentos WHERE departamentos.id_departamento=alma_pedidos.pedidos_departamento_envio) as departamento_envio,
        (SELECT municipio FROM municipios WHERE municipios.id_municipio=alma_pedidos.pedidos_municipio_envio) as municipio_envio');
        $this->db->join('departamentos','departamentos.id_departamento=alma_pedidos.pedidos_departamento','left');
        $this->db->join('municipios','municipios.id_municipio=alma_pedidos.pedidos_municipio','left');
        
        if ($limite==NULL) {
            $limit = 24;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(24,$limit);
        }else{
            $this->db->limit($limite[0],$limite[1]);
        }

        if ($pedidos_ids!=array()) {
            $this->db->group_start();
            $this->db->where_in("pedidos_id",$pedidos_ids);
            $this->db->group_end();
        }

        
        if (isset($_REQUEST['search']) && $_REQUEST['search']!="") {
            $this->db->group_start();
            $this->db->like("pedidos_nombre",$_REQUEST['search']);
            $this->db->group_end();
        }

        if (isset($filtros['order']) && $filtros['order']!=NULL) {
            $this->db->order_by($filtros['order'][0],$filtros['order'][1]);
        }else{
            $this->db->order_by('pedidos_fecha_creacion','DESC');
        }

        $result = $this->db->get('alma_pedidos');
        $pedidos = array();
        $count = 0;
        $pedidos['pedidos'] = array();
        foreach ($result->result_array() as $key => $value) {
            $count++;
            $pedidos_add = array();
            foreach ($value as $key2 => $value2) {
                $pedidos_add[$key2] = $value2;
            }
            array_push($pedidos['pedidos'],$pedidos_add);
        }
        if ($productos!=array()) {
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where_in('productos_id', $productos);
            $pedidos['productos'] = $this->db->get('productos');
        }else{
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where('productos_id', 0);
            $pedidos['productos'] = $this->db->get('productos');
        }
        
        $this->db->select('alma_pedidos_detalle.*');
        $this->db->where('alma_pedidos_detalle.pedidos_detalle_vendedor',$_SESSION['usuarios_id']);
        $pedidos['pedidos_productos'] = $this->db->get('alma_pedidos_detalle');

        return $pedidos;

    }
    public function getAllNew($filtros=array(),$page=1,$limite=NULL){

        if (isset($_REQUEST['search']) && $_REQUEST['search']!="" && (isset($_REQUEST['search-type']) &&  $_REQUEST['search-type']==2)) {
            $this->db->select('alma_pedidos_detalle.pedidos_detalle_pedidos_id,alma_pedidos_detalle.pedidos_detalle_producto,usuarios.name');
            $this->db->join('productos','productos.productos_id=alma_pedidos_detalle.pedidos_detalle_producto','inner');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','inner');
            $this->db->like('usuarios.name',$_REQUEST['search']);
            $pedidos_first = $this->db->get('alma_pedidos_detalle');

            $pedidos_ids = array();
            
            foreach ($pedidos_first->result_array() as $key => $value) {
                array_push($pedidos_ids,$value['pedidos_detalle_pedidos_id']);
            }
        }

        $this->db->select('alma_pedidos.*,departamentos.*,municipios.*,
        (SELECT departamento FROM departamentos WHERE departamentos.id_departamento=alma_pedidos.pedidos_departamento_envio) as departamento_envio,
        (SELECT municipio FROM municipios WHERE municipios.id_municipio=alma_pedidos.pedidos_municipio_envio) as municipio_envio');
        $this->db->join('departamentos','departamentos.id_departamento=alma_pedidos.pedidos_departamento','left');
        $this->db->join('municipios','municipios.id_municipio=alma_pedidos.pedidos_municipio','left');
        if (isset($filtros['where']) && $filtros['where']!=NULL) {
            $this->db->where($filtros['where'][0], $filtros['where'][1]);
        }
        if (isset($filtros['where_arr']) && $filtros['where_arr']!=NULL)  $this->db->where("pedidos_fecha_creacion BETWEEN '".$filtros['where_arr']['fini']."' AND '".$filtros['where_arr']['ffin']."'");
        if (isset($filtros['order']) && $filtros['order']!=NULL) {
            $this->db->order_by($filtros['order'][0],$filtros['order'][1]);
        }else{
            $this->db->order_by('pedidos_fecha_creacion','DESC');
        }
        if ($limite==NULL) {
            $limit = 24;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(24,$limit);
        }else{
            $this->db->limit($limite[0],$limite[1]);
        }
        if (isset($_REQUEST['search']) && $_REQUEST['search']!="") {
            if (isset($_REQUEST['search-type'])) {
                if ($_REQUEST['search-type']==0) {
                    $this->db->group_start();
                    $this->db->where("pedidos_id",$_REQUEST['search']);
                    $this->db->group_end();
                }
                if ($_REQUEST['search-type']==1) {
                    $this->db->group_start();
                    $this->db->like("pedidos_nombre",$_REQUEST['search']);
                    $this->db->group_end();
                }
                if ($_REQUEST['search-type']==2) {
                    $this->db->group_start();
                    $this->db->where_in("pedidos_id",$pedidos_ids);
                    $this->db->group_end();
                }
            }
        }
        $result = $this->db->get('alma_pedidos');

        $pedidos = array();
        $productos = array();
        $pedidos_ids_2 = array();
        $count = 0;
        $pedidos['pedidos'] = array();
        foreach ($result->result_array() as $key => $value) {
            $count++;
            $pedidos_add = array();
            foreach ($value as $key2 => $value2) {
                $pedidos_add[$key2] = $value2;
            }
            array_push($pedidos['pedidos'],$pedidos_add);
            array_push($pedidos_ids_2,$value['pedidos_id']);
        }
        $this->db->select('*');
        if ($pedidos_ids_2!=array()) {
            $this->db->where_in('pedidos_detalle_pedidos_id',$pedidos_ids_2);
        }
        $pedidos['pedidos_productos'] = $this->db->get('alma_pedidos_detalle');

        foreach ($pedidos['pedidos_productos']->result_array() as $key => $value) {
            array_push($productos,$value['pedidos_detalle_producto']);
        }
        
        if ($productos!=array()) {
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where_in('productos_id', $productos);
            $pedidos['productos'] = $this->db->get('productos');
        }else{
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where('productos_id', 0);
            $pedidos['productos'] = $this->db->get('productos');
        }

        return $pedidos;
    }
    /* /New  */

    public function getAllforVendor($filtros=array(),$page=1,$limite=NULL){
        $this->db->select('pedidos.pedidos_productos');
        $pedidos_first = $this->db->get('pedidos');

        $productos_cs = array();
        
        foreach ($pedidos_first->result_array() as $key => $value) {
            $products = explode(",",$value['pedidos_productos']);
            for ($i=0; $i < count($products); $i++) {
                if (!in_array($products[$i], $productos_cs)) {
                    array_push($productos_cs,$products[$i]);
                }
            }
        }

        $this->db->select('productos_id');
        $this->db->where_in('productos_id',$productos_cs);
        $this->db->where('productos_vendedor',$_SESSION['usuarios_id']);
        $cons_prod = $this->db->get('productos');
        
        $like_productos = "";

        foreach ($cons_prod->result_array() as $key => $value) {
            if ($like_productos=="") {
                $like_productos = "
                (pedidos_productos = '".$value['productos_id']."' 
                OR pedidos_productos LIKE ('%,".$value['productos_id']."') 
                OR pedidos_productos LIKE ('".$value['productos_id'].",%') 
                OR pedidos_productos LIKE ('%,".$value['productos_id'].",%')) ";
            }else{
                $like_productos .= "
                OR (pedidos_productos = '".$value['productos_id']."' 
                OR pedidos_productos LIKE ('%,".$value['productos_id']."') 
                OR pedidos_productos LIKE ('".$value['productos_id'].",%') 
                OR pedidos_productos LIKE ('%,".$value['productos_id'].",%'))
                ";
            }
        }

        $this->db->select('pedidos.*,
        (SELECT departamento FROM departamentos WHERE departamentos.id_departamento=pedidos.pedidos_departamento_envio) as departamento_envio,
        (SELECT municipio FROM municipios WHERE municipios.id_municipio=pedidos.pedidos_localidad_envio) as municipio_envio');
        $this->db->join('departamentos','departamentos.id_departamento=pedidos.pedidos_departamento','left');
        $this->db->join('municipios','municipios.id_municipio=pedidos.pedidos_localidad','left');
        if (isset($filtros['where']) && $filtros['where']!=NULL) {
            $this->db->where($filtros['where'][0],$filtros['where'][1]);
        }
        if (isset($filtros['order']) && $filtros['order']!=NULL) {
            $this->db->order_by($filtros['order'][0],$filtros['order'][1]);
        }else{
            $this->db->order_by('pedidos_fecha','DESC');
        }
        if ($limite==NULL) {
            $limit = 24;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(24,$limit);
        }else{
            $this->db->limit($limite[0],$limite[1]);
        }
        if ($like_productos!="") {
            $this->db->group_start();
            $this->db->where($like_productos);
            $this->db->group_end();
        }
        
        if (isset($_REQUEST['search']) && $_REQUEST['search']!="") {
            $this->db->group_start();
            $this->db->like("pedidos_nombre",$_REQUEST['search']);
            $this->db->group_end();
        }

        $result = $this->db->get('pedidos');
        $pedidos = array();
        $productos = array();
        $count = 0;
        $pedidos['pedidos'] = array();
        foreach ($result->result_array() as $key => $value) {
            $count++;
            $pedidos_add = array();
            foreach ($value as $key2 => $value2) {
                $pedidos_add[$key2] = $value2;
            }
            array_push($pedidos['pedidos'],$pedidos_add);
            $productos_add = explode(",",$value["pedidos_productos"]);
            for ($i=0; $i < count($productos_add); $i++) {
                if (!in_array($productos_add[$i], $productos)) {
                    array_push($productos, $productos_add[$i]);
                }
            }
        }
        if ($productos!=array()) {
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where_in('productos_id', $productos);
            $pedidos['productos'] = $this->db->get('productos');
        }else{
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where('productos_id', 0);
            $pedidos['productos'] = $this->db->get('productos');
        }
        
        $this->db->select('*');
        $pedidos['pedidos_productos'] = $this->db->get('pedidos_productos');

        return $pedidos;

    }
    public function getAll($filtros=array(),$page=1,$limite=NULL){

        if (isset($_REQUEST['search']) && $_REQUEST['search']!="" && (isset($_REQUEST['search-type']) &&  $_REQUEST['search-type']==2)) {
            $this->db->select('pedidos.pedidos_productos');
            $pedidos_first = $this->db->get('pedidos');

            $productos_cs = array();
            
            foreach ($pedidos_first->result_array() as $key => $value) {
                $products = explode(",",$value['pedidos_productos']);
                for ($i=0; $i < count($products); $i++) {
                    if (!in_array($products[$i], $productos_cs)) {
                        array_push($productos_cs,$products[$i]);
                    }
                }
            }

            $this->db->select('productos.productos_id,usuarios.name');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','inner');
            $this->db->where_in('productos.productos_id',$productos_cs);
            $this->db->like('usuarios.name',$_REQUEST['search']);
            $cons_prod = $this->db->get('productos');
            
            $like_productos = "";

            foreach ($cons_prod->result_array() as $key => $value) {
                if ($like_productos=="") {
                    $like_productos = "
                    (pedidos_productos = '".$value['productos_id']."' 
                    OR pedidos_productos LIKE ('%,".$value['productos_id']."') 
                    OR pedidos_productos LIKE ('".$value['productos_id'].",%') 
                    OR pedidos_productos LIKE ('%,".$value['productos_id'].",%')) ";
                }else{
                    $like_productos .= "
                    OR (pedidos_productos = '".$value['productos_id']."' 
                    OR pedidos_productos LIKE ('%,".$value['productos_id']."') 
                    OR pedidos_productos LIKE ('".$value['productos_id'].",%') 
                    OR pedidos_productos LIKE ('%,".$value['productos_id'].",%'))
                    ";
                }
            }
        }

        $this->db->select('pedidos.*,departamentos.*,municipios.*,
        (SELECT departamento FROM departamentos WHERE departamentos.id_departamento=pedidos.pedidos_departamento_envio) as departamento_envio,
        (SELECT municipio FROM municipios WHERE municipios.id_municipio=pedidos.pedidos_localidad_envio) as municipio_envio');
        $this->db->join('departamentos','departamentos.id_departamento=pedidos.pedidos_departamento','left');
        $this->db->join('municipios','municipios.id_municipio=pedidos.pedidos_localidad','left');
        if (isset($filtros['where']) && $filtros['where']!=NULL) {
            $this->db->where($filtros['where'][0],$filtros['where'][1]);
        }
        if (isset($filtros['order']) && $filtros['order']!=NULL) {
            $this->db->order_by($filtros['order'][0],$filtros['order'][1]);
        }else{
            $this->db->order_by('pedidos_fecha','DESC');
        }
        if ($limite==NULL) {
            $limit = 24;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(24,$limit);
        }else{
            $this->db->limit($limite[0],$limite[1]);
        }
        if (isset($_REQUEST['search']) && $_REQUEST['search']!="") {
            if (isset($_REQUEST['search-type'])) {
                if ($_REQUEST['search-type']==0) {
                    $this->db->group_start();
                    $this->db->where("pedidos_id",$_REQUEST['search']);
                    $this->db->group_end();
                }
                if ($_REQUEST['search-type']==1) {
                    $this->db->group_start();
                    $this->db->like("pedidos_nombre",$_REQUEST['search']);
                    $this->db->group_end();
                }
                if ($_REQUEST['search-type']==2) {
                    $like_productos = str_replace("\n","",$like_productos);
                    $like_productos = str_replace("\\","",$like_productos);
                    //$this->db->group_start();
                    $this->db->where($like_productos);
                    //$this->db->group_end();
                }
            }
        }
        $result = $this->db->get('pedidos');
        $pedidos = array();
        $productos = array();
        $count = 0;
        $pedidos['pedidos'] = array();
        foreach ($result->result_array() as $key => $value) {
            $count++;
            $pedidos_add = array();
            foreach ($value as $key2 => $value2) {
                $pedidos_add[$key2] = $value2;
            }
            array_push($pedidos['pedidos'],$pedidos_add);
            $productos_add = explode(",",$value["pedidos_productos"]);
            for ($i=0; $i < count($productos_add); $i++) {
                if (!in_array($productos_add[$i], $productos)) {
                    array_push($productos, $productos_add[$i]);
                }
            }
        }
        
        if ($productos!=array()) {
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where_in('productos_id', $productos);
            $pedidos['productos'] = $this->db->get('productos');
        }else{
            $this->db->select('*');
            $this->db->join('medios','medios.medios_id=productos.productos_imagen_destacada','left');
            $this->db->join('usuarios','usuarios.usuarios_id=productos.productos_vendedor','left');
            $this->db->where('productos_id', 0);
            $pedidos['productos'] = $this->db->get('productos');
        }

        $this->db->select('*');
        $pedidos['pedidos_productos'] = $this->db->get('pedidos_productos');

        return $pedidos;
    }
    
    public function generarPreOrdenNew(){
        
        $query = $this->db->query('SHOW COLUMNS FROM alma_pedidos');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }
        $data += ["pedidos_fecha_creacion"=>date('Y-m-d H:i:s')];        
        $data += ["pedidos_estatus" => 1];
        $ingresar['data'] = $this->db->insert('alma_pedidos', $data);
        $ultimoId = $this->db->insert_id();
        $pedido = array();
        foreach ($_POST as $key => $value) $pedido[$key] = $value;
        $pedido["pedidos_id"] = $ultimoId;        
        $pedidos_productos_agg = array();
        foreach ($_SESSION['cart'] as $key => $value) {
            array_push($pedidos_productos_agg, array(
                "pedidos_detalle_pedidos_id" => $pedido["pedidos_id"],
                "pedidos_detalle_producto" => $value['productos_id'],
                "pedidos_detalle_producto_cantidad" => $value['productos_cantidad'],
                "pedidos_detalle_producto_addons" => $value['productos_addons'],
                "pedidos_detalle_producto_precio" => $value['productos_precio'],
                "pedidos_detalle_producto_envio_local" => $value['productos_envio_local'],
                "pedidos_detalle_productos_valor_envio_local" => $value['productos_valor_envio_local'],
                "pedidos_detalle_productos_ubicaciones_envio" => $value['productos_ubicaciones_envio'],
                "pedidos_detalle_productos_envio_nacional" => $value['productos_envio_nacional'],
                "pedidos_detalle_productos_valor_envio_nacional" => $value['productos_valor_envio_nacional'],
                "pedidos_detalle_vendedor" => $value['productos_vendedor'],
                "pedidos_detalle_estatus" => 1,
                'pedidos_detalle_fechaprogramada' => $value['productos_fecha_programada']
            ));            
        }
        $this->db->insert_batch('alma_pedidos_detalle', $pedidos_productos_agg);        
        $_SESSION['cart'] = [];

        return $pedido;
    }
    
    public function generarPreOrden(){
        $query = $this->db->query('SHOW COLUMNS FROM pedidos');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
          $field = $value["Field"];
          if ($value["Field"]!="pedidos_productos" && $value['Field']!="pedidos_productos_cantidad" && $this->input->post($value["Field"])!=NULL) {
            $data += [$value["Field"]=>$this->input->post($value["Field"])];
          }
        }

        $pedidos_productos = "";
        $pedidos_productos_cantidad = "";
        for ($i=0; $i < count($_POST['pedidos_productos']); $i++) {
            if ($i==0) {
                $pedidos_productos.=$_POST['pedidos_productos'][$i];
                $pedidos_productos_cantidad.=$_POST['pedidos_productos_cantidad'][$i];
            }else{
                $pedidos_productos.=",".$_POST['pedidos_productos'][$i];
                $pedidos_productos_cantidad.=",".$_POST['pedidos_productos_cantidad'][$i];
            }
        }
        $data += ["pedidos_productos"=>$pedidos_productos];
        $data += ["pedidos_productos_cantidad"=>$pedidos_productos_cantidad];
        $data += ["pedidos_estatus"=>"Esperando confirmación de Pago"];

        $data += ["pedidos_productos"=>$pedidos_productos];

        $ingresar['data'] = $this->db->insert('pedidos', $data);

        $ultimoId = $this->db->insert_id();

        $pedido = array();

        foreach ($_POST as $key => $value) {
            $pedido[$key] = $value;
        }

        $pedido["pedidos_id"] = $ultimoId;
        
        $pedidos_productos = array();
        
        $pedidos_productos_agg = array();

        foreach ($_SESSION['cart'] as $key => $value) {

            array_push($pedidos_productos_agg, array(
                "pedidos_productos_producto_id" => $value['producto_id'],
                "pedidos_productos_cantidad" => $value['cantidad'],
                "pedidos_productos_precio" => $value['precio'],
                "pedidos_productos_addons" => $value['addons'],
                "pedidos_productos_pedido_id" => $pedido["pedidos_id"]
            ));
            
        }

        $this->db->insert_batch('pedidos_productos', $pedidos_productos_agg);
        
        $_SESSION['cart'] = array();

        $this->mailPedidoRecibido($this->single($ultimoId));
        
        return $pedido;

    }

    public function single($id){
        $ids_productos = array();
        $query = $this->db->query('SHOW COLUMNS FROM pedidos');
        $valores = $query->result_array();
        $this->db->select('*,
        (SELECT departamento FROM departamentos WHERE departamentos.id_departamento=pedidos.pedidos_departamento_envio) as departamento_envio,
        (SELECT municipio FROM municipios WHERE municipios.id_municipio=pedidos.pedidos_localidad_envio) as municipio_envio')
        ->where("pedidos_id",$id);
        $this->db->join('departamentos','departamentos.id_departamento=pedidos.pedidos_departamento','left');
        $this->db->join('municipios','municipios.id_municipio=pedidos.pedidos_localidad','left');
        $query = $this->db->get('pedidos');
        $data = array();
        $data['pedido'] = array();
        $data['productos'] = array(0);
        foreach ($query->result_array() as $key2 => $value2) {
            foreach ($valores as $key => $value) {
                $data['pedido'] += [$value["Field"]=>$value2[$value["Field"]]];
            }
            $data['pedido'] += ["municipio"=>$value2['municipio']];
            $data['pedido'] += ["departamento"=>$value2['departamento']];
            $data['pedido'] += ["municipio_envio"=>$value2['municipio_envio']];
            $data['pedido'] += ["departamento_envio"=>$value2['departamento_envio']];
            $ids_productos = explode(",",$value2['pedidos_productos']);
        }
        $this->db->select('*')->join("medios","medios.medios_id=productos.productos_imagen_destacada","left")->where_in("productos_id",$ids_productos);
        $data['productos'] = $this->db->get('productos');

        $this->db->select('*')->where("pedidos_id",$id);
        $data['estatus'] = $this->db->get('pedidos_estatus_productos');

        $this->db->select('*');
        $this->db->where('pedidos_productos_pedido_id', $id);
        $data['pedidos_productos'] = $this->db->get('pedidos_productos');
        
        $id_addons = array();
        foreach ($data['pedidos_productos']->result_array() as $key => $value) {
            $addons = explode("],[",$value['pedidos_productos_addons']);
            for ($i=0; $i < count($addons); $i++) {
                $childs = explode("/,/",$addons[$i]);
                array_push($id_addons, $childs[0]);
            }
        }
        
        $this->db->select('*');
        if ($id_addons!=array()) {
            $this->db->where_in('addons_id', $id_addons);
        }
        $data['addons'] = $this->db->get('addons_productos');
        return $data;

    }

    public function notas_internas($id=0){
        
        $data = array();
        if (isset($_SESSION['tipo_accesos'])
        && isset($_SESSION['usuarios_id']) 
        && $id!=0) {
            $data['notas_vendedores'] = array();
            $data['notas_pedidos_id'] = $id;
            if ($_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) {

                $this->db->select("pedidos_detalle_vendedor,usuarios.name");
                $this->db->where("pedidos_detalle_pedidos_id",$id);
                $this->db->join("usuarios","alma_pedidos_detalle.pedidos_detalle_vendedor=usuarios.usuarios_id","inner");
                $this->db->group_by("pedidos_detalle_vendedor");
                $id_user = $this->db->get("alma_pedidos_detalle");

                foreach ($id_user->result_array() as $key => $value) {
                    array_push($data['notas_vendedores'],array(
                        "id" => $value['pedidos_detalle_vendedor'],
                        "name" => $value['name'],
                    ));
                }

                $this->db->select("*");
                $this->db->join("usuarios","usuarios.usuarios_id=notas_pedidos.notas_pedidos_usuarios_id","inner");
                $this->db->where("notas_pedidos.notas_pedidos_pedido_id",$id);
                $data['notas'] = $this->db->get("notas_pedidos")->result_array();
                
            }elseif ($_SESSION['tipo_accesos']==8) {
                
                $this->db->select("*");
                $this->db->join("usuarios","usuarios.usuarios_id=notas_pedidos.notas_pedidos_usuarios_id","inner");
                $this->db->where("notas_pedidos.notas_pedidos_pedido_id",$id);
                $this->db->group_start();
                $this->db->where("notas_pedidos.notas_pedidos_usuarios_id",$_SESSION['usuarios_id']);
                $this->db->or_where("notas_pedidos.notas_pedidos_usuario_dirigido",$_SESSION['usuarios_id']);
                $this->db->or_where("notas_pedidos.notas_pedidos_tipo",1);
                $this->db->group_end();
                $data['notas'] = $this->db->get("notas_pedidos")->result_array();
                $data['error'] = 0;
                $data['mensaje'] = "Consulta Realizada con éxito";
                
            }else{
                $data['notas'] = NULL;
                $data['error'] = 1;
                $data['mensaje'] = "Error de autenticación";
            }
        }else{
            $data['notas'] = NULL;
            $data['error'] = 1;
            $data['mensaje'] = "Error de autenticación";
        }

        return $data;

    }
    
    public function singleNew($id){
        $ids_productos = array(0);
        $query = $this->db->query('SHOW COLUMNS FROM alma_pedidos');
        $valores = $query->result_array();
        $this->db->select('*,
        (SELECT departamento FROM departamentos WHERE departamentos.id_departamento=alma_pedidos.pedidos_departamento_envio) as departamento_envio,
        (SELECT municipio FROM municipios WHERE municipios.id_municipio=alma_pedidos.pedidos_municipio_envio) as municipio_envio')
        ->where("pedidos_id",$id);
        $this->db->join('departamentos','departamentos.id_departamento=alma_pedidos.pedidos_departamento','left');
        $this->db->join('municipios','municipios.id_municipio=alma_pedidos.pedidos_municipio','left');
        $query = $this->db->get('alma_pedidos');

        $data = array();
        $data['pedido'] = array();
        $data['productos'] = array(0);
        foreach ($query->result_array() as $key2 => $value2) {
            foreach ($valores as $key => $value) {
                $data['pedido'] += [$value["Field"]=>$value2[$value["Field"]]];
            }
            $data['pedido'] += ["municipio"=>$value2['municipio']];
            $data['pedido'] += ["departamento"=>$value2['departamento']];
            $data['pedido'] += ["municipio_envio"=>$value2['municipio_envio']];
            $data['pedido'] += ["departamento_envio"=>$value2['departamento_envio']];
        }
        
        $this->db->select('alma_pedidos_detalle.*, usuarios.name as nombreVendedor, usuarios.lastname as apellidoVendedor');
        $this->db->join('usuarios','usuarios.usuarios_id = alma_pedidos_detalle.pedidos_detalle_vendedor','left');
        $this->db->where('pedidos_detalle_pedidos_id', $id);
        $data['pedidos_productos'] = $this->db->get('alma_pedidos_detalle')->result_array();

        $id_addons = array();
        $total=0;

        foreach ($data['pedidos_productos'] as $key => $value) {
            array_push($ids_productos, $value['pedidos_detalle_producto']);
            $addons = array();
            if ($value['pedidos_detalle_producto_addons']!="}]}") {
                $addons = str_replace("\n"," ", $value['pedidos_detalle_producto_addons']);
                $addons = str_replace("],{","]},{", $addons);
                $addons = preg_replace("[\n|\r|\n\r]"," ", $addons);
                $addons = json_decode($addons);
                if ($addons!=NULL) {
                    foreach ($addons as $key2 => $value2) {
                        for ($i=0; $i < count($value2); $i++) {
                            array_push($id_addons, $value2[$i]->addons_id);
                        }
                    }
                }
            }
            $parcial = $value['pedidos_detalle_producto_precio'] * $value['pedidos_detalle_producto_cantidad'];
            $total += $parcial;
        }

        //->group_by("productos_id, estatus")
        $data['estatus'] = $this->db->where("pedidos_id",$id)->order_by("pedidos_estatus_productos_id","DESC")->limit(count($data['pedidos_productos']))->get('pedidos_estatus_productos');

        $data['vlrtotal'] = $total;
        $data['cantitems'] = count($data['pedidos_productos']);

        if ($ids_productos) {
            $this->db->select('*')
            ->join("medios","medios.medios_id=productos.productos_imagen_destacada","left")
            ->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left")
            ->where_in("productos_id",$ids_productos);
        }else{
            $this->db->select('*')
            ->join("medios","medios.medios_id=productos.productos_imagen_destacada","left")
            ->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left")
            ->where_in("productos_id",array(0));
        }
        $data['productos'] = $this->db->get('productos')->result_array();
        
        $this->db->select('*');
        if ($id_addons!=array()) {
            $this->db->where_in('addons_id', $id_addons);
        }
        $data['addons'] = $this->db->get('addons_productos');

        return $data;

    }

    function vacio(){
        $query = $this->db->query('SHOW COLUMNS FROM productos');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $data += [$value["Field"]=>""];
        }
        return $data;
    }
    public function add(){
        $query = $this->db->query('SHOW COLUMNS FROM productos');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
        $field = $value["Field"];
        if ($value["Field"]!="productos_id" and $this->input->post($value["Field"])!=NULL) {
            $data += [$value["Field"]=>$this->input->post($value["Field"])];
        }
        }
        $ingresar['data'] = $this->db->insert('productos', $data);
        return $ingresar;
    }
    function save(){

        $query = $this->db->query('SHOW COLUMNS FROM pedidos');
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
          $field = $value["Field"];
          if ($value["Field"]!="pedidos_id" && $this->input->post($value["Field"])!=NULL) {
            $data += [$value["Field"]=>$this->input->post($value["Field"])];
          }
        }
        $ingresar['data'] = $this->db->insert('pedidos', $data);

        return $ingresar;
    }
    function edit(){        
        $query = $this->db->query('SHOW COLUMNS FROM pedidos');
        
        $datos = array();
        $products_verify = 0;
        
        if (isset($_POST['pedidos_productos_estatus']) && $_POST['pedidos_productos_estatus']!=NULL) {
            for ($i=0; $i < count($_POST['estatus_productos_producto_id']); $i++) {
                array_push($datos, array(
                    'productos_id' => $_POST['estatus_productos_producto_id'][$i],
                    'pedidos_id' => $_POST['pedidos_id'],
                    'estatus' => $_POST['pedidos_productos_estatus'][$i], 
                    'nro_guia' => $_POST['nro_guia'][$i], 
                    'nombre_empresa' => $_POST['nombre_empresa'][$i], 
                    'addons' => $_POST['addons'][$i], 
                    'cambio_usuarios_id' => $_SESSION['usuarios_id'] 
                ));
                if ($_POST['pedidos_productos_estatus'][$i]=="Enviado" || $_POST['pedidos_productos_estatus'][$i]=="Reembolsado") {
                    $products_verify++;
                }
            }
    
            $this->db->where('pedidos_id', $_POST['pedidos_id']);
            $this->db->delete('pedidos_estatus_productos');
        
            $this->db->insert_batch('pedidos_estatus_productos', $datos);

            $this->db->select('*')->where("pedidos_id",$_POST['pedidos_id']);
            $estatus = $this->db->get('pedidos_estatus_productos');
            $estatus_en = 0;
            
            foreach ($estatus->result_array() as $key => $value) {
                if ($value['estatus']=="Enviado") {
                    $estatus_en++;
                }
            }
            
            $this->db->select('pedidos_productos_id')->where("pedidos_productos_pedido_id",$_POST['pedidos_id']);
            $estatus_en_ver = $this->db->get('pedidos_productos');
            
            if ($estatus_en==$estatus_en_ver->num_rows()) {
                $pedido_estatus="Enviado";
            }else{
                $pedido_estatus=$_POST['pedidos_estatus'];
            }

        }else{
            $pedido_estatus=$_POST['pedidos_estatus'];
        }

        
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="pedidos_id" && $value["Field"]!="pedidos_estatus" && $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }
        $data += ["pedidos_estatus"=>$pedido_estatus];
        
        $this->db->where('pedidos_id', $this->input->post('pedidos_id'));
        $ingresar['data'] = $this->db->update('pedidos', $data);

        $ingresar['estatus'] = array(
            'pedidos_id' => $_POST['pedidos_id'],
            'pedidos_estatus' => $pedido_estatus,
            'pedidos_productos' => explode(",",$_POST['pedidos_productos']),
        ); 

        if ($pedido_estatus=="Enviado") {
            $this->mailPedidoEnviado($this->single($_POST["pedidos_id"]));
        }elseif ($pedido_estatus=="En preparación" || $pedido_estatus==4) {
            $this->mailPedidoPreparacion($this->single($_POST["pedidos_id"]));
        }elseif ($pedido_estatus=="Esperando confirmación de Pago") {
            $this->mailPedidoRecibido($this->single($_POST["pedidos_id"]));
        }elseif ($pedido_estatus=="Rechazado" || $pedido_estatus==6) {
            $this->mailPedidoFallido($this->single($_POST["pedidos_id"]));
        }
        
        return $ingresar;
    }
    
    function edit_new(){            

        $this->db->select('pedidos_detalle_id, pedidos_detalle_producto')->where("pedidos_detalle_pedidos_id",$_POST['pedidos_id']);
        $estatus_en_ver = $this->db->get('alma_pedidos_detalle');
        
        if ($_POST['pedidos_estatus']=="Rechazado") {
            $this->db->where('pedidos_id', $_POST['pedidos_id']);
            $this->db->where('estatus!=', "Confirmado");
            $this->db->delete('pedidos_estatus_productos');
        }
        if ($_POST['pedidos_estatus']=="Cancelado") {
            $this->db->where('pedidos_id', $_POST['pedidos_id']);
            $this->db->where('estatus!=', "Confirmado");
            $this->db->delete('pedidos_estatus_productos');
        }
        if (
            (
                $_POST['pedidos_estatus_anterior']=="Enviado"
                || $_POST['pedidos_estatus_anterior']=="Rechazado"
                || $_POST['pedidos_estatus_anterior']=="Cancelado"
                || $_POST['pedidos_estatus_anterior']=="Pendiente"
                || $_POST['pedidos_estatus_anterior']==6
            ) 
        &&
            $_POST['pedidos_estatus']=="En preparación") {
            $this->db->where('pedidos_id', $_POST['pedidos_id']);
            $this->db->where('estatus!=', "Confirmado");
            $this->db->delete('pedidos_estatus_productos');
        }

        $prod_envio_coord=[];

        $query = $this->db->query('SHOW COLUMNS FROM alma_pedidos');    
        $datos = array();
        $products_verify = 0;
        if (isset($_POST['pedidos_productos_estatus']) 
            && $_POST['pedidos_productos_estatus']!=NULL
            && $_POST['pedidos_estatus']!="Esperando confirmación de Pago"
            && $_POST['pedidos_estatus']!="Pendiente"
        ) {
            for ($i=0; $i < count($_POST['estatus_productos_producto_id']); $i++) {
                if ($_POST['pedidos_estatus']=="Rechazado") {
                    $this_estatus = "Rechazado";
                }else if ($_POST['pedidos_estatus']=="Cancelado") {
                    $this_estatus = "Cancelado";
                }
                elseif (
                    (
                        $_POST['pedidos_estatus_anterior']=="Enviado"
                        || $_POST['pedidos_estatus_anterior']=="Rechazado"
                        || $_POST['pedidos_estatus_anterior']=="Cancelado"
                        || $_POST['pedidos_estatus_anterior']=="Pendiente"
                    ) 
                && $_POST['pedidos_estatus']==="En preparación") {
                    $this_estatus = "En preparación";
                }
                else{
                    $this_estatus = $_POST['pedidos_productos_estatus'][$i];
                }

                if($_POST['pedidos_estatus']==="En preparación") $this_estatus = "En preparación";
                
                if(isset($_POST['coordinadora_estado'][$i])){
                    //print 'CHECKEADO '.$_POST['estatus_productos_producto_id'][$i].' - ESTADO '.$_POST['coordinadora_estado'][$i].'<br>'.PHP_EOL;
                    /*
                    Esta checkeado pero no tiene guia.... añadimos y preparamos envio WedbService...
                    a su vez... actualizamos en detalle producto
                    */
                    if($_POST['coordinadora_guia'][$i]==='') $prod_envio_coord[]=$_POST['estatus_productos_producto_id'][$i];
                }  

                $coordEstado = (isset($_POST['coordinadora_estado'][$i]))?1:0; 
                $coordGuia = (isset($_POST['coordinadora_guia'][$i]))?$_POST['coordinadora_guia'][$i]:'';   
                $coordSegu = (isset($_POST['coordinadora_seguimiento'][$i]))?$_POST['coordinadora_seguimiento'][$i]:'';   

                array_push($datos, array(
                    'productos_id' => $_POST['estatus_productos_producto_id'][$i],
                    'pedidos_id' => $_POST['pedidos_id'],
                    'estatus' => $this_estatus, 
                    'nro_guia' => $_POST['nro_guia'][$i], 
                    'nombre_empresa' => $_POST['nombre_empresa'][$i], 
                    'addons' => $_POST['addons'][$i], 
                    'cambio_usuarios_id' => $_SESSION['usuarios_id'],
                    'coordinadora_estado' => $coordEstado,
                    'coordinadora_guia' => $coordGuia,
                    'coordinadora_seguimiento' => $coordSegu
                ));
                
            }
            $this->db->insert_batch('pedidos_estatus_productos', $datos);

            if ($_POST['pedidos_estatus']!="Rechazado"
            || $_POST['pedidos_estatus']!="Cancelado"
            || $_POST['pedidos_estatus']!="Pendiente"
            || $_POST['pedidos_estatus']!="Esperando confirmación de Pago"
            ) {
                $this->db->select('*')->where("pedidos_id",$_POST['pedidos_id'])->order_by("fecha_modificacion","DESC");
                $estatus = $this->db->get('pedidos_estatus_productos');
                $estatus_en = array();
                $conteo_estatus_en = 0;
                foreach ($estatus->result_array() as $key => $value) {
                    if ($value['estatus']=="Enviado") {
                        $agg = 1;
                        for ($i=0; $i < count($estatus_en); $i++) {
                            if ($estatus_en[$i]['producto']==$value['productos_id'] && $estatus_en[$i]['estatus']==$value['estatus']) {
                                $agg = 0;
                            }
                        }
                        if ($agg==1) {
                            array_push($estatus_en,array(
                                "producto" => $value['productos_id'],
                                "estatus" => $value['estatus'],
                            ));
                            $conteo_estatus_en++;
                        }
                    }
                }
                $cant_prod = array();
                $conteo_prod = 0;
                foreach ($estatus_en_ver->result_array() as $key => $value) {
                    $agregar = 1;
                    for ($i=0; $i < count($cant_prod); $i++) {
                        if ($cant_prod[$i]['productos']==$value['pedidos_detalle_producto']) {
                            $agregar = 0;
                        }
                    }
                    if ($agregar == 1) {
                        array_push($cant_prod, array(
                            "productos" => $value['pedidos_detalle_producto']
                        ));
                        $conteo_prod++;
                    }
                }
                if ($conteo_estatus_en==$conteo_prod) {
                    $pedido_estatus="Enviado";
                }else{
                    $pedido_estatus=$_POST['pedidos_estatus'];
                }
            }else{
                $pedido_estatus=$_POST['pedidos_estatus'];
            }

        }else{
            $pedido_estatus=$_POST['pedidos_estatus'];
        }

        //se existe productos pendinte por enviar
        if(count($prod_envio_coord)>0){
            $obj = new $this->wservicecoordinadora();
            $respuesta = $obj->getGenerarGuia($_POST['pedidos_id'], $prod_envio_coord);
            $jsonRespuesta = json_decode($respuesta);
        }
        
        $valores = $query->result_array();
        $data = array();
        foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="pedidos_id" && $value["Field"]!="pedidos_estatus" && $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$this->input->post($value["Field"])];
            }
        }
        $data += ["pedidos_estatus"=>$pedido_estatus];
        
        $this->db->where('pedidos_id', $this->input->post('pedidos_id'));
        $ingresar['data'] = $this->db->update('alma_pedidos', $data);

        $ingresar['estatus'] = array(
            'pedidos_id' => $_POST['pedidos_id'],            'pedidos_estatus' => $pedido_estatus
        ); 

        
        $pedido = $this->singleNew($_POST["pedidos_id"]);

        if ($pedido_estatus=="Enviado") {
            $this->mailing_model->mailPedidoEnviado($pedido);
        }elseif ($pedido_estatus=="En preparación" || $pedido_estatus==4) {
            $rptaMail = $this->mailing_model->mailPedidoPreparacion($pedido);
        }elseif ($pedido_estatus=="Esperando confirmación de Pago") {
            $this->mailing_model->mailPedidoRecibido($pedido);
        }elseif ($pedido_estatus=="Rechazado" || $pedido_estatus==6) {
            $this->mailing_model->mailPedidoFallido($pedido);
        } 

        return $ingresar;
    }

    public function mailPedidoEnviado($pedido=array()){
        
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES_1.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Tu pedido está en camino</p>
                    <div style="width:100%;display:none;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/'.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Tu pedido #'.$pedido['pedido']['pedidos_id'].' ha sido enviado, y se encuentra en estos momentos en camino a destino.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                    <thead style="background-color:#eaeaea;">
                    <tr style="padding:10px;background-color:#eaeaea;">
                    <th style="text-align:left;padding:10px">Número de pedido</th>
                    <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                    </tr>
                    </thead>
                    <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_productos_pedido_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_productos_producto_id']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_productos_producto_id']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_productos_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_productos_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['pedidos_localidad'].'
'.$pedido['pedido']['pedidos_departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['pedidos_localidad_envio'].'
'.$pedido['pedido']['pedidos_departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }

    }
    public function mailNuevaVenta($pedido=array()){
        if ($pedido!=array()) {
            $productos = explode(",",$pedido['pedido']['pedidos_productos']);
        }
    }

    public function mailPedidoFallido($pedido=array()){
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES-03.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Tu pedido fue fallido</p>
                    <div style="width:100%;display:none;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/'.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Lo sentimos, tu pedido #'.$pedido['pedido']['pedidos_id'].' ha fallido.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Número de pedido</th>
                                <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_productos_pedido_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_productos_producto_id']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_productos_producto_id']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_productos_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_productos_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['pedidos_localidad'].'
'.$pedido['pedido']['pedidos_departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['pedidos_localidad_envio'].'
'.$pedido['pedido']['pedidos_departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }
    }
    public function mailPedidoPreparacion($pedido=array()){
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '<!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES-02.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Tu pedido está en preparación</p>
                    <div style="width:100%;display:none;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/'.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Buenas noticias!, tu pedido #'.$pedido['pedido']['pedidos_id'].' ya está en preparación, te enviaremos un correo una vez haya sido enviado.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Número de pedido</th>
                                <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_productos_pedido_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_productos_producto_id']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_productos_producto_id']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_productos_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_productos_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['pedidos_localidad'].'
'.$pedido['pedido']['pedidos_departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['pedidos_localidad_envio'].'
'.$pedido['pedido']['pedidos_departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }
    }
    public function mailPedidoRecibido($pedido=array()){
        if ($pedido!=array()) {
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las cosas');
            //Set who the message is to be sent to
            $mail->addAddress($pedido['pedido']['pedidos_correo'], $pedido['pedido']['pedidos_nombre']);
            //Set the subject line
            $mail->Subject = 'PEDIDO - ALMA DE LAS COSAS';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>PEDIDOS - ALMA DE LAS COSAS</title>
            </head>
            <body style="text-align:center;background-color:#fff7ec;padding-top:20px; padding-bottom:20px;width:100%;">
                <div style="background-color:#fff;width:100%;max-width:600px;padding-top:10px;padding-bottom:10px;margin:auto;">
                    <p>
                        <a href="'.base_url().'">
                            <img style="margin:auto;" width="65px" src="'.base_url().'assets/img/logo.png">
                        </a>
                    </p>
                    <div style="width:100%;text-align:center;">
                        <img style="width:100%;max-width:300px;margin:auto;" src="'.base_url().'/assets/img/ILUSTRACIONES-04.png" alt="Ilutracion">
                    </div>
                    <p style="font-weight:600;margin-top:15px;">Hemos recibido tu pedido</p>
                    <div style="width:100%;display:none;margin-top:20px;margin-bottom:20px;">
                        <a style="background-color: #e6a726;
                        font-size: 12px;
                        text-decoration: none;
                        box-shadow: 0px 3px 10px #ccc;
                        border-radius: 15px;color:#fff;font-weight:600;padding-left:40px;padding-right:40px;padding-top:10px;padding-bottom:10px;" href="'.base_url().'rastreo/'.$pedido['pedido']['pedidos_id'].'">RASTREAR PEDIDO</a>
                    </div>
                    <h3>¡Hola '.$pedido['pedido']['pedidos_nombre'].'!</h3>
                    <p style="margin-bottom:15px;width:90%;margin-left:5%;">Hemos recibido tu pedido #'.$pedido['pedido']['pedidos_id'].', le enviremos otro correo cuando se haya confirmado el pago.<br>No dudes en contactarnos si tienes alguna duda.</p>
    
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Número de pedido</th>
                                <th style="text-align:right;padding:10px">#'.$pedido['pedido']['pedidos_id'].'</th>
                            </tr>
                        </thead>
                        <tbody>';
                        $precioTotal = 0;
                        $productosMostrar = array();
                        $total=0;
                        $total_envio=0;
                        $agregado = array();
                        if ($pedido["productos"]!=NULL && $pedido["productos"]!="") {
                            if ($pedido["productos"]->num_rows() > 0) {
                                foreach ($pedido["pedidos_productos"]->result_array() as $key2 => $value2) {
                                    if ($value2['pedidos_productos_pedido_id']==$pedido['pedido']['pedidos_id']) {
                                        $agregado[$key2] = 0;
                                        foreach ($pedido["productos"]->result_array() as $key3 => $value3) {
                                            if ($value3['productos_id']==$value2['pedidos_productos_producto_id']) {
                                                $agg=0;
                                                for ($i=0; $i < count($productosMostrar); $i++) {
                                                    if (isset($productosMostrar[$i]['productos_id'])) {
                                                        if($productosMostrar[$i]['productos_id']==$value2['pedidos_productos_producto_id']){
                                                            if (floatval($productosMostrar[$i]['productos_precio'])==floatval($value2['pedidos_productos_precio'])) {
                                                                if ($agregado[$key2]!=1) {
                                                                    $productosMostrar[$i]['productos_cantidad']=floatval($productosMostrar[$i]['productos_cantidad'])+floatval($value2['pedidos_productos_cantidad']);
                                                                    $agregado[$key2] = 1;
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                                
                                                if ($agregado[$key2]!=1) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                                if (count($productosMostrar)<=0) {
                                                    $image = "";
                                                    if ($value3['productos_imagen_destacada']!="" && $value3['productos_imagen_destacada']!=0) {
                                                        $image=base_url().$value3['medios_url'];
                                                    }
                                                    array_push($productosMostrar, array(
                                                        'productos_id' => $value3['productos_id'],
                                                        'productos_imagen' => $image,
                                                        'productos_titulo' => $value3['productos_titulo'],
                                                        'productos_precio' => floatval($value2['pedidos_productos_precio']),
                                                        'productos_cantidad' => floatval($value2['pedidos_productos_cantidad']),
                                                        'productos_vendedor' => $value3['productos_vendedor'],
                                                    ));
                                                    $agregado[$key2] = 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            
                        }
                        foreach ($productosMostrar as $key4 => $value4) {
                            $cuerpo.='
                                <tr>
                                    <td style="text-align:left;padding-bottom:15px;padding-top:15px;">';
                            $this_price = 0;
                            $cuerpo.=$value4['productos_titulo']." $".number_format($value4['productos_precio'], 0, ',', '.')." x ".$value4['productos_cantidad']."<br>";
                            $this_price = $value4['productos_precio']*$value4['productos_cantidad'];
                            $cuerpo.='</td>
                                    <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($this_price, 0, ',', '.').'</td>
                                </tr>
                                <tr>
                            ';
                            $total=$total+$this_price;
                        }

                        $cuerpo.='
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Valor del envío</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_envio'], 0, ',', '.').'</td>
                            </tr>
                            <tr style="border-top:solid 1px #000;">
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;">Total</td>
                                <td style="text-align:right;padding-bottom:15px;padding-top:15px;">$'.number_format($pedido['pedido']['pedidos_precio_total'], 0, ',', '.').'</td>
                            </tr>
                        </tbody>
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de facturación</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_nombre'].' 
'.$pedido['pedido']['pedidos_direccion'].'
'.$pedido['pedido']['pedidos_localidad'].'
'.$pedido['pedido']['pedidos_departamento'].'
'.$pedido['pedido']['pedidos_codigo_postal'].'
'.$pedido['pedido']['pedidos_telefono'].'
'.$pedido['pedido']['pedidos_correo'].'<pre></td>
                        </tbody>
                        
                    </table>
                    <table style="width:90%;margin-left:5%;margin-bottom:15px;border-collapse:collapse;">
                        <thead style="background-color:#eaeaea;">
                            <tr style="padding:10px;background-color:#eaeaea;">
                                <th style="text-align:left;padding:10px">Dirección de envío</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
<td style="text-align:left;padding-bottom:15px;padding-top:15px;"><pre>'.$pedido['pedido']['pedidos_correo_envio'].' 
'.$pedido['pedido']['pedidos_direccion_envio'].'
'.$pedido['pedido']['pedidos_localidad_envio'].'
'.$pedido['pedido']['pedidos_departamento_envio'].'
'.$pedido['pedido']['pedidos_codigo_postal_envio'].'
'.$pedido['pedido']['pedidos_correo_envio'].'<pre></td>
                            </tr>
                        </tbody>
                        
                    </table>
    
                    
                </div>
            </body>
            </html>';
            $mail->msgHTML($cuerpo);
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if (!$mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
            }
            
        }
    }
}
