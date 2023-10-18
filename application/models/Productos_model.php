<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Productos_model extends CI_Model {

    public function getAgotados($filtros=array(),$page=1,$limite=NULL){
        $this->db->select("*")
        ->join("medios","medios.medios_id=productos.productos_imagen_destacada","left")
        ->where("productos_gestion_inv",1)
        ->where("productos_stock <=",0);
        if ($_SESSION['tipo_accesos']!=1 && $_SESSION['tipo_accesos']!=0) {
            $this->db->where("productos_vendedor",$_SESSION['usuarios_id']);
        }
        if ($limite==NULL) {
            $limit = 24;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(24,$limit);
        }else{
            $this->db->limit($limite[0],$limite[1]);
        }
        $productos = $this->db->get("productos");
        return $productos;
    }

    public function getAll($filtros=array(),$page=1,$limite=NULL){

        $this->db->select('*');
        $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","left");
        $this->db->join("productos_order_defecto","productos_order_defecto.productos=productos.productos_id","left");
        if (
        isset($_SESSION['municipio_session']) 
        && $_SESSION['municipio_session']!=0
        && ((!isset($_SESSION['tipo_accesos'])) || $_SESSION['tipo_accesos']!=0 && $_SESSION['tipo_accesos']!=1)
        ) {
            $this->db->group_start();
            $this->db->where("productos_envio_nacional",1);
            $this->db->or_like("productos_ubicaciones_envio",$_SESSION['departamento_session'].",".$_SESSION['municipio_session']);
            $this->db->group_end();
        }elseif (!isset($_SESSION['tipo_accesos']) || ($_SESSION['tipo_accesos']!=0 && $_SESSION['tipo_accesos']!=1)) {
            $this->db->group_start();
            $this->db->where("productos_envio_nacional",1);
            $this->db->group_end();   
        }
        
        if (isset($filtros['where']) && $filtros['where']!=NULL && $filtros['where']!="") {
            $this->db->group_start();
            $this->db->like($filtros['where'][0], $filtros['where'][1]);
            $this->db->group_end();
        }
        if (isset($filtros['where_in']) && $filtros['where_in']!=NULL && $filtros['where_in']!=""
        && isset($filtros['where_in_name']) && $filtros['where_in_name']!=NULL && $filtros['where_in_name']!=""
        ) {
            $this->db->group_start();
            $this->db->where_in($filtros['where_in_name'],$filtros['where_in']);
            $this->db->group_end();
        }
        if (isset($filtros['where_not_in']) && $filtros['where_not_in']!=NULL && $filtros['where_not_in']!=""
        && isset($filtros['where_not_in_name']) && $filtros['where_not_in_name']!=NULL && $filtros['where_not_in_name']!=""
        ) {
            $this->db->group_start();
            $this->db->where_not_in($filtros['where_not_in_name'],$filtros['where_not_in']);
            $this->db->group_end();
        }
        if ((isset($_REQUEST['minprice']) && isset($_REQUEST['maxprice'])) && ($_REQUEST['maxprice']!=NULL && $_REQUEST['minprice']!=NULL) ) {
            $this->db->group_start();
            $this->db->where("productos_precio >=", $_REQUEST['minprice']); 
            $this->db->where("productos_precio <=", $_REQUEST['maxprice']); 
            $this->db->group_end();
        }
        if (isset($filtros['like']) && $filtros['like']!=NULL && $filtros['like']!="") {
            $this->db->group_start();
            $this->db->like($filtros['like'][0], $filtros['like'][1]);
            $this->db->group_end();
        }

        if (isset($_REQUEST['productos_categorias']) && ($_REQUEST['productos_categorias']!=NULL && $_REQUEST['productos_categorias']!="" && $_REQUEST['productos_categorias']!=array())) {
            $productos_categorias = array();
            $this->db->group_start();
            for ($i=0; $i < count($_REQUEST['productos_categorias']); $i++) { 
                if ($i==0) {
                    $this->db->like("productos_categorias","/,/".$_REQUEST['productos_categorias'][$i]."/,/");
                }else{
                    $this->db->or_like("productos_categorias","/,/".$_REQUEST['productos_categorias'][$i]."/,/");
                }
            }
            $this->db->group_end();
        }
        if (!isset($_SESSION['tipo_accesos']) 
        || ($_SESSION['tipo_accesos']!=0 && $_SESSION['tipo_accesos']!=1 && $_SESSION['tipo_accesos']!=8)){
            $this->db->group_start();
            $this->db->where('productos_gestion_inv',1);
            $this->db->where('productos_stock > ', 0);
            $this->db->or_where('productos_gestion_inv',0);
            $this->db->where('productos_estado_inv',1);
            $this->db->group_end();
        }
        if (isset($filtros['where_arr']) && $filtros['where_arr']!=NULL && $filtros['where_arr']!="") {
            $this->db->group_start();
            foreach ($filtros['where_arr'] as $key => $value) {
                if($key!=='productos_titulo') $this->db->where($key, $value);
                if($key==='productos_titulo') $this->db->like($key, $value);
            }
            $this->db->group_end();
        }
        $this->db->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left");
        if ($limite==NULL) {
            $limit = 24;
            $page = $page-1;
            $limit = $limit*$page;
            $this->db->limit(24,$limit);
        }else{
            $this->db->limit($limite[0],$limite[1]);
        }
        if (isset($filtros['orderby']) &&  $filtros['orderby']!=NULL && $filtros['orderby']!="" && $filtros['orderby']!=array()) {
            $this->db->order_by($filtros['orderby'][0],$filtros['orderby'][1]);
        }else{
            $this->db->order_by("productos_order_defecto.id","ASC");
        }
        $productos = $this->db->get('productos');
        //print $this->db->last_query();
        return $productos;
    }
    public function addons($id=0){
        $this->db->select('*');
        $this->db->where("addons_producto_id", $id);
        return $productos = $this->db->get('addons_productos')->result_array();
    }
    public function maxaddonsp($id=0){
        $addons = array();
        $this->db->select('max(addons_id)')->where("productos_id",$id);
        $cons = $this->db->get('addons');
        $addons['addons_maximo_id'] = "";
        foreach ($cons->result_array() as $key => $value) {
            $addons['addons_maximo_id'] = $value['max(addons_id)'];
        }
        return $addons;
    }
    public function maxminPrice(){
        $precios = array();
        $this->db->select('max(productos_precio)');
        $productos = $this->db->get('productos');
        $precios['maximo'] = "";
        foreach ($productos->result_array() as $key => $value) {
            $precios['maximo'] = $value['max(productos_precio)'];
        }
        $this->db->select('min(productos_precio)');
        $productos = $this->db->get('productos');
        $precios['minimo'] = "";
        foreach ($productos->result_array() as $key => $value) {
            $precios['minimo'] = $value['min(productos_precio)'];
        }
        return $precios;
    }
    public function single($id="",$type=""){

        $query = $this->db->query('SHOW COLUMNS FROM productos');
        $query1 = $this->db->query('SHOW COLUMNS FROM medios');
        $valores = $query->result_array();
        $valores1 = $query1->result_array();
        $this->db->select('*')
        ->join("medios","medios.medios_id=productos.productos_imagen_destacada","left")
        ->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left");
        if ($type!="" && $type=="slug") {
            $this->db->where("productos_slug",$id);
        }else{
            $this->db->where("productos_id",$id);
        }
        $query = $this->db->get('productos');
        $data = array();
        foreach ($query->result_array() as $key2 => $value2) {
            foreach ($valores as $key => $value) {
                $data += [$value["Field"]=>$value2[$value["Field"]]];
            }
            foreach ($valores1 as $key => $value) {
                $data += [$value["Field"]=>$value2[$value["Field"]]];
                
            }
            $data += ["vendedor_nombre"=>$value2["name"]];
        }
        
        $data += ["ubicaciones_texto"=>""];
        
        $ubicaciones = explode("/",$data['productos_ubicaciones_envio']);
        $departamentos = array();
        $municipios = array();
        for ($i=0; $i < count($ubicaciones); $i++) {
            $dep = explode(",",$ubicaciones[$i]);
            if (isset($dep[1])) {
                array_push($departamentos, $dep[0]);
                array_push($municipios, $dep[1]);
            }
        }

        if ($departamentos!=array() && $municipios!=array()) {
            $this->db->select("*");
            $this->db->join("departamentos","departamentos.id_departamento=municipios.departamento_id","inner");
            $this->db->where_in("id_municipio",$municipios);
            $res = $this->db->get("municipios");
            foreach ($res->result_array() as $key => $value) {
                if($data['ubicaciones_texto']==""){
                    $data['ubicaciones_texto'] .= $value['departamento'].", ".$value['municipio'];
                }else{
                    $data['ubicaciones_texto'] .= " - ".$value['departamento'].", ".$value['municipio'];
                }
            }
        }

        return $data;
    }
    public function relacionados($relations){
        $productos_relacionados = array(0);
        if ($relations!="") {
            $productos_relacionados = explode(",", $relations);
        }
        $this->db->select("*");
        $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","left");
        $this->db->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left");
        
        for ($i=0; $i < count($productos_relacionados); $i++) {
            if ($i==0) {
                $this->db->where("productos_id",$productos_relacionados[$i]);
            }else{
                $this->db->or_where("productos_id",$productos_relacionados[$i]);
            }
        }
        $datos_productos_relacionados = $this->db->get("productos");
        return $datos_productos_relacionados;
    }
    public function singleNext($id){
        $idcons = 0;
        $queryn = $this->db->query('SELECT productos_id
        FROM productos
        WHERE productos_id = (SELECT MIN(productos_id)
                    FROM productos
                    WHERE productos_id > '.$id.')
                    AND productos_video != "" 
                    LIMIT 1');
                    foreach ($queryn->result_array() as $key2 => $value2) {
                        $idcons = $value2["productos_id"];
                    }

        $query = $this->db->query('SHOW COLUMNS FROM productos');
        $query1 = $this->db->query('SHOW COLUMNS FROM medios');
        $valores = $query->result_array();
        $valores1 = $query1->result_array();
        $this->db->select('*')
        ->join("medios","medios.medios_id=productos.productos_imagen_destacada","left")
        ->where("productos_id",$idcons);
        $query = $this->db->get('productos');
        $data = array();
        foreach ($query->result_array() as $key2 => $value2) {
            foreach ($valores as $key => $value) {
                $data += [$value["Field"]=>$value2[$value["Field"]]];
            }
            foreach ($valores1 as $key => $value) {
                if (isset($value2[$value["Field"]]) && $value2[$value["Field"]]!=NULL) {
                    $data += [$value["Field"]=>$value2[$value["Field"]]];
                }
            }
        }
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

        $verify_slug = 0;

        if ($this->input->post("productos_slug")!="") {
            $this->db->select("productos_slug")
            ->where("productos_slug",$this->input->post("productos_slug"));
            $cons = $this->db->get("productos");
            if ($cons->num_rows() <= 0) {
                $verify_slug = 1;
            }
        }else{
            $verify_slug = 1;
        }

        if ($verify_slug == 1) {
            $_POST['productos_ubicaciones_envio'] = "";
            $_POST['productos_envio_local'] = 1;
            for ($i=0; $i < count($_POST['productos_departamentos']); $i++) {
                if ($i==0) {
                    $_POST['productos_ubicaciones_envio'] .= $_POST['productos_departamentos'][$i].",".$_POST['productos_municipios'][$i];
                }else{
                    $_POST['productos_ubicaciones_envio'] .= "/".$_POST['productos_departamentos'][$i].",".$_POST['productos_municipios'][$i];
                }
            }
            
            if (isset($_POST['productos_envio_nacional']) && $_POST['productos_envio_nacional']=="on") {
                $_POST['productos_envio_nacional'] = 1;
            }else{
                $_POST['productos_envio_nacional'] = 0;
            }

            $productos_relacionados="";
            if (isset($_POST['productos_relacionados'])) {
                for ($i=0; $i < count($_POST['productos_relacionados']); $i++) {
                    if ($i==0) {
                        $productos_relacionados.=$_POST['productos_relacionados'][$i];
                    }else{
                        $productos_relacionados.=",".$_POST['productos_relacionados'][$i];
                    }
                }
            }

            $query = $this->db->query('SHOW COLUMNS FROM productos');
            $valores = $query->result_array();
            $data = array();
            foreach ($valores as $key => $value) {
            $field = $value["Field"];
            if ($value["Field"]!="productos_slug" && $value["Field"]!="productos_id" && $value['Field']!="productos_sku" && $value['Field']!="productos_relacionados" && $value['Field']!="productos_imagenes" && $value['Field']!="productos_tipo_presentacion" && $value["Field"]!="productos_dimensiones" && $value["Field"]!="productos_categorias" && $value["Field"]!="productos_programacion" && $this->input->post($value["Field"])!=NULL) {
                $data += [$value["Field"]=>$_POST[$value["Field"]]];
            }
            }
            if ($this->input->post("productos_slug")=="") {
                $data += ["productos_slug"=>limpiarUri($this->input->post('productos_titulo'))."-".rand(1,9999)];
            }else{
                $data += ["productos_slug"=>$this->input->post("productos_slug")];
            }

            $programacion = "";
            $cn = 0;
            if ($this->input->post('productos_programacion')==NULL) {
                $_POST['productos_programacion'] = array();
            }
            foreach ($this->input->post('productos_programacion') as $key) {
                $cn++;
                if ($cn==1) {
                    $programacion .= "/,/".$key."/,/";
                }else{
                    $programacion .= $key."/,/";
                }
            }
            $dimensiones = "";
            $cn = 0;
            if ($this->input->post('productos_dimensiones')==NULL) {
                $_POST['productos_programacion'] = array();
            }
            foreach ($this->input->post('productos_dimensiones') as $key) {
                $cn++;
                if ($cn==1) {
                    $dimensiones .= "/,/".$key."/,/";
                }else{
                    $dimensiones .= $key."/,/";
                }
            }
            $categorias = "";
            $cn = 0;
            if ($this->input->post('productos_categorias')==NULL) {
                $_POST['productos_programacion'] = array();
            }
            foreach ($this->input->post('productos_categorias') as $key) {
                $cn++;
                if ($cn==1) {
                    $categorias .= "/,/".$key."/,/";
                }else{
                    $categorias .= $key."/,/";
                }
            }
            $productos_tipo_presentacion = "";
            $cn = 0;
            if ($this->input->post('productos_tipo_presentacion')==NULL) {
                $_POST['productos_tipo_presentacion'] = array();
            }
            foreach ($this->input->post('productos_tipo_presentacion') as $key) {
                $cn++;
                if ($cn==1) {
                    $productos_tipo_presentacion .= "/,/".$key."/,/";
                }else{
                    $productos_tipo_presentacion .= $key."/,/";
                }
            }

            if(isset($_POST['productos_imagen_destacada']) && $_POST['productos_imagen_destacada']!=='') getMiniaturaProduct($_POST['productos_imagen_destacada']);

            $productos_imagenes = '';
            //if ($this->input->post('productos_imagenes')==NULL) $_POST['productos_imagenes'] = array();

            if(isset($_POST['productos_imagenes'])) $productos_imagenes=implode('/,/', $_POST['productos_imagenes']);

            $data += ["productos_programacion"=>$programacion];
            $data += ["productos_dimensiones"=>$dimensiones];
            $data += ["productos_categorias"=>$categorias];
            $data += ["productos_imagenes"=>$productos_imagenes];
            $data += ["productos_relacionados"=>$productos_relacionados];
            $ingresar['data'] = $this->db->insert('productos', $data);

            $ultimoId = $this->db->insert_id();

            $sku = "";
            if (isset($_POST['productos_titulo'])) {
                $sku = str_replace(" ","",$_POST['productos_titulo']);
                $sku = substr($sku,0,3);
                $sku = $sku.$ultimoId;
            }

            $this->db->set('productos_sku', $sku);
            $this->db->where('productos_id', $ultimoId);
            $this->db->update('productos');

            $addons = array();

            for ($i=0; $i < count($_POST['addons_id']); $i++) {
                if (!isset($_POST['addons_id'][$i]) || $_POST['addons_id'][$i]==NULL) {
                    $_POST['addons_id'][$i]="";
                }
                if (!isset($_POST['addons_tipo'][$i]) || $_POST['addons_tipo'][$i]==NULL) {
                    $_POST['addons_tipo'][$i]="";
                }
                if (!isset($_POST['addons_display'][$i]) || $_POST['addons_display'][$i]==NULL) {
                    $_POST['addons_display'][$i]="";
                }
                if (!isset($_POST['addons_titulo'][$i]) || $_POST['addons_titulo'][$i]==NULL) {
                    $_POST['addons_titulo'][$i]="";
                }
                if (!isset($_POST['addons_tipo_titulo'][$i]) || $_POST['addons_tipo_titulo'][$i]==NULL) {
                    $_POST['addons_tipo_titulo'][$i]="";
                }
                if (!isset($_POST['addons_agg_desc'][$i]) || $_POST['addons_agg_desc'][$i]==NULL) {
                    $_POST['addons_agg_desc'][$i]="";
                }
                if (!isset($_POST['addons_descripcion'][$i]) || $_POST['addons_descripcion'][$i]==NULL) {
                    $_POST['addons_descripcion'][$i]="";
                }
                if (!isset($_POST['addons_requerido'][$i]) || $_POST['addons_requerido'][$i]==NULL) {
                    $_POST['addons_requerido'][$i]="";
                }
                if (!isset($_POST['addons_opciones'][$i]) || $_POST['addons_opciones'][$i]==NULL) {
                    $_POST['addons_opciones'][$i]="";
                }
                array_push($addons, array(
                    'addons_id' => $_POST['addons_id'][$i],
                    'addons_tipo' => $_POST['addons_tipo'][$i],
                    'addons_display' => $_POST['addons_display'][$i],
                    'addons_titulo' => $_POST['addons_titulo'][$i],
                    'addons_tipo_titulo' => $_POST['addons_tipo_titulo'][$i],
                    'addons_agg_desc' => $_POST['addons_agg_desc'][$i],
                    'addons_descripcion' => $_POST['addons_descripcion'][$i],
                    'addons_requerido' => $_POST['addons_requerido'][$i],
                    'addons_opciones' => $_POST['addons_opciones'][$i],
                    'addons_producto_id' => $ultimoId
                ));
            }

            $this->db->insert_batch('addons_productos', $addons);

            $ingresar['result'] = 1;
            $ingresar['mensaje'] = "Guardado con Éxito";

        }else{

            $ingresar['result'] = 0;
            $ingresar['mensaje'] = "No se pudieron guardar los cambios, el slug no puede ser igual al de otro producto.";

        }

        return $ingresar;
    }


    function edit(){

        $verify_slug = 0;

        if ($this->input->post("productos_slug")!="") {
            $this->db->select("productos_slug")
            ->where("productos_slug",$this->input->post("productos_slug"))
            ->where("productos_id!=",$this->input->post("productos_id"));
            $cons = $this->db->get("productos");
            if ($cons->num_rows() <= 0) {
                $verify_slug = 1;
            }
        }else{
            $verify_slug = 1;
        }

        if ($verify_slug == 1) {
            $productos_envio_nacional = 0;
            $_POST['productos_ubicaciones_envio'] = "";

            $_POST['productos_envio_local'] = 1;
            for ($i=0; $i < count($_POST['productos_departamentos']); $i++) {
                if ($i==0) {
                    $_POST['productos_ubicaciones_envio'] .= $_POST['productos_departamentos'][$i].",".$_POST['productos_municipios'][$i];
                }else{
                    $_POST['productos_ubicaciones_envio'] .= "/".$_POST['productos_departamentos'][$i].",".$_POST['productos_municipios'][$i];
                }
            }
            if (isset($_POST['productos_envio_nacional']) && $_POST['productos_envio_nacional']=="on") {
                $productos_envio_nacional = 1;
            }else{
                $productos_envio_nacional = 0;
            }
            
            $query = $this->db->query('SHOW COLUMNS FROM productos');
            $valores = $query->result_array();
            $data = array();
            foreach ($valores as $key => $value) {
                $field = $value["Field"];
                if ($value["Field"]!="productos_slug" && $value["Field"]!="productos_id" && $value["Field"]!="productos_envio_nacional" && $value['Field']!="productos_relacionados" && $value['Field']!="productos_imagenes" && $value['Field']!="productos_tipo_presentacion" && $value["Field"]!="productos_dimensiones" && $value["Field"]!="productos_categorias" && $value["Field"]!="productos_programacion" && $this->input->post($value["Field"])!=NULL) {
                    $data += [$value["Field"]=>$_POST[$value["Field"]]];
                }
            }
            if ($this->input->post("productos_slug")=="") {
                $data += ["productos_slug"=>limpiarUri($this->input->post('productos_titulo'))."-".$this->input->post("productos_id")];
            }else{
                $data += ["productos_slug"=>$this->input->post("productos_slug")];
            }
            if (
            !isset($_POST['productos_precio_oferta'])
            || $_POST['productos_precio_oferta']==NULL
            || $_POST['productos_precio_oferta']==""
            ) {
                $data += ["productos_precio_oferta"=>""];
            }
            $data += ["productos_envio_nacional"=>$productos_envio_nacional];
            $programacion = "";
            $cn = 0;
            if (!isset($_POST['productos_programacion']) || $this->input->post('productos_programacion')=="" || $this->input->post('productos_programacion')==NULL) {
                $_POST['productos_programacion'] = array();
            }
            foreach ($_POST['productos_programacion'] as $key) {
                $cn++;
                if ($cn==1) {
                    $programacion .= "/,/".$key."/,/";
                }else{
                    $programacion .= $key."/,/";
                }
            }
            $dimensiones = "";
            $cn = 0;
            if (!isset($_POST['productos_dimensiones']) || $this->input->post('productos_dimensiones')=="" || $this->input->post('productos_dimensiones')==NULL) {
                $_POST['productos_dimensiones'] = array();
            }
            foreach ($_POST['productos_dimensiones'] as $key) {
                $cn++;
                if ($cn==1) {
                    $dimensiones .= "/,/".$key."/,/";
                }else{
                    $dimensiones .= $key."/,/";
                }
            }
            $categorias = "";
            $cn = 0;
            if (!isset($_POST['productos_categorias']) || $this->input->post('productos_categorias')=="" || $this->input->post('productos_categorias')==NULL) {
                $_POST['productos_categorias'] = array();
            }
            foreach ($_POST['productos_categorias'] as $key) {
                $cn++;
                if ($cn==1) {
                    $categorias .= "/,/".$key."/,/";
                }else{
                    $categorias .= $key."/,/";
                }
            }
            $productos_tipo_presentacion = "";
            $cn = 0;
            if (!isset($_POST['productos_tipo_presentacion']) || $this->input->post('productos_tipo_presentacion')=="" || $this->input->post('productos_tipo_presentacion')==NULL) {
                $_POST['productos_tipo_presentacion'] = array();
            }
            foreach ($_POST['productos_tipo_presentacion'] as $key) {
                $cn++;
                if ($cn==1) {
                    $productos_tipo_presentacion .= "/,/".$key."/,/";
                }else{
                    $productos_tipo_presentacion .= $key."/,/";
                }
            }
            
            if(isset($_POST['productos_imagen_destacada']) && $_POST['productos_imagen_destacada']!=='') getMiniaturaProduct($_POST['productos_imagen_destacada']);

            $productos_imagenes = "";
            if ($this->input->post('productos_imagenes')==NULL) {
                $_POST['productos_imagenes'] = array();
            }

            foreach ($this->input->post('productos_imagenes') as $key) {
                $cn++;
                if ($cn==1) {
                    $productos_imagenes .= "/,/".$key."/,/";
                }else{
                    $productos_imagenes .= $key."/,/";
                }
            }
            $productos_relacionados="";
            if (isset($_POST['productos_relacionados'])) {
                for ($i=0; $i < count($_POST['productos_relacionados']); $i++) {
                    if ($i==0) {
                        $productos_relacionados.=$_POST['productos_relacionados'][$i];
                    }else{
                        $productos_relacionados.=",".$_POST['productos_relacionados'][$i];
                    }
                }
            }
            $data += ["productos_programacion"=>$programacion];
            $data += ["productos_dimensiones"=>$dimensiones];
            $data += ["productos_categorias"=>$categorias];
            $data += ["productos_tipo_presentacion"=>$productos_tipo_presentacion];
            $data += ["productos_imagenes"=>$productos_imagenes];
            $data += ["productos_relacionados"=>$productos_relacionados];
            $this->db->where('productos_id', $this->input->post('productos_id'));
            $ingresar['data'] = $this->db->update('productos', $data);
            $addons = array();

            $this->db->where('addons_producto_id', $this->input->post('productos_id'))->delete('addons_productos');

            if (isset($_POST['addons_id'])) {                
                for ($i=0; $i < count($_POST['addons_id']); $i++) {
                    if (!isset($_POST['addons_id'][$i]) || $_POST['addons_id'][$i]==NULL) $_POST['addons_id'][$i]="";
                    if (!isset($_POST['addons_tipo'][$i]) || $_POST['addons_tipo'][$i]==NULL) $_POST['addons_tipo'][$i]="";
                    if (!isset($_POST['addons_display'][$i]) || $_POST['addons_display'][$i]==NULL) $_POST['addons_display'][$i]="";
                    if (!isset($_POST['addons_titulo'][$i]) || $_POST['addons_titulo'][$i]==NULL) $_POST['addons_titulo'][$i]="";
                    if (!isset($_POST['addons_tipo_titulo'][$i]) || $_POST['addons_tipo_titulo'][$i]==NULL) $_POST['addons_tipo_titulo'][$i]="";
                    if (!isset($_POST['addons_agg_desc'][$i]) || $_POST['addons_agg_desc'][$i]==NULL) $_POST['addons_agg_desc'][$i]="";
                    if (!isset($_POST['addons_descripcion'][$i]) || $_POST['addons_descripcion'][$i]==NULL) $_POST['addons_descripcion'][$i]="";
                    if (!isset($_POST['addons_requerido'][$i]) || $_POST['addons_requerido'][$i]==NULL) $_POST['addons_requerido'][$i]="";
                    if (!isset($_POST['addons_opciones'][$i]) || $_POST['addons_opciones'][$i]==NULL) $_POST['addons_opciones'][$i]="";

                    $addons = array(
                        'addons_id' => $_POST['addons_id'][$i],
                        'addons_tipo' => $_POST['addons_tipo'][$i],
                        'addons_display' => $_POST['addons_display'][$i],
                        'addons_titulo' => $_POST['addons_titulo'][$i],
                        'addons_tipo_titulo' => $_POST['addons_tipo_titulo'][$i],
                        'addons_agg_desc' => $_POST['addons_agg_desc'][$i],
                        'addons_descripcion' => $_POST['addons_descripcion'][$i],
                        'addons_requerido' => $_POST['addons_requerido'][$i],
                        'addons_opciones' => $_POST['addons_opciones'][$i],
                        'addons_producto_id' => $this->input->post('productos_id')
                    );
        
                    $verificar = $this->db->select('addons_id')->where('addons_id',$_POST['addons_id'][$i])->get('addons_productos');
        
                    if ($verificar->num_rows() > 0) {
                        $this->db->where('addons_id', $_POST['addons_id'][$i]);
                        $this->db->update('addons_productos', $addons);
                    }else{
                        $this->db->insert('addons_productos', $addons);
                    }        
                }
            }
            
            $ingresar['result'] = 1;
            $ingresar['mensaje'] = "Guardado con Éxito";

        }else{
            $ingresar['result'] = 0;
            $ingresar['mensaje'] = "No se pudieron guardar los cambios, el slug no puede ser igual al de otro producto.";
        }   

        /*
        if(isset($_POST['productos_imagen_destacada']) && $_POST['productos_imagen_destacada']!==''){
            $carpetaMiniatura='assets/uploads/01_miniatura/';

            $dataMedio = $this->db->where('medios_id', $_POST['productos_imagen_destacada'])->get('medios')->result_array()[0];
            if($dataMedio['medios_url'] === $dataMedio['medios_enlace_miniatura']){
                $fileName = explode('/', $dataMedio['medios_url']);
                
                $rutaOrigen = delBackslashIni($dataMedio['medios_url']);
                $rutaDestino = $carpetaMiniatura.$_POST['productos_imagen_destacada'].'_'.end($fileName);
                
                if (copy($rutaOrigen, $rutaDestino)) {
                    list($ancho, $alto) = getCalculoRedimensionar($rutaDestino, 370);
                    getRedimencionImage($rutaDestino, $ancho, $alto);
                    $this->db->where('medios_id', $_POST['productos_imagen_destacada'])->set(['medios_enlace_miniatura'=>$rutaDestino])->update('medios');
                }                    
            }
        }
        */

        return $ingresar;
    }

    public function verificarVisita($ip=0,$id=0){
        $datos = array();
        if ($ip==0 || $id == 0) {
            $datos['agregado'] = 0;
            return $datos;
        }else{
            $idusuario = 0;
            $this->db->select('productos_visitas_id');
            $this->db->where('productos_visitas_productos_id',$id);
            if (isset($_SESSION['usuarios_id'])) {
                $idusuario = $_SESSION['usuarios_id'];
                $this->db->where('(productos_visitas_usuarios_id='.$_SESSION['usuarios_id'].' OR productos_visitas_ip="'.$ip.'" )');
            }else{
                $this->db->where('productos_visitas_ip',$ip);
            }
            $query = $this->db->get('productos_visitas');
            if ($query->num_rows()==0) {
                $data = array(
                    'productos_visitas_productos_id' => $id,
                    'productos_visitas_usuarios_id' => $idusuario,
                    'productos_visitas_ip' => $ip
                );
                $this->db->insert('productos_visitas', $data);
                
                $this->db->select('productos_visitas,productos_fecha_creacion');
                $this->db->where('productos_id', $id);
                $visita = $this->db->get('productos');
                $visita_num=0;
                $prod_fecha="";
                foreach ($visita->result_array() as $key => $value) {
                    $visita_num=$value['productos_visitas'];
                    $prod_fecha=$value['productos_fecha_creacion'];
                }
                $visita_num++;

                $data = array(
                    'productos_visitas' => $visita_num,
                    'productos_fecha_creacion' => $prod_fecha
                );
                $this->db->where('productos_id', $id);
                $up_prod = $this->db->update('productos', $data);

                $datos['agregado'] = 1;
                return $datos;
            }else{
                $data = array(
                    'productos_visitas_usuarios_id' => $idusuario,
                    'productos_visitas_ip' => $ip,
                    'productos_visitas_fecha' => date('Y-m-d h:i:s')
                );
                if (isset($_SESSION['usuarios_id'])) {
                    $idusuario = $_SESSION['usuarios_id'];
                    $this->db->where('(productos_visitas_usuarios_id='.$_SESSION['usuarios_id'].' OR productos_visitas_ip="'.$ip.'" )');
                }else{
                    $this->db->where('productos_visitas_ip',$ip);
                }
                $this->db->where('productos_visitas_productos_id', $id);
                $this->db->update('productos_visitas', $data);

                $datos['agregado'] = 0;
                return $datos;
            }
        }
    }

    public function getIdsLatestViews(){
        $datos = array();
        $latesIds = array();
        $idusuario = 0;
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SESSION['usuarios_id'])) {
            $idusuario = $_SESSION['usuarios_id'];
        }
        $this->db->select('productos_visitas_productos_id');
        if (isset($_SESSION['usuarios_id'])) {
            $this->db->where('productos_visitas_usuarios_id', $idusuario);
            $this->db->or_where('productos_visitas_ip', $ip);
        }else{
            $this->db->where('productos_visitas_ip', $ip);
        }
        $this->db->order_by('productos_visitas_fecha', 'DESC');
        $this->db->group_by('productos_visitas_productos_id');
        $this->db->limit(10);
        $query = $this->db->get('productos_visitas');
        $cont=0;
        if ($query->num_rows() > 0) {
            $orderby = "";
            foreach ($query->result_array() as $key => $value) {
                $cont++;
                array_push($latesIds, $value['productos_visitas_productos_id']);
                if ($cont==1) {
                    $orderby .= $value['productos_visitas_productos_id'];
                }else{
                    $orderby .= ",".$value['productos_visitas_productos_id'];
                }
            }
            $this->db->select('*');
            $this->db->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left");
            $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","left");
            if (
                isset($_SESSION['municipio_session']) 
                && $_SESSION['municipio_session']!=0 
                && (
                    !isset($_SESSION['tipo_accesos']) 
                    || $_SESSION['tipo_accesos']==6
                    )
                ) {
                $this->db->group_start();
                $this->db->where("productos_envio_local",0);
                $this->db->or_where("productos_envio_local",1);
                $this->db->like("productos_ubicaciones_envio",$_SESSION['departamento_session'].",".$_SESSION['municipio_session']);
                $this->db->group_end();
            }
            $this->db->where("productos_estatus",1);
            $this->db->where("productos_estado_inv",1);
            $this->db->where_in('productos_id',$latesIds);
            $this->db->order_by("FIELD(productos_id, ".$orderby.")");
            $datos = $this->db->get('productos');
        }else{
            $this->db->select('productos_visitas_productos_id');
            $this->db->order_by('productos_visitas_fecha', 'DESC');
            $this->db->group_by('productos_visitas_productos_id');
            $this->db->limit(10);
            $query2 = $this->db->get('productos_visitas');
            if ($query2->num_rows() > 0) {
                $orderby = "";
                foreach ($query2->result_array() as $key => $value) {
                    $cont++;
                    array_push($latesIds, $value['productos_visitas_productos_id']);
                    if ($cont==1) {
                        $orderby .= $value['productos_visitas_productos_id'];
                    }else{
                        $orderby .= ",".$value['productos_visitas_productos_id'];
                    }
                }
                $this->db->select('*');
                $this->db->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left");
                $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","left");
                if (
                    isset($_SESSION['municipio_session']) 
                    && $_SESSION['municipio_session']!=0 
                    && (
                        !isset($_SESSION['tipo_accesos']) 
                        || $_SESSION['tipo_accesos']==6
                        )
                    ) {
                    $this->db->group_start();
                    $this->db->where("productos_envio_local",0);
                    $this->db->or_where("productos_envio_local",1);
                    $this->db->like("productos_ubicaciones_envio",$_SESSION['departamento_session'].",".$_SESSION['municipio_session']);
                    $this->db->group_end();
                }
                $this->db->where("productos_estatus",1);
                $this->db->where("productos_estado_inv",1);
                $this->db->where_in('productos_id',$latesIds);
                $this->db->order_by("FIELD(productos_id, ".$orderby.")");
                $datos = $this->db->get('productos');
            }
        }

        return $datos;
    }

    function getSorting($cant=3, $cat=''){
        $lista=[
            'ultimos'=>[],
            'vendidos'=>[],
            'masvend'=>[]
        ];
        $idConsultado = [];

        $cantRand = $cant * 10;

        //--------------------------------------------------------------

        // Ultimos Productos Creados
        $this->db->order_by("productos_id","DESC");
        if($cat!=='') $this->db->like("productos_categorias", "/,/".$cat."/,/");
        $prod = $this->db->limit($cantRand)->get('productos')->result_array();
        $ids=[];
        foreach($prod as $idprod) $ids[]=$idprod['productos_id'];

        if(count($ids)>0){
            $resultSQL = $this->db->select("productos_id")->order_by('rand()')->where_in('productos_id', $ids)->limit($cant)->get('productos')->result_array();
            foreach($resultSQL as $pro) $idConsultado[] = $pro['productos_id'];
        }
        
        $lista['ultimos'] = $idConsultado;

        //--------------------------------------------------------------
        //Ultimos Vendidos
        /*
        SELECT DISTINCT(alma_pedidos_detalle.pedidos_detalle_producto) AS producto_id, productos.productos_titulo
        FROM alma_pedidos
        INNER JOIN alma_pedidos_detalle ON alma_pedidos_detalle.pedidos_detalle_pedidos_id = alma_pedidos.pedidos_id
        INNER JOIN productos ON productos.productos_id = alma_pedidos_detalle.pedidos_detalle_producto
        WHERE alma_pedidos.pedidos_estatus IN ('Enviado','En preparación','Cancelado')
        ORDER BY alma_pedidos_detalle.pedidos_detalle_producto DESC
        LIMIT 10
        */

        $this->db->distinct()->select('alma_pedidos_detalle.pedidos_detalle_producto')
                 ->join("alma_pedidos_detalle", "pedidos_detalle_pedidos_id = alma_pedidos.pedidos_id", "left")
                 ->join("productos", "productos.productos_id = alma_pedidos_detalle.pedidos_detalle_producto", "left")
                 ->where_in('alma_pedidos.pedidos_estatus', ['Enviado','En preparación','Cancelado'])
                 ->order_by("alma_pedidos_detalle.pedidos_detalle_producto","RANDOM")
                 ->where_not_in('alma_pedidos_detalle.pedidos_detalle_producto', $idConsultado)
                 ->limit($cantRand);
        if($cat!=='') $this->db->like("productos.productos_categorias", "/,/".$cat."/,/");

        $ids=[];
        $resultSQL = $this->db->get('alma_pedidos')->result_array();
        //$lista['sql1'] = ""; //$this->db->last_query();

        foreach($resultSQL as $pro) $ids[] = $pro['pedidos_detalle_producto'];

        if(count($ids)>0){
            $resultSQL = $this->db->select("productos_id")->order_by('rand()')->where_in('productos_id', $ids)->limit($cant)->get('productos')->result_array();
            foreach($resultSQL as $pro){
                $lista['vendidos'][] = $pro['productos_id'];
                $idConsultado[] = $pro['productos_id'];
            }  
        }         

        //--------------------------------------------------------------
        //Vendidos los Ultimos 30 Dias
        /*
        SELECT DISTINCT(alma_pedidos_detalle.pedidos_detalle_producto) AS producto_id, productos.productos_titulo
        FROM alma_pedidos
        INNER JOIN alma_pedidos_detalle ON alma_pedidos_detalle.pedidos_detalle_pedidos_id = alma_pedidos.pedidos_id
        INNER JOIN productos ON productos.productos_id = alma_pedidos_detalle.pedidos_detalle_producto
        WHERE alma_pedidos.pedidos_estatus IN ('Enviado','En preparación','Cancelado')
              AND alma_pedidos.pedidos_fecha_creacion BETWEEN '2022-06-01 0:00:00' AND '2022-06-30 23:59:59'
        ORDER BY alma_pedidos_detalle.pedidos_detalle_producto DESC
        LIMIT 10
        */

        $ffin = '2022-11-01 23:59:59';
        //$ffin = date('Y-m-d').' 23:59:59';
        $fini = date("Y-m-d",strtotime($ffin."-6 month")).' 00:00:00'; 

        $this->db->distinct()->select('alma_pedidos_detalle.pedidos_detalle_producto')
                 ->join("alma_pedidos_detalle", "pedidos_detalle_pedidos_id = alma_pedidos.pedidos_id", "left")
                 ->join("productos", "productos.productos_id = alma_pedidos_detalle.pedidos_detalle_producto", "left")
                 ->where_in('alma_pedidos.pedidos_estatus', ['Enviado','En preparación','Cancelado'])
                 ->where("alma_pedidos.pedidos_fecha_creacion BETWEEN '".$fini."' AND '".$ffin."'")
                 ->where_not_in('alma_pedidos_detalle.pedidos_detalle_producto', $idConsultado)
                 ->order_by("alma_pedidos_detalle.pedidos_detalle_producto","RANDOM")
                 ->limit(100);
        if($cat!=='') $this->db->like("productos.productos_categorias", "/,/".$cat."/,/");

        $ids=[];
        $resultSQL = $this->db->get('alma_pedidos')->result_array();
        //$lista['sql2'] = "";//$this->db->last_query();
        foreach($resultSQL as $pro) $ids[] = $pro['pedidos_detalle_producto']; 

        if(count($ids)>0){
            $resultSQL = $this->db->select("productos_id")->order_by('rand()')->where_in('productos_id', $ids)->limit($cant)->get('productos')->result_array();
            foreach($resultSQL as $pro){
                $lista['masvend'][] = $pro['productos_id'];
                $idConsultado[] = $pro['productos_id'];
            } 
        }        

        return $lista;
    }

    function getProductsVideoByCategory($idcat){
        $prod = $this->db->like('productos_categorias', '/,/'.$idcat.'/,/', 'both', false)
                          ->where("productos_video<>''")
                          ->get('productos')->result_array();
        return $prod;
    }

}
