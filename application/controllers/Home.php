<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Home extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('home');
        $this->load->helper('commun');
        $this->load->model("menu_model");
        $this->load->model("categorias_model");
        $this->load->model("productos_model");
        $this->load->model("pedidos_model");
        $this->load->model("usuarios_model");
        $this->load->model("general_model");
        $this->load->model("vendor_model");
    }

    public function suma_boton_whatsapp(){
      $datos = array();
      $cantidad = floatval($_POST['whatsapp_plugin_cantidad'])+1;
      $this->db->set('whatsapp_plugin_cantidad', $cantidad);
      $this->db->where('whatsapp_plugin_id', $_POST['whatsapp_plugin_id']);
      $modif = $this->db->update('whatsapp_plugin');
      if ($modif) {
        $datos['mensaje'] = "Éxito";
        $datos['error'] = 0;
        $datos['result'] = 1;
      }else{
        $datos['mensaje'] = "Ha ocurrido un error en la modificación";
        $datos['error'] = 1;
        $datos['result'] = 0;
      }

      echo json_encode($datos);

    }

    public function pruebasMailer(){
      $enviar = $this->mailing_model->mailPedidoEnviado($this->pedidos_model->singleNew(1048));
    }

    public function index()
    {
      
      if (isMobile()) {
        $js_data = [
          'assets/plugins/owl/dist/owl.carousel.min.js',
          'assets/js/front/home/index_for_mobile.js?'.rand(),
          'assets/js/front/confia_alma/index.js?'.rand(),
        ];
      }else{
        $js_data = [
          'assets/plugins/owl/dist/owl.carousel.min.js',
          'assets/js/front/home/index.js?'.rand(),
        ];
      }

      $bannerHome = $this->general_model->bannerHome();

      $banner_imagen_mobile_url='';
      $banner_imagen_mobile=(isset($bannerHome['banner_imagen_mobile']))?$bannerHome['banner_imagen_mobile']:'';
  
      $res = $this->db->select('medios_url')->where('medios_id', $banner_imagen_mobile)->get('medios')->result_array();
      foreach ($res as $key => $value)  $bannerHome['banner_imagen_mobile_url']=$value['medios_url'];

      //-------------------------------------------------------------------------

      $cat_filtros['limit'] = array(8,0);
      $cat_filtros['where_arr'] = array(
                                    'categorias_status' => 1,
                                    'categorias_padre' => 0
                                  );
      $categorias = $this->categorias_model->getAll($cat_filtros);

      //-------------------------------------------------------------------------

      $filtros = array();
      $filtros['orderby'] = array("productos.productos_id", "DESC");
      $filtros['where_arr'] =  array(
        'productos_estado_inv' => 1,
        'productos_estatus' => 1
      );
      $page = 1;
      $limite = array(10,0);
      $productos = $this->productos_model->getAll($filtros, $page, $limite);


      //-------------------------------------------------------------------------

      $filtros['orderby'] = array("ventas", "DESC");
      $filtros['where_arr'] =  array(
        'productos_estado_inv' => 1,
        'productos_estatus' => 1
      );
      $page=1;
      $limite=array(10,0);
      $recomendados = $this->productos_model->getAll($filtros,$page,$limite);


      //-------------------------------------------------------------------------

      $catVendor = $this->vendor_model->obtenerCategoriasVendor();

      $datos = [
        'vista' => "front",
        'view' => "home/index",
        'css_data' => ['assets/plugins/owl/dist/assets/owl.carousel.min.css'],
        'js_data' => $js_data,
        'banner_home' => $bannerHome,
        'banner_second' => $this->general_model->bannerSecond(),
        'categorias' => $categorias,
        'productos' => $productos,
        'latestViews' => $this->productos_model->getIdsLatestViews(),        
        'categorias_vendor' => $catVendor,
        'productos_recomendados' => $recomendados,
        'vendedores_first' => $this->vendor_model->obtenerVendedorPorCategoria($catVendor['first_cat'])
      ];

      $this->load->view('normal_view', $datos);
    }

    public function pagina_no_encontrada()
    {
      
      $datos = array();
      $datos['vista'] = "front";
      $datos['view'] = "home/pagina_no_encontrada";
      $this->load->view('normal_view',$datos);
    }

    public function registro(){
      $datos = array();
      if (!isset($_POST["tipo_accesos"]) || $_POST["tipo_accesos"] != 6) {
        $this->session->set_userdata('message_tipo', "error");
        $this->session->set_userdata('message', "Error de seguridad, intente de nuevo.");
        redirect(base_url()."auth/login", $datos);
      }else{
        $ingresar = $this->usuarios_model->save();
        //print_r($ingresar);
        //exit();

        if (isset($ingresar['data']) && $ingresar['data']!=='') {
            if ($ingresar['mensaje_error']=="") {
                $this->session->set_userdata('message_tipo', "success");
                $this->session->set_userdata('message', "Registro Éxitoso, por favor, inicie sesión.");
                redirect(base_url()."auth/login", $datos);
            }else{
                $this->session->set_userdata('message_tipo', "error");
                $this->session->set_userdata('message', $ingresar['mensaje_error']);
                redirect(base_url()."auth/login", $datos);
            }
        }else {
            $this->session->set_userdata('message_tipo', "error");
            $this->session->set_userdata('message', $ingresar['mensaje_error']);
            redirect(base_url()."auth/login", $datos);
        }
      }
    }

    public function obtenerMunicipios($id_departamento=0,$id_municipio=0){
      $datos = array();
      $filter = array();
      if ($id_departamento!==0 && $id_departamento!=='') $filter['where'] = array('departamento_id', $id_departamento);
      $datos['id_municipio'] = $id_municipio;
      $datos['municipios'] = $this->general_model->obtenerMunicipios($filter);
      $this->load->view("themes/admin/municipios/options", $datos);
    }
    
    public function obtenerDepartamentos($id_departamento=0){
      $datos = array();

      $filter = array();
      $datos['id_departamento'] = $id_departamento;
      $datos['departamentos'] = $this->general_model->obtenerDepartamentos();
      $this->load->view("themes/admin/departamentos/options", $datos);
    }

    public function nuevaUbicacion($departamento = 0, $municipio = 0){

      $datos = array();

      if ($departamento!=0 && $municipio!=0) {

        $this->session->set_userdata("departamento_session", $departamento);
        $this->session->set_userdata("municipio_session", $municipio);

        if (isset($_SESSION['usuarios_id'])) {
          $this->db->set('usuarios_departamento', $departamento);
          $this->db->set('usuarios_municipio', $municipio);
          $this->db->where('usuarios_id', $_SESSION['usuarios_id']);
          $this->db->update('usuarios');
        }

        if ($_SESSION['municipio_session']!=0) {
          $this->db->select("m.municipio,d.departamento");
          $this->db->where("m.id_municipio",$_SESSION['municipio_session']);
          $this->db->join("departamentos as d","d.id_departamento=m.departamento_id","inner");
          $ubicaciones_cons = $this->db->get("municipios as m");
          foreach ($ubicaciones_cons->result_array() as $key => $value) {
            $_SESSION['departamento_nombre'] = $value['departamento']; 
            $_SESSION['municipio_nombre'] = $value['municipio']; 
          }
        }else{
          $_SESSION['departamento_nombre'] = ""; 
          $_SESSION['municipio_nombre'] = ""; 
        }

        $datos['result'] = 1;
        $datos['mensaje'] = "Ubicación Modificada con éxito";
        $datos['nombre_municipio'] = $_SESSION['municipio_nombre'];
        $datos['nombre_departamento'] = $_SESSION['departamento_nombre'];
      }else{
        $datos['result'] = 0;
        $datos['mensaje'] = "Ha ocurrido un error con el servidor";
      }

      echo json_encode($datos);

    }

    public function obtenerVendorCat($categorias_id=0){
      $datos = array();
      if ($categorias_id!=0) {
        $datos['categorias_id'] = $categorias_id;
        $datos['vendedores'] = $this->vendor_model->obtenerVendedorPorCategoria($categorias_id);
        $this->load->view("themes/modern/vendedores/home-vendor", $datos);        
      }
    }


    public function getMac(){
      echo "<pre>";
      echo "</pre>";
    }

}
