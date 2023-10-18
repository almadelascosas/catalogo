<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Tienda extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('tienda');
        $this->load->helper(array('commun'));
        $this->load->model("productos_model");
        $this->load->model("usuarios_model");
        $this->load->model("categorias_model");
        $this->load->model("medios_model");
        $this->load->model("mailing_model", 'mdMailing');
    }

    public function index()
    {

      $datos = array();
      $datos['vista'] = "front";
      $datos['title'] = "Tienda - Alma de las Cosas";
      $page=1;
      if (isset($_REQUEST['page']) && $_REQUEST['page']!=NULL) {
        $page = $_REQUEST['page'];
      }

      

      $filtros = array();
      $filtros['where_arr'] = array(
        'productos_estatus' => 1
      );

      if(isset($_REQUEST['search'])){
        $filtros['like'][0]='productos_titulo';
        $filtros['like'][1]=$_REQUEST['search'];
      }

      if (isset($_REQUEST['orderby']) && ($_REQUEST['orderby']!=NULL && $_REQUEST['orderby']!="" && $_REQUEST['orderby']!=array())) {
        $filtros['orderby'] = array();
        if ($_REQUEST['orderby']=="sale") {
          $filtros['orderby'][0] = "ventas";
          $filtros['orderby'][1] = "DESC";
        }
        if ($_REQUEST['orderby']=="date") {
          $filtros['orderby'][0] = "productos_fecha_creacion";
          $filtros['orderby'][1] = "DESC";
        }
        if ($_REQUEST['orderby']=="price_low") {
          $filtros['orderby'][0] = "productos_precio";
          $filtros['orderby'][1] = "ASC";
        }
        if ($_REQUEST['orderby']=="price_high") {
          $filtros['orderby'][0] = "productos_precio";
          $filtros['orderby'][1] = "DESC";
        }
      }
      
      $datos['productos'] = $this->productos_model->getAll($filtros,$page);

      /*  --------------------------------------------------------------------- */

      $listaProductos = $datos['productos']->result_array();

      $sorting = $this->productos_model->getSorting(3);
      for($p=0 ; $p<3 ; $p++){
        $new = (isset($sorting['ultimos'][$p])) ? $sorting['ultimos'][$p] : 0;
        $ven = (isset($sorting['vendidos'][$p])) ? $sorting['vendidos'][$p] : 0;
        $cal = (isset($sorting['masvend'][$p])) ? $sorting['masvend'][$p] : 0;
        
        $posicion=0;
        foreach($listaProductos  as $key => $pro):
          getMiniaturaProduct($pro['productos_imagen_destacada']);
          if($ven!==0){
            if(intval($pro['productos_id']) === intval($ven)){
              $listaProductos[$key]['sorting']='caliente';
              $listaProductos = reposicionarArray($listaProductos, $key, $posicion);
              $posicion++;
            }
          }
          
          if($cal!==0){
            if(intval($pro['productos_id']) === intval($cal)){
              $listaProductos[$key]['sorting']='vendido';
              $listaProductos = reposicionarArray($listaProductos, $key, $posicion);
              $posicion++;
            }
          }

          if($new!==0){
            if(intval($pro['productos_id']) === intval($new)){
              $listaProductos[$key]['sorting']='nuevo';
              $listaProductos = reposicionarArray($listaProductos, $key, $posicion);
              $posicion++;
            }
          }
        endforeach;
      }
      $datos['productos'] = $listaProductos;

        /* ---------------------------------------------------------------------------------------- */

      $filtro_cat = array();

      $filtro_cat['where_arr'] = array(
        'categorias_status' => 1,
        'categorias_padre' => 0
      );

      $datos['categorias'] = $this->categorias_model->getAll($filtro_cat);
      $datos['preciosFiltro'] = $this->productos_model->maxminPrice();
      $datos['view'] = "tienda/index";

      $datos['js_data'] = array(
        'assets/js/plugins/nouislider/nouislider.min.js?v='.rand(99,9999),
        'assets/js/front/tienda/index.js?v='.rand(99,9999),
      );
      $datos['css_data'] = array(
        'assets/js/plugins/nouislider/nouislider.min.css?v='.rand(99,9999),
      );
      $this->load->view('normal_view',$datos);
    }

    public function categorias($cat=0, $name_cat=""){
      $datos = array();
      $datos['vista'] = "front";
      
      if ($cat!=0) {
        $page=1;
        if (!isset($_REQUEST['productos_categorias'])) {
          $_REQUEST['productos_categorias'] = array();
          array_push($_REQUEST['productos_categorias'], $cat);
        }
        if (isset($_REQUEST['page']) && $_REQUEST['page']!=NULL) {
          $page = $_REQUEST['page'];
        }
        $filtros = array();
        $filtros['where_arr'] = array(
          'productos_estado_inv' => 1,
          'productos_estatus' => 1
        );
        if (isset($_REQUEST['orderby']) && ($_REQUEST['orderby']!=NULL && $_REQUEST['orderby']!="" && $_REQUEST['orderby']!=array())) {
          $filtros['orderby'] = array();
          if ($_REQUEST['orderby']=="sale") {
            $filtros['orderby'][0] = "ventas";
            $filtros['orderby'][1] = "DESC";
          }
          if ($_REQUEST['orderby']=="date") {
            $filtros['orderby'][0] = "productos_fecha_creacion";
            $filtros['orderby'][1] = "DESC";
          }
          if ($_REQUEST['orderby']=="price_low") {
            $filtros['orderby'][0] = "productos_precio";
            $filtros['orderby'][1] = "ASC";
          }
          if ($_REQUEST['orderby']=="price_high") {
            $filtros['orderby'][0] = "productos_precio";
            $filtros['orderby'][1] = "DESC";
          }
        }else{
          $filtros['orderby'][0] = "ventas";
          $filtros['orderby'][1] = "DESC";
        }
        
        $datos['productos'] = $this->productos_model->getAll($filtros,$page);

        /*  --------------------------------------------------------------------- */

        $listaProductos = $datos['productos']->result_array();

        $cantSorting = 3;
        $sorting = $this->productos_model->getSorting($cantSorting, $cat);

        for($p=0 ; $p<$cantSorting ; $p++){
          $new = (isset($sorting['ultimos'][$p])) ? $sorting['ultimos'][$p] : 0;
          $ven = (isset($sorting['vendidos'][$p])) ? $sorting['vendidos'][$p] : 0;
          $cal = (isset($sorting['masvend'][$p])) ? $sorting['masvend'][$p] : 0;
          
          $posicion=0;
          foreach($listaProductos  as $key => $pro):
            //getMiniaturaProduct($pro['productos_imagen_destacada']);
            if($ven!==0){
              if(intval($pro['productos_id']) === intval($ven)){
                $listaProductos[$key]['sorting']='caliente';
                $listaProductos = reposicionarArray($listaProductos, $key, $posicion);
                $posicion++;
              }
            }
            
            if($cal!==0){
              if(intval($pro['productos_id']) === intval($cal)){
                $listaProductos[$key]['sorting']='vendido';
                $listaProductos = reposicionarArray($listaProductos, $key, $posicion);
                $posicion++;
              }
            }

            if($new!==0){
              if(intval($pro['productos_id']) === intval($new)){
                $listaProductos[$key]['sorting']='nuevo';
                $listaProductos = reposicionarArray($listaProductos, $key, $posicion);
                $posicion++;
              }
            }
          endforeach;
        }
        $datos['productos'] = $listaProductos;

        /* ---------------------------------------------------------------------------------------- */

        $filtro_cat = array(
          'where_arr' => array(
            'categorias_padre' => $cat,
            'categorias_status' => 1
          )
        );
        
        $datos['categorias'] = $this->categorias_model->getAll($filtro_cat);
        $datos['categoria_padre'] = $this->categorias_model->single($cat);
        if ($datos['categoria_padre']['categorias_titulo_seo']!="") {
          $datos['title'] = $datos['categoria_padre']['categorias_titulo_seo'];
        }else{
          $datos['title'] = $datos['categoria_padre']['categorias_nombre']." - Alma de las Cosas";
        }
        if ($datos['categoria_padre']['categorias_meta_descripcion']!="") {
          $datos['descripcion'] = $datos['categoria_padre']['categorias_meta_descripcion'];
        }
        $datos['preciosFiltro'] = $this->productos_model->maxminPrice();
        $datos['view'] = "tienda/index";
        
        $datos['js_data'] = array(
          'assets/js/plugins/nouislider/nouislider.min.js?'.rand(),
          'assets/js/front/tienda/index.js?'.rand(),
        );
        $datos['css_data'] = array(
          'assets/js/plugins/nouislider/nouislider.min.css?'.rand()
        );
        
        $this->load->view('normal_view',$datos);
      }else{
        redirect(base_url()."tienda", $datos);
      }

    }

    public function vendor($vendor_id=0,$vendor_name=""){
      $datos = array();
      $datos['vista'] = "front";
      $datos['title'] = $vendor_name." - Alma de las Cosas";
      if ($vendor_id!=0) {
        $page=1;
        if (isset($_REQUEST['page']) && $_REQUEST['page']!=NULL) {
          $page = $_REQUEST['page'];
        }
        $filtros = array();
        $filtros['where_arr'] = array(
          'productos_estado_inv' => 1,
          "productos_vendedor" => $vendor_id,
          'productos_estatus' => 1
        );
        if (isset($_REQUEST['orderby']) && ($_REQUEST['orderby']!=NULL && $_REQUEST['orderby']!="" && $_REQUEST['orderby']!=array())) {
          $filtros['orderby'] = array();
          if ($_REQUEST['orderby']=="sale") {
            $filtros['orderby'][0] = "ventas";
            $filtros['orderby'][1] = "DESC";
          }
          if ($_REQUEST['orderby']=="date") {
            $filtros['orderby'][0] = "productos_fecha_creacion";
            $filtros['orderby'][1] = "DESC";
          }
          if ($_REQUEST['orderby']=="price_low") {
            $filtros['orderby'][0] = "productos_precio";
            $filtros['orderby'][1] = "ASC";
          }
          if ($_REQUEST['orderby']=="price_high") {
            $filtros['orderby'][0] = "productos_precio";
            $filtros['orderby'][1] = "DESC";
          }
        }
        
        $datos['productos'] = $this->productos_model->getAll($filtros,$page);
        $datos['vendor'] = $this->usuarios_model->single($vendor_id);
        $filtro_cat = array(
          'where_arr' => array(
            'categorias_padre' => 0,
            'categorias_status' => 1
          )
        );
        $datos['categorias'] = $this->categorias_model->getAll($filtro_cat);
        
        $datos['preciosFiltro'] = $this->productos_model->maxminPrice();
        $datos['view'] = "tienda/index";
        $datos['js_data'] = array(
          'assets/js/plugins/nouislider/nouislider.min.js?v='.rand(99,9999),
          'assets/js/front/tienda/index.js?v='.rand(99,9999),
        );
        $datos['css_data'] = array(
          base_url().'assets/js/plugins/nouislider/nouislider.min.css?v='.rand(99,9999),
        );
        
        $this->load->view('normal_view',$datos);
      }else{
        redirect(base_url()."tienda", $datos);
      }

    }

    public function mostrarMas(){
      $datos = array();
      $filtros = array();
      $page=1;
      if (isset($_REQUEST['page']) && $_REQUEST['page']!=NULL) {
        $page = $_REQUEST['page'];
      }
      $filtros = array();
      $filtros['where'] = array('productos_estado_inv', 1);
      if (isset($_REQUEST['orderby']) && ($_REQUEST['orderby']!=NULL && $_REQUEST['orderby']!="" && $_REQUEST['orderby']!=array())) {
        $filtros['orderby'] = array();
        if ($_REQUEST['orderby']=="sale") {
          $filtros['orderby'][0] = "ventas";
          $filtros['orderby'][1] = "DESC";
        }
        if ($_REQUEST['orderby']=="date") {
          $filtros['orderby'][0] = "productos_fecha_creacion";
          $filtros['orderby'][1] = "DESC";
        }
        if ($_REQUEST['orderby']=="price_low") {
          $filtros['orderby'][0] = "productos_precio";
          $filtros['orderby'][1] = "ASC";
        }
        if ($_REQUEST['orderby']=="price_high") {
          $filtros['orderby'][0] = "productos_precio";
          $filtros['orderby'][1] = "DESC";
        }
      }
      $datos['productos'] = $this->productos_model->getAll($filtros,$page);
      $this->load->view('themes/modern/tienda/vermas',$datos);

    }

    public function single($id, $titulo="")
    {

      $ip = $_SERVER['REMOTE_ADDR'];
      $registrarVisita = $this->productos_model->verificarVisita($ip, $id);

      $producto = $this->productos_model->single($id);
      $categorias = explode("/,/", $producto['productos_categorias']);
      $imagenes = explode("/,/", $producto['productos_imagenes']);
      foreach($imagenes as $key => $idimg) getMiniaturaProduct($idimg);

      $metodos=[];
      $query = $this->db->get("metodos_pagos");
      foreach ($query->result_array() as $key => $value) {
          array_push($metodos, array(
              "metodo_id" => $value['metodos_pagos_id'],
              "metodo_titulo" => $value['metodos_pagos_titulo'],
              "metodo_imagen" => base_url().$value['metodos_pagos_imagen']
          )); 
      }

      $datos = [
        'metodos' => [],
        'producto' => $producto,
        'title' => $producto['productos_titulo']." - Alma de las Cosas",
        'productos_relacionados' => $this->productos_model->relacionados($producto['productos_relacionados']),
        'categorias' => $this->db->select("categorias_id,categorias_nombre")->where_in("categorias_id",$categorias)->get("categorias"),
        'addons_producto' => $this->productos_model->addons($producto['productos_id']),
        'imagenes' => $this->medios_model->get_in("medios_id", $imagenes),
        'metodos' => $metodos,
        'ogfb' => true,
        'ogfbimage' => $producto['medios_url'],
        'ogfbdetalle' => $producto['productos_descripcion_corta'],
        'css_data' => [
            'assets/css/pages/front/single/index.css',
            'assets/owlcarousel/assets/owl.theme.default.min.css',
            'assets/owlcarousel/assets/owl.carousel.min.css',
            'assets/plugins/snackbar/snackbar.min.css',
            'assets/css/style-calendar.css?'.rand(),
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'
          ],
        'js_data' => [
            'assets/owlcarousel/owl.carousel.min.js',
            'assets/plugins/snackbar/snackbar.min.js',
            'assets/js/front/home/index.js?'.rand(),
            'assets/js/front/single/index_new.js?'.rand(),
            'assets/js/main-calendar.js?'.rand(),
            
          ],
        'vista' => "front",
        'view' => "tienda/single",
        'dataPixel' => 'viewContent'
      ];
      
      $this->load->view('normal_view',$datos);
    }


    public function reel($id,$titulo)
    {

      $datos = array();
      $datos['producto'] = $this->productos_model->single($id);
      $datos['title'] = $datos['producto']['productos_titulo']." - Alma de las Cosas";
      $datos['css_data'] = array(
          'assets/fullpage/dist/fullpage.min.css?'.rand(),
          'assets/css/pages/front/reel/index.css?'.rand(),
      );
      $datos['js_data'] = array(
        'assets/fullpage/dist/fullpage.min.js?'.rand(),
        'assets/js/front/reel/video.min.js',
        'assets/js/front/reel/index.js?v='.rand(99,9999),
      );
      $datos['vista'] = "front";
      $datos['view'] = "tienda/reel";
      $this->load->view('normal_view',$datos);
    }
    public function nextReel()
    {

      $datos = array();
      $id = $this->input->post("idActual");
      $datos['producto'] = $this->productos_model->singleNext($id);
      $this->load->view('themes/modern/tienda/reel/next',$datos);
    }
    public function modalInfo()
    {

      $datos = array();
      $id = $this->input->post("id");
      $datos['producto'] = $this->productos_model->single($id);
      $this->load->view('themes/modern/tienda/reel/modal',$datos);
    }

    public function addcart($idprod=0){
      $datos = array();
      if ($idprod!=0) {
        $datos["result"] = 0; 
        $datos["mensaje"] = "Error al agregar"; 
        if (!isset($_SESSION["cart"]))  $_SESSION["cart"] = [];
        

        $agg = 1;
        /*
        $this->db->select("productos_gestion_inv, productos_stock");
        $this->db->where("productos_id",$this->input->post('productos_id'));
        $producto = $this->db->get("productos");
        $agg = 0;
        $stock_value = 0;        
        foreach ($producto->result_array() as $key => $value) {
          $stock = $value['productos_stock'] - $this->input->post('productos_cantidad');
          $stock_value = $value['productos_stock'];
          if ($value['productos_gestion_inv']==1) {
            if ($stock >= 0) {
              $agg = 1;
              $this->db->set('productos_stock', $stock);
              $this->db->where('productos_id', $this->input->post('productos_id'));
              $this->db->update('productos');
            }
          }else{
            $agg = 1;
          }
        }
        */

        $esta=0;
        foreach($_SESSION["cart"] as $key => $cart):
          if(intval($cart['productos_id']) === intval($this->input->post('productos_id')) ){
            $_SESSION["cart"][$key]['productos_cantidad'] = $this->input->post('productos_cantidad');
            $nuevo=$_SESSION["cart"][$key];
            $datos["result"] = 1; 
            $datos["mensaje"] = "Se ha actualizado la cantidad";
            $esta++;
          }
        endforeach;

        if ($agg === 1) {
          if($esta===0){
            $nuevo = [
              'productos_id' => $this->input->post('productos_id'),
              'productos_addons' => $this->input->post('productos_addons'),
              'productos_cantidad' => $this->input->post('productos_cantidad'),
              'productos_precio_sin_addons' => $this->input->post('productos_precio_sin_addons'),
              'productos_precio' => $this->input->post('productos_precio'),
              'productos_envio_local' => $this->input->post('productos_envio_local'),
              'productos_valor_envio_local' => $this->input->post('productos_valor_envio_local'),
              'productos_ubicaciones_envio' => $this->input->post('productos_ubicaciones_envio'),
              'productos_envio_nacional' => $this->input->post('productos_envio_nacional'),
              'productos_envio_nacional' => $this->input->post('productos_envio_nacional'),
              'productos_valor_envio_nacional' => $this->input->post('productos_valor_envio_nacional'),
              'productos_vendedor' => $this->input->post('productos_vendedor'),
              'productos_fecha_programada' => ($this->input->post('productos_fecha_programada')!=='' && $this->input->post('productos_fecha_programada')!=='undefined') ? $this->input->post('productos_fecha_programada') : ''
            ];
            array_push($_SESSION["cart"], $nuevo);
            $datos["result"] = 1; 
            $datos["mensaje"] = "Agregado con éxito";
          }
          pixelMetaConversion('addToCart', $nuevo);
        }else{
          $datos["result"] = 0; 
          $datos["mensaje"] = "Este producto está agotado, quedan ".$stock_value." productos disponibles del mismo, verifica e intenta nuevamente.";
        }
        
        $this->load->view("themes/modern/tienda/cart", $datos);

      }
    }

    public function delitem($idprod=0,$precio=0){
      if ($idprod!=0 && $precio!=0) {
        foreach ($_SESSION['cart'] as $key => $value) {
          if ($value["productos_id"]==$idprod && $value["productos_precio"]==$precio) {
            
            $this->db->select("productos_gestion_inv,productos_stock");
            $this->db->where("productos_id",$value['productos_id']);
            $producto = $this->db->get("productos");
            $agg = 0;
            $stock_value = 0;
            foreach ($producto->result_array() as $key2 => $value2) {
              $stock = $value2['productos_stock'] + $value["productos_cantidad"];
              if ($value2['productos_gestion_inv']==1) {
                $agg = 1;
                $this->db->set('productos_stock', $stock);
                $this->db->where('productos_id', $value['productos_id']);
                $this->db->update('productos');
              }
            }

            unset($_SESSION['cart'][$key]);
          }
        }
      }
      $this->load->view("themes/modern/tienda/cart");
    }
    public function vaciarCarrito(){
      unset($_SESSION['cart']);
      $this->load->view("themes/modern/tienda/cart");
    }
    public function productosReqUbi(){
      
      $idProductos = array();
      foreach ($_SESSION['cart'] as $key => $value) {
        if (isset($value["productos_envio_nacional"]) && $value["productos_envio_nacional"]==0) {
          array_push($idProductos, $value['productos_id']);
        }
      }
      $this->db->select("medios.medios_enlace_miniatura as image,productos_id,productos_titulo,productos_precio");
      $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada");
      $this->db->where_in("productos_id", $idProductos);
      $variable = $this->db->get("productos");

      foreach ($variable->result_array() as $key => $value) {
        $image = image($value['image']);
        echo '
        <li>
            <span style="background-image:url('.$image.');" class="image"></span>
            <p class="text">
                <span>'.$value['productos_titulo'].'</span><br>
                <strong>'.number_format($value['productos_precio'], 0, ',', '.').'</strong>
            </p>
        </li>
        ';
      }

    }
    
    public function politicas($tip=''){
      $datos = [
        'vista' => "front",
        'title' => 'Política de Privacidad app móvil Alma de las Cosas',
        'ogfb' => true,
        'view' => "tienda/politicas-app",
        'custom_header' => "header_logo",
        'custom_footer' => "footer_checkout"
      ];
      
      $this->load->view('normal_view',$datos);
    }

    public function condiciones(){
      $datos['vista'] = "front";
      $datos['ogfb'] = true;
      $datos['view'] = "tienda/condiciones-app";
      $this->load->view('normal_view',$datos);
    }

    public function eliminardatos(){
      $datos = [
        'vista' => "front",
        'title' => 'Eliminacion de datos - Alma de las Cosas',
        'ogfb' => true,
        'view' => "tienda/eliminacion-datos-app",
        'custom_header' => "header_logo",
        'custom_footer' => "footer_checkout"
      ];

      $this->load->view('normal_view', $datos);
    }

    public function eliminarDatoApp(){
      $email = $_REQUEST['txtEmail'];
      $datos = $this->usuarios_model->getByEmail($email);
      if(count($datos)>0){
        $resp = $this->mdMailing->mailUserDisabled($email);
        $_SESSION['msj']=['tip'=>'success', 'tit'=>'!Wow,', 'txt'=>' lamentamos que nos desconectemos, pero ya se ha eliminado tus datos de nuestra base de datos.'];
      }else{
        $_SESSION['msj']=['tip'=>'danger', 'tit'=>'!Ups,', 'txt'=>' no encontramos datos con este correo'];
      }
      redirect(base_url('eliminacion-datos'));
    }

}
