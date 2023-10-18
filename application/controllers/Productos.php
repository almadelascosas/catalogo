<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Productos extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('productos');
        $this->load->helper(array('commun'));
        $this->load->library('phpmailing');
        $this->load->model("categorias_model");
        $this->load->model("medios_model");
        $this->load->model("productos_model");
        $this->load->model("usuarios_model");
        $this->load->model("municipios_model");
    }

    public function generadorSlug(){

      $this->db->select("productos_id,productos_titulo,productos_fecha_creacion");
      $productos = $this->db->get("productos");

      $modify_slug = array();
      $cont = 0;
      foreach ($productos->result_array() as $key => $value) {
        $cont++;
        $slug = limpiarUri($value['productos_titulo'])."-".$value['productos_id'];

        array_push($modify_slug,array(
          "productos_id" => $value['productos_id'],
          "productos_fecha_creacion" => $value['productos_fecha_creacion'],
          "productos_slug" => $slug,
        ));

      }

      $this->db->update_batch('productos', $modify_slug, 'productos_id');

    }

    public function busqueda(){
       if(isset($_POST['btnBuscar'])){
        if (isset($_POST['productos_estatus']) && $_POST['productos_estatus']!=='') $filtros['where_arr']["productos_estatus"] = $_POST['productos_estatus']; 
        if (isset($_POST['productos_estado_inv']) && $_POST['productos_estado_inv']!=='') $filtros['where_arr']["productos_estado_inv"] = $_POST['productos_estado_inv'];  
        if (isset($_POST['usuarios_id']) && $_POST['usuarios_id']!=='')  $filtros['where_arr']["usuarios_id"] = $_POST['usuarios_id']; 
        if (intval($_SESSION['tipo_accesos'])!==0 && intval($_SESSION['tipo_accesos'])!==1) $filtros['where_arr']['usuarios_id']=$_SESSION['usuarios_id'];
        if (isset($_POST['search']) && $_POST['search']!=='') $filtros['where_arr']["productos_titulo"] = $_POST['search'];                
      }

      if(isset($_POST['btnLimpiar'])){
        unset($_SESSION['filtros']);
        if (intval($_SESSION['tipo_accesos'])!==0 && intval($_SESSION['tipo_accesos'])!==1) $filtros['where_arr']['usuarios_id']=$_SESSION['usuarios_id'];
      }

      $filtros['orderby'] = array();
      $filtros['orderby'][0] = "productos_fecha_creacion";
      $filtros['orderby'][1] = "DESC";

      $_SESSION['filtros']=$filtros;

      redirect(base_url('productos'));
    }

    public function index()
    {
      $filtros_user = [];
      $filtros_user['where'] = array("tipo_accesos", [1,8]);
      $page_user = 1;
      $limite_user = [500,0];
      $vendedores=[];

      $resp = $this->usuarios_model->getAll($filtros_user, $page_user, $limite_user);
      foreach($resp->result_array() as $data) $vendedores[] = $data;

      $datos = [
        'view' => "productos/index",
        'css_data' => [],
        'js_data' => [
            'assets/js/pages/productos/index.js?'.rand(),
          ],
        'vendedores' => $vendedores,
        'productos' => [],
        'categorias' => []

      ];

      $filtros = array();

      /*
      if (intval($_SESSION['tipo_accesos'])!==0 && intval($_SESSION['tipo_accesos'])!==1){
        if(intval($_SESSION['tipo_accesos'])===8){
          $filtros['where_arr']['usuarios_id']=1;
        }else{
          $filtros['where_arr']['usuarios_id']=$_SESSION['usuarios_id'];
        }
      }
      */

      if (intval($_SESSION['tipo_accesos'])!==0 && intval($_SESSION['tipo_accesos'])!==1) $filtros['where_arr']['usuarios_id']=$_SESSION['usuarios_id'];

      $page = 1;
      $limit = 12;
      $limite = array();
      if (!isset($_GET['page'])) {
        $limite=array($limit,$page-1);
      }else{
        $page = $_GET['page']-1;
        $paginado = $limit*$page;
        $limite=array($limit,$paginado);
      }

      if(isset($_SESSION['filtros'])){
        $filtros = $_SESSION['filtros'];
      }else{
        $filtros['orderby'] = array();
        $filtros['orderby'][0] = "productos_fecha_creacion";
        $filtros['orderby'][1] = "DESC";
      }       

      $datos['productos'] = $this->productos_model->getAll($filtros,$page,$limite);      

      $categorias = array();

      foreach ($datos['productos']->result_array() as $key => $value) {
        $cat = explode("/,/",$value['productos_categorias']);
        for ($i=0; $i < count($cat); $i++) {
          if (isset($cat[$i]) && $cat[$i]!="" && $cat[$i]!=0) {
            array_push($categorias, $cat[$i]);
          }
        }
      }

      $this->db->select("*");
      if ($categorias!=array()){
        $this->db->where_in("categorias_id",$categorias);
      }else{
        $this->db->where_in("categorias_id",0);
      }
      $datos['categorias'] = $this->db->get("categorias");

      $this->load->view('normal_view', $datos);
    }

    public function descuentoProd(){
      if (isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']==9) {
        $productos = $this->db->select("productos_id,productos_categorias,productos_precio,productos_fecha_creacion")
        ->where("productos_vendedor",1)
        ->or_where("productos_vendedor",2)->get("productos");
        $modify = array();
        foreach ($productos->result_array() as $key => $value) {
          $precio_oferta = $value["productos_precio"]/100;
          $precio_oferta = $precio_oferta*19;
          $precio_oferta = $value["productos_precio"]-$precio_oferta;

          
          $producto_categoria = $value["productos_categorias"]."816/,/";
          
          array_push($modify, array(
            "productos_id" => $value["productos_id"],
            "productos_fecha_creacion" => $value["productos_fecha_creacion"],
            "productos_precio_oferta" => $precio_oferta,
            "productos_categorias" => $producto_categoria
          ));
        }

        $this->db->update_batch('productos', $modify, 'productos_id');

      }
    }

    public function AddVentas(){
      
      if (isset($_SESSION['usuarios_id']) && $_SESSION['usuarios_id']==9) {
        $productos_fech = $this->db->select("productos_id,productos_fecha_creacion")->get("productos");
        $this->db->select("pedidos_detalle_producto,pedidos_detalle_producto_cantidad");
        $pedidos = $this->db->get("alma_pedidos_detalle");
        $count_productos = array();
        $productos_cons = array(0);
        foreach ($pedidos->result_array() as $key => $value) {
          $prod = explode(",",$value['pedidos_detalle_producto']);
          $prod_cont = explode(",",$value['pedidos_detalle_producto_cantidad']);
          for ($i=0; $i < count($prod); $i++) {
            $existe = 0;
            for ($i2=0; $i2 < count($count_productos); $i2++) {
              if ($prod[$i] == $count_productos[$i2]["productos_id"]) {
                $existe = 1;
                $count_productos[$i2]["ventas"] = $count_productos[$i2]["ventas"]+floatval($prod_cont[$i]);
              }
            }
            if ($existe==0) {
              $fecha = "";
              foreach ($productos_fech->result_array() as $key43 => $value43) {
                if ($prod[$i]==$value43['productos_id']) {
                  $fecha = $value43['productos_fecha_creacion'];
                }
              }
              array_push($count_productos, array(
                "productos_id" => $prod[$i],
                "productos_fecha_creacion" => $fecha,
                "ventas" => 1
              ));
              array_push($productos_cons,$prod[$i]);
            }
          }
        }
        $this->db->update_batch('productos', $count_productos, 'productos_id');

        $productos_nuevos = $this->db->select("productos_id")->order_by("productos_fecha_creacion","DESC")->get("productos");
        $productos_vendidos = $this->db->select("productos_id")->order_by("ventas","DESC")->get("productos");
        $productos_visitas = $this->db->select("productos_id")->order_by("productos_visitas","DESC")->get("productos");
        
        $cont = 0;
        $orderprod = array();
        foreach ($productos_nuevos->result_array() as $key => $value) {
          if (!in_array($value['productos_id'],$orderprod)) {
            array_push($orderprod,$value['productos_id']);
            $cont++;
            $vendidos_agg = 0;
            
            foreach ($productos_vendidos->result_array() as $key2 => $value2) {
              if (!in_array($value2['productos_id'],$orderprod) && $vendidos_agg==0) {
                array_push($orderprod,$value2['productos_id']);
                $vendidos_agg=1;
                $visitas_agg=0;
                $cont++;

                foreach ($productos_visitas->result_array() as $key3 => $value3) {
                  if (!in_array($value3['productos_id'],$orderprod) && $visitas_agg==0) {
                    array_push($orderprod,$value3['productos_id']);
                    $visitas_agg = 1;
                    $cont++;
                  } 
                }

              }
            }

          }
        }

        $data = array();

        $this->db->empty_table('productos_order_defecto');

        for ($i=0; $i < count($orderprod); $i++) {
          array_push($data, array(
            "productos" => $orderprod[$i]
          ));
        }
        
        $this->db->insert_batch('productos_order_defecto', $data);

      }
    }

    public function agregar()
    {
      $datos = [
        'view' => "productos/add",
        'css_data' => [
              'assets/plugins/dropify/dropify.min.css',
              'assets/css/pages/productos/style.css?'.rand()
            ],
        'js_data' => [
              'assets/plugins/dropify/dropify.min.js',
              'assets/js/pages/productos/add.js?'.rand()
            ],
        'categorias' => $this->categorias_model->getAll(),
        'addons_producto' => NULL,
        'producto' => $this->productos_model->vacio(),
        'productos_relacionados' => ""
      ];
      
      if (intval($_SESSION['tipo_accesos'])===1 || intval($_SESSION['tipo_accesos'])===0) {
        $filtros_user = array();
        $filtros_user['where'] = array("tipo_accesos",8);
        $page_user = 1;
        $limite_user = array(0,1000);
        $datos['usuarios'] = $this->usuarios_model->getAll($filtros_user, $page_user, $limite_user);
      }
      
      $this->load->view('normal_view', $datos);
    }

    public function editar($id=0,$nombre="")
    {
      $datosProducto = $this->productos_model->single($id);

      $productos_relacionados = array(0);
      if ($datosProducto['productos_relacionados']!="")  $productos_relacionados = explode(",", $datosProducto['productos_relacionados']);
      $this->db->select("productos_id,productos_titulo");
      for ($i=0; $i < count($productos_relacionados); $i++) {
        if ($i==0) {
          $this->db->where("productos_id",$productos_relacionados[$i]);
        }else{
          $this->db->or_where("productos_id",$productos_relacionados[$i]);
        }
      }
      $prodRelacionados = $this->db->get("productos");

      if ($_SESSION['tipo_accesos']==1 || $_SESSION['tipo_accesos']==0) {
        $filtros_user = array();
        $filtros_user['where'] = array("tipo_accesos",8);
        $page_user = 1;
        $limite_user = array(10000,0);
        $usuarios = $this->usuarios_model->getAll($filtros_user,$page_user,$limite_user);
      }

      $imagenes = explode("/,/",$datosProducto['productos_imagenes']);
      $imagenes = array_diff($imagenes, array("",0,null));
      foreach($imagenes as $key => $value) getMiniaturaProduct($value);

      $datos = [
        'view' => "productos/add",
        'css_data' => [ 'assets/plugins/dropify/dropify.min.css'],
        'js_data' => [
            'assets/plugins/dropify/dropify.min.js',
            'assets/js/pages/productos/add.js?'.rand()
          ],
        'categorias' => $this->categorias_model->getAll(),
        'medios' => $this->medios_model->getAll(),
        'producto' => $datosProducto,
        'mdMuni' => $this->municipios_model,
        'productos_relacionados' => $prodRelacionados,
        'addons_producto' => $this->productos_model->addons($id),
        'usuarios' => $usuarios,
        'imagenes' => $this->medios_model->get_in("medios_id",$imagenes),
        'imagen_destacada' => ($datosProducto['productos_imagen_destacada']!="" && $datosProducto['productos_imagen_destacada']!=0) ? $this->medios_model->single($datosProducto['productos_imagen_destacada']) : ""
      ]; 
      
      $this->load->view('normal_view', $datos);
    }

    public function guardar(){
      
      $datos = array();
      if ($this->input->post('productos_id')!=="" and $this->input->post('productos_id')!==0) {
        $ingresar = $this->productos_model->edit();
        $return = base_url("productos/editar/".$this->input->post('productos_id')."/mensaje");
      }else {
        $ingresar = $this->productos_model->save();
        $return = base_url("productos/agregar");
      }

      if (intval($ingresar['result'])===1) {
        $this->session->set_flashdata('success', $ingresar['mensaje']);
      }else {
        $this->session->set_flashdata('error', $ingresar['mensaje']);
      }
      redirect($return, $datos);
    }
    
    public function delete()
    {
      $datos = array();
      $id=$this->input->post("id");
      $query = $this->db->delete('productos', array('productos_id' => $id));
      /* 
      if ($query) {
          $this->session->set_userdata('message_tipo', "success");
          $this->session->set_userdata('message', "Guardado Éxitoso");
          redirect(base_url()."usuarios", $datos);
      }else {
          $this->session->set_userdata('message_tipo', "error");
          $this->session->set_userdata('message', "Error al registrar.");
          redirect(base_url()."usuarios", $datos);
      }*/
      if ($query) {
        $datos['mensaje']="Eliminado con éxito";
        $datos['result']=1;
      }else {
        $datos['mensaje']="Hubo un error, intente de nuevo!";
        $datos['result']=0;
      }
      echo json_encode($datos);
    }
    public function subirvideo()
    {

      ini_set('post_max_size','100M');  // Tamaño máximo de datos enviados por método POST.

      ini_set('upload_max_filesize','100M');   // Tamaño máximo para subir archivos al servidor.

      ini_set('max_execution_time','0');  // Tiempo máximo de ejecución de éste script en segundos.

      ini_set('max_input_time','0'); /*Tiempo máximo en segundos que el script puede usar 
      para analizar los datos input, sean post,get o archivos.*/

      ini_set("memory_limit" , "100M") ; /*Tamaño máximo que el script puede usar de la memoria, mientras se ejecuta.*/

      set_time_limit(0);

      $datos = array();
      $nombre_fichero =  $_SERVER['DOCUMENT_ROOT']."/assets/uploads/videosproductos/".limpiarUri(date("m-Y"));

      if (file_exists($nombre_fichero)) {
        if (move_uploaded_file($_FILES["productos_video_upload"]["tmp_name"], $nombre_fichero."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video_upload']['name']))) {
              $datos["medios_url"] = "assets/uploads/videosproductos/".limpiarUri(date("m-Y"))."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video_upload']['name']);
              $nombreEx = explode(".",$_FILES["productos_video_upload"]["name"]);
              $datos["medios_titulo"] = "";
              for ($i=0; $i < count($nombreEx); $i++) {
                  if ($i!=count($nombreEx)-1) {
                      $datos["medios_titulo"] .= $nombreEx[$i];
                  }
              }
              $datos["medios_user"] = $_SESSION['usuarios_id'];
              $datos["success"] = 1;

          } else {
              $datos["success"] = 0;
          }
      } else {
          mkdir($_SERVER['DOCUMENT_ROOT']."/assets/uploads/videosproductos/".limpiarUri(date("m-Y")), 0777);
          if (move_uploaded_file($_FILES["productos_video_upload"]["tmp_name"], $nombre_fichero."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video_upload']['name']))) {
              $datos["medios_url"] = "assets/uploads/videosproductos/".limpiarUri(date("m-Y"))."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['productos_video_upload']['name']);
              $nombreEx = explode(".",$_FILES["productos_video_upload"]["name"]);
              $datos["medios_titulo"] = "";
              for ($i=0; $i < count($nombreEx); $i++) {
                  if ($i!=count($nombreEx)-1) {
                      $datos["medios_titulo"] .= $nombreEx[$i];
                  }
              }
              $datos["medios_user"] = $_SESSION['usuarios_id'];
              $datos["success"] = 1;
          } else {
              $datos["success"] = 0;
          }
      }
      echo json_encode($datos);
    }

    public function buscarProductos($clave=""){
      $datos = array();
      if ($clave!="") {
        $this->db->select("productos_id,productos_titulo");
        $this->db->like("productos_titulo", $clave);
        if (isset($_POST['productos']) && $_POST['productos']!="") {
          $productos = explode(",",$_POST['productos']);
          for ($i=0; $i < count($productos); $i++) {
            $this->db->where("productos_id!=", $productos[$i]);
          }
        }
        $datos['productos'] = $this->db->get("productos");

        $this->load->view('themes/admin/productos/productos_relacionados', $datos);

      }
    }

}
