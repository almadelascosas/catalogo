<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Checkout extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('checkout');
        $this->load->helper(array('commun'));
        $this->load->model("categorias_model");
        $this->load->model("medios_model");
        $this->load->model("productos_model");
        $this->load->model("pedidos_model");
        $this->load->model("general_model");
        $this->load->model("checkout_model");
        $this->load->model("Metodos_Pagos_model", 'mdMpago');
    }

    public function index()
    {
      $datos = array();
      $datos['vista'] = "front";
      $datos['view'] = "checkout/index_new";
      $datos['custom_header'] = "header_checkout";
      $datos['custom_footer'] = "footer_checkout";
      $datos['css_data'] = array();
      $datos['js_data'] = array(
          'assets/js/pages/productos/index.js?v='.rand(99,9999),
          'assets/js/pages/checkout/index.js?v='.rand(99,9999),
      );
      
      $this->db->select('*');
      $this->db->where('alma_metodos_pagos_estatus', 1);
      $datos['metodos_pagos'] = $this->db->get('alma_metodos_pagos');
      
      $datos['departamentos'] = $this->general_model->obtenerDepartamentos();
      $datos['productos'] = $this->productos_model->getAll();
      $this->load->view('normal_view', $datos);
    }
    public function thanks()
    {
      $datos = array();
      $datos['vista'] = "front";
      $datos['view'] = "checkout/thanks_new";
      $datos['custom_footer'] = "footer_checkout";
      $datos['css_data'] = array(
        base_url().'assets/css/pages/front/thanks/index.css',
      );
      $datos['js_data'] = array(
          'assets/js/pages/productos/index.js?v='.rand(99,9999),
      );
      if (isset($_REQUEST['external_reference'])) {
        $datos['pedido'] = $this->pedidos_model->singleNew($_REQUEST['external_reference']);
      }elseif(isset($_REQUEST['extra1'])){
        $datos['pedido'] = $this->pedidos_model->singleNew($_REQUEST['extra1']);
      }else{
        $datos['pedido'] = $this->pedidos_model->singleNew(0);
      }
      $this->load->view('normal_view', $datos);
    }
    public function finalizarCompra($pedido = 0)
    {
        $datos = array();
        $datos['vista'] = "front";
        $datos['custom_header'] = "header_checkout";
        $datos['custom_footer'] = "footer_checkout";
        $datos['view'] = "checkout/finalizar-compra-new";
        if ($pedido==0) {
          $datos['pedido'] = $this->pedidos_model->generarPreOrdenNew();
          $datos['css_data'] = array();
          $datos['js_data'] = array(
              'assets/js/pages/productos/index.js?v='.rand(99,9999),
          );
          redirect(base_url()."checkout/finalizar-compra/".$datos['pedido']['pedidos_id'], $datos);
        }else{
          $datos['pedido'] = $this->pedidos_model->singleNew($pedido);
          $datos['css_data'] = array();
          $datos['js_data'] = array(
              'assets/js/pages/productos/index.js?v='.rand(99,9999),
          );
          if ($datos['pedido']['pedido']['pedidos_metodo_pago']==1 || $datos['pedido']['pedido']['pedidos_metodo_pago']=="Payzen") {
            $this->load->view('themes/modern/checkout/finalizar-comprar-payzen', $datos);
          }else{
            $this->load->view('normal_view', $datos);
          }
        }
        
    }

    /**
     * Muestra Vista metodo de pago segun el pedido
     *
     * 
     *
     * @access public
     * @param Integer $id ID del pedido
     * @return Array $datos 
    */
    public function paymenStep($idPedido){
      $pedido = $this->pedidos_model->singleNew($idPedido);
      $pedidodata = $pedido['pedido'];
      $pedidodata['vlrtotal']=$pedido['vlrtotal'];

      $idMpago = intval($pedidodata['pedidos_metodo_pago']);
      switch($idMpago){
        case 0:
          $idmetodoBD = 11;
          break;
        case 3:
          $idmetodoBD = 15;
          break;
        case 4:
          $idmetodoBD = 15;
          break;
        default:
          $idmetodoBD = 11;
          break;
      }
      $datoMetodoPago = $this->mdMpago->getMetodoPagoById($idmetodoBD);
      setlocale(LC_TIME, "spanish");
      setlocale(LC_ALL,"es_ES");
      $newDate = date("d-m-Y", strtotime($pedidodata['pedidos_fecha_creacion'])); 
      $fechaMostrar = strftime("%d, %b %Y", strtotime($newDate)); 
      $pedidodata['pedidos_fecha_larga']=$fechaMostrar;
      $datos=[
        'pedido' => $pedidodata,
        'mpago' => $datoMetodoPago
      ];

      $this->load->view('themes/modern/checkout/paymentPse', $datos);
    }

    /**
     * Muestra Vista metodo de pago segun el pedido
     *
     * 
     *
     * @access public
     * @param Integer $id ID del pedido
     * @return Array $datos 
    */
    public function pago($titulo, $idMetodoPago, $idPedido){
      $pedido = $this->pedidos_model->singleNew($idPedido);
      $pedidodata = $pedido['pedido'];
      $pedidodata['vlrtotal']=$pedido['vlrtotal'];

      $datoMetodoPago = $this->mdMpago->getMetodoPagoById($idMetodoPago);
      setlocale(LC_TIME, "spanish");
      setlocale(LC_ALL,"es_ES");
      $newDate = date("d-m-Y", strtotime($pedidodata['pedidos_fecha_creacion'])); 
      $fechaMostrar = strftime("%d, %b %Y", strtotime($newDate)); 
      $pedidodata['pedidos_fecha_larga']=$fechaMostrar;

      $datos=[
        'pedido' => $pedidodata,
        'mpago' => $datoMetodoPago
      ];
      
      $this->load->view($datoMetodoPago['alma_metodos_pagos_template'], $datos);
    }

    public function confirmationUrl(){
        
        $estatus = $_POST['state_pol'];
        $this->db->set('pedidos_estatus', $estatus);
        $this->db->where('pedidos_id', $_POST['extra1']);
        $this->db->update('alma_pedidos');

        if ($estatus==6 || $estatus==4) {
          $datos = array();
          $this->db->select("pedidos_detalle_producto as producto");
          $this->db->where("pedidos_detalle_pedidos_id",$_POST['extra1']);
          $consulta = $this->db->get("alma_pedidos_detalle");

          $productos = array();

          foreach ($consulta->result_array() as $key => $value) {
            array_push($productos,$value['producto']);
          }
          for ($i=0; $i < count($productos); $i++) {
            $this_estatus = "Rechazado";
            if ($estatus==6) {
                $this_estatus = "Rechazado";
            }
            elseif ($estatus==4) {
                $this_estatus = "En preparación";
            }
            array_push($datos, array(
                'productos_id' => $productos[$i],
                'pedidos_id' => $_POST['extra1'],
                'estatus' => $this_estatus, 
                'nro_guia' => "", 
                'nombre_empresa' => "", 
                'addons' => "", 
                'cambio_usuarios_id' => 0 
            ));
          }
          $this->db->insert_batch('pedidos_estatus_productos', $datos);
        }

    }

  public function recalculateCheckout($departamento = 0, $municipio = 0){
      $datos = array();

      $this->db->select('*');
      $this->db->where('alma_metodos_pagos_estatus', 1);
      $datos['metodos_pagos'] = $this->db->get('alma_metodos_pagos');

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
    
    $this->load->view('themes/modern/checkout/calculate-checkout', $datos);

  }

}
