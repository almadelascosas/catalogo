<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Checkout extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('checkout');
        $this->load->library('session');
        $this->load->helper('commun');
        $this->load->model("categorias_model");
        $this->load->model("medios_model");
        $this->load->model("productos_model");
        $this->load->model("pedidos_model");
        $this->load->model("general_model");
        $this->load->model("checkout_model");
        $this->load->model("Metodos_Pagos_model", 'mdMpago');
        $this->load->model("auth_model", "modAuth");
        $this->load->model("address_model", "mdAddUse"); 
        $this->load->model("usuarios_model", "mdUser");
        $this->load->model("municipios_model");
        $this->load->library('phpmailing');

    }

    public function index()
    {
      // if(!isset($this->session->userdata('login_user')['usuarios_id'])) redirect('checkout/promo');

      $datosUsuario = (isset($this->session->userdata('login_user')['usuarios_id'])) ? $this->session->userdata('login_user') : [];
      $datos = [
        'vista' => "front",
        'view' => "checkout/index_new",
        'custom_header' => "header_checkout",
        'custom_footer' => "footer_checkout",
        'js_data' => [
            'assets/js/pages/productos/index.js?'.rand(),
            'assets/js/pages/checkout/index.js?'.rand()
        ],
        'metodos_pagos' => $this->db->where('alma_metodos_pagos_estatus', 1)->get('alma_metodos_pagos'),
        'departamentos' => $this->general_model->obtenerDepartamentos(),
        'mdMunicipios' => $this->municipios_model,
        'productos' => $this->productos_model->getAll(),
        'mdProducto' => $this->productos_model,
        'datos_usuario' => $datosUsuario,
        'dataPixel' => 'inicioPago'
      ];       
      $this->load->view('normal_view', $datos);
    }

    public function recalcularEnvio(){
      /**
       * Recalcular valor envio
       *
       * Para los usuarios No Logueados, que han seleccionado Dpto/Ciudad
       * se recalculara el valor del envio, asi mismo el valor total del pedido
       *
       * @access public
       * @param integer : idmuni : ID del municipio seleccionado
       * @return json : 
      */

      $idMuni = intval($_POST['idmun']);
      $vendedores = [];
      $envioTotal=0;
      $ubica=[];
      $estUbica=[];
      $html='';

      if(isset($_SESSION['cart'])){
        foreach($_SESSION['cart'] as $key2 => $prod):
          $dataProducto = $this->productos_model->single($prod['productos_id']);
          $ubicaciones = explode('/', $dataProducto['productos_ubicaciones_envio']);
          $estaUbicada=0;
          $listaMuni=[];

          foreach($ubicaciones as $ubic):
            list($dpto, $muni) = explode(',', $ubic);
            $estaUbicada += (intval($muni)===$idMuni) ? 1 : 0;
            $listaMuni[]=$muni;
          endforeach;

          $tipoEnvio = ($estaUbicada>0)?'L':'N';
          $estadoCobro = cobroEnvioCkeckoput($dataProducto['productos_vendedor'], $listaMuni, $tipoEnvio);
          $estUbica[] = $estadoCobro;
          $cobroParcial=0;

          $envioTotal += ($estadoCobro) ? intval(($tipoEnvio==='L')?$dataProducto['productos_valor_envio_local']:$dataProducto['productos_valor_envio_nacional']) : 0;
          if($estadoCobro) $cobroParcial = ($tipoEnvio==='L')?$dataProducto['productos_valor_envio_local']:$dataProducto['productos_valor_envio_nacional'];
          
          $tipLetra=($tipoEnvio==='L')?'Local':'Nacional';
          $html.='<tr>
                 <td>'.$dataProducto['productos_titulo'].'</td>
                 <td>'.$tipLetra.'</td>
                 <td class="text-right"> $'.number_format($cobroParcial, 0, ',', '.').'</td>
                 </tr>';
          
        endforeach;

        $html.='<tr>
                <td class="text-right" colspan="2"><b>TOTAL</b></td>
                <td class="text-right"> $'.number_format($envioTotal, 0, ',', '.').'</td>
                </tr>';

      }
      unset($_SESSION['dataCobro']);
      $precioTotal = intval($_POST['subt']) + intval($envioTotal);
      print json_encode(['enviototal'=>$envioTotal, 
                         'enviototalformat'=>number_format($envioTotal, 0, ',', '.'), 
                         'totalcompra' => $precioTotal,
                         'totalcompraformat' => number_format($precioTotal, 0, ',', '.'),
                         'vendedores'=>$vendedores, 
                         'estados'=>$estUbica, 
                         'html'=>$html
                       ]);
      exit();
    }

    public function misDireccionesUsuario(){
      /**
       * Vista de Lista de Direcciones Usuario Logueado
       *
       * Al estar Usuario logueado, y si tiene más de 1 dirección, 
       * mostrará esta vista la cual podra seleccionar otra direccion y opcion  
       * de crear otra dirección mas
       *
       * @access public
       * @param array session login_user
       * @return View
       */
      $datos = [
        'vista' => "front",
        'view' => "checkout/list_address_user",
        'custom_header' => "header_checkout",
        'custom_footer' => "footer_checkout",
        'lista_direcciones' => $this->session->userdata('login_user')['direcciones']
      ];       
      $this->load->view('normal_view', $datos);
    }

    public function dirsel(){
      /**
       * Vista de Lista de Direcciones Usuario Logueado
       *
       * Al estar Usuario logueado, y si tiene más de 1 dirección, 
       * mostrará esta vista la cual podra seleccionar otra direccion y opcion  
       * de crear otra dirección mas
       *
       * @access public
       * @param array session login_user
       * @return View
       */

      $data=['prede'=>0];
      $filter = ['id_usuario', $this->session->userdata('login_user')['usuarios_id']];
      $this->mdAddUse->updateAddressMultiple($data, $filter);

      $data=['prede' => 1];
      $this->mdAddUse->updateAddress($data, $this->input->post('rdDir'));

      if($this->restablecerDatosSession()) redirect('checkout');
    }

    public function agregarDireccionUsuario(){
      /**
       * Vista de Lista de Direcciones Usuario Logueado
       *
       * Al estar Usuario logueado, y si tiene más de 1 dirección, 
       * mostrará esta vista la cual podra seleccionar otra direccion y opcion  
       * de crear otra dirección mas
       *
       * @access public
       * @param array session login_user
       * @return View
       */
      if(!isset($this->session->userdata('login_user')['usuarios_id'])) redirect('sdafdsf');

      $datos = [
        'vista' => "front",
        'view' => "checkout/add_address_user",
        'custom_header' => "header_checkout",
        'custom_footer' => "footer_checkout",
        'js_data' => [
            'assets/js/pages/productos/index.js?'.rand(),
            'assets/js/pages/checkout/index.js?'.rand(),
        ],
        'departamentos' => $this->general_model->obtenerDepartamentos()
      ];       
      $this->load->view('normal_view', $datos);
    }
    public function addDireccion(){
      $this->mdAddUse->addAddress($this->input->post());
      if($this->session->userdata('login_user')['dni']===''){ 
        $this->session->set_flashdata('info', 'guardada DNI usuario');
        $data=['dni'=>$this->input->post('dni')];
        $this->db->where('usuarios_id', $this->session->userdata('login_user')['usuarios_id'])->update('usuarios', $data);
      }
      $this->session->set_flashdata('success', 'Se ha guardado con exito tu direccion');
      $this->restablecerDatosSession();
      redirect('checkout');
    }


    public function thanks($estado, $idPedido, $datosExtra=[])
    {
      if(!isset($idPedido)) redirect("checkout/");
    
      $datos = [
        'vista' => "front",
        'view' => "checkout/thanks_new",
        'custom_footer' => "footer_checkout",
        'css_data' => [
          'assets/css/pages/front/thanks/index.css'
        ]
      ];

      $datos['pedido'] = $this->pedidos_model->singleNew($idPedido);

      switch($estado){
        case 'exitoso':
          $parametro=[
            'icono' => 'assets/img/icono-pay/ok.png',
            'background' => 'assets/img/icono-pay/fondo-pago-exito.fw.png',
            'titulo' => 'Pago exitoso',
            'descrip' => 'Felicitaciones, tu transacción fue exitosa, Enviaremos al email registrado la confirmación de la compra',
            'estado' => $estado
          ];     
          break;
        case 'pendiente':
          $parametro=[
            'icono' => 'assets/img/icono-pay/pendiente.png',
            'background' => 'assets/img/icono-pay/fondo-pago-error.fw.png',
            'titulo' => 'Pendiente de pago',
            'descrip' => 'Estamos atentos y en espera de la confirmación de pago por parte de la entidad bancaria',
            'estado' => $estado
          ];          
          break;
        case 'rechazado':
          $parametro=[
            'icono' => 'assets/img/icono-pay/error.png',
            'background' => 'assets/img/icono-pay/fondo-pago-error.fw.png',
            'titulo' => 'Ups! el pago no fue exitoso',
            'descrip' => 'La plataforma rechazó el intento de pago. Puedes ponerte en contacto con nosotros para ayudarte',
            'estado' => $estado
          ];          
          break;
        case 'cancelado':
          $parametro=[
            'icono' => 'assets/img/icono-pay/error.png',
            'background' => 'assets/img/icono-pay/fondo-pago-error.fw.png',
            'titulo' => 'Wow! el pago fue cancelado',
            'descrip' => 'El pago fue cancelado. Puedes intentarlo de nuevo o puedes ponerte en contacto con nuestro equipo para ayudarte',
            'estado' => $estado
          ];          
          break;
        case 'error':
          $parametro=[
            'icono' => 'assets/img/icono-pay/error.png',
            'background' => 'assets/img/icono-pay/fondo-pago-error.fw.png',
            'titulo' => 'Ups! hubo un eror',
            'descrip' => 'Ocurrio un error en el pago. Puedes intentar de nuevo más tarde o ponerte en contacto con nosotros para ayudarte',
            'estado' => $estado
          ];          
          break;
        
      }

      $datos['parametroEstado']=$parametro;

      $this->load->view('normal_view', $datos);
    }


    public function finalizarCompra($pedido=0){
        $datos = [
          'vista' => "front",
          'custom_header' => "header_checkout",
          'custom_footer' => "footer_checkout",
          'view' => "checkout/finalizar-compra-new",
          'js_data' => [
              'assets/js/pages/productos/index.js?v='.rand(99,9999)
            ],
          'dataPixel' => 'purchase'
        ];
        
        if ($pedido===0) {
          $datos['pedido'] = $this->pedidos_model->generarPreOrdenNew();

          $rpta = $this->mailing_model->mailPedidoRecibido($this->pedidos_model->singleNew($datos['pedido']['pedidos_id']));

          //Opcion guardar direccion
          if(intval($this->input->post('saveAddress'))===1){
            $data=[
              'id_usuario'=>$this->session->userdata('login_user')['usuarios_id'],
              'dni'=>$this->input->post('pedidos_identificacion'),
              'nombre'=>'Direccion Envio Checkout',
              'id_dpto'=>$this->input->post('pedidos_departamento'),
              'id_muni'=>$this->input->post('pedidos_municipio'),
              'barrio'=>$this->input->post('pedidos_barrio'),
              'direccion'=>$this->input->post('pedidos_direccion'),
              'referencia'=>$this->input->post('pedidos_referencia'),
              'persona'=>$this->input->post('pedidos_nombre'),
              'telefono'=>$this->input->post('pedidos_telefono'),              
              'prede'=>1
            ];
            $this->mdAddUse->addAddress($data);
            if($this->session->userdata('login_user')['dni']===''){ 
              $this->session->set_flashdata('info', 'guardada DNI usuario');
              $data=['dni'=>$this->input->post('pedidos_identificacion')];
              $this->db->where('usuarios_id', $this->session->userdata('login_user')['usuarios_id'])->update('usuarios', $data);

            }
            $this->restablecerDatosSession();
          }
          redirect("checkout/finalizar-compra/".$datos['pedido']['pedidos_id'], $datos);
        }else{
          $datos['pedido'] = $this->pedidos_model->singleNew($pedido);
          if ($datos['pedido']['pedido']['pedidos_metodo_pago']==1 || $datos['pedido']['pedido']['pedidos_metodo_pago']=="Payzen") {
            $this->load->view('themes/modern/checkout/finalizar-comprar-payzen', $datos);
          }else{
            $this->load->view('normal_view', $datos);
          }
        }        
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
      $pedidodata['vlrtotal'] = $pedido['vlrtotal'];

      $datoMetodoPago = $this->mdMpago->getMetodoPagoById($idMetodoPago);
      setlocale(LC_TIME, "spanish");
      setlocale(LC_ALL,"es_ES");
      $newDate = date("d-m-Y", strtotime($pedidodata['pedidos_fecha_creacion'])); 
      $fechaMostrar = strftime("%d, %b %Y", strtotime($newDate)); 
      $pedidodata['pedidos_fecha_larga']=$fechaMostrar;

      $domain=base_url();
      switch($domain){
        case 'http://pruebas.almadelascosas.lc/':
        case 'https://code.almadelascosas.co/':
            $modo = 'TEST';
            $codeSignature='OEOBrffArdHWSSWg';
            break;
        case 'https://almadelascosas.com/':
            $modo = 'PRODUCTION';
            $codeSignature='WZhiqh8mYRheu8uV';
            break;
      }    

      $datos=[
        'pedido' => $pedidodata,
        'mpago' => $datoMetodoPago,
        'modo' => $modo,
        'codSignature' => $codeSignature
      ];
      
      $this->load->view($datoMetodoPago['alma_metodos_pagos_template'], $datos);
    }

    /**
     * Vista de Pago Exitoso PSE
     *
     * 
     *
     * @access public
     * @param Integer $id ID del pedido
     * @return Array $datos 
    */
    public function returnPayPayzen($tipo, $idPedido){
      switch($tipo){
        case 'exitoso':
          $parametro=[
            'icono' => 'assets/img/icono-pay/ok.png',
            'background' => 'assets/img/icono-pay/fondo-pago-exito.fw.png',
            'titulo' => 'Pago exitoso',
            'descrip' => 'Felicitaciones, tu transacción fue exitosa, Enviaremos al email registrado la confirmación de la compra'
          ];          
          break;
        case 'rechazado':
          $parametro=[
            'icono' => 'assets/img/icono-pay/error.png',
            'background' => 'assets/img/icono-pay/fondo-pago-error.fw.png',
            'titulo' => 'Ups! el pago no fue exitoso',
            'descrip' => 'La plataforma rechazó el intento de pago. Puedes ponerte en contacto con nosotros para ayudarte'
          ];          
          break;
        case 'cancelado':
          $parametro=[
            'icono' => 'assets/img/icono-pay/error.png',
            'background' => 'assets/img/icono-pay/fondo-pago-error.fw.png',
            'titulo' => 'Wow! el pago fue cancelado',
            'descrip' => 'El pago fue cancelado. Puedes intentarlo de nuevo o puedes ponerte en contacto con nuestro equipo para ayudarte'
          ];          
          break;
        case 'error':
          $parametro=[
            'icono' => 'assets/img/icono-pay/error.png',
            'background' => 'assets/img/icono-pay/fondo-pago-error.fw.png',
            'titulo' => 'Ups! hubo un eror',
            'descrip' => 'Ocurrio un error en el pago. Puedes intentar de nuevo más tarde o ponerte en contacto con nosotros para ayudarte'
          ];          
          break;
      }

      $datos = [
        'tipo' => $tipo, 
        'vista' => "front",
        'view' => "checkout/pago-retorno-payzen",
        'parametro' => $parametro,
        'custom_header' => "header_checkout",
        'custom_footer' => "footer_checkout"
      ];
      //Actualiza el pedido a 'En preparacion'      
      /*
      $pedido = $this->pedidos_model->singleNew($idPedido);
      */

      $this->load->view('normal_view', $datos);
    }

    public function restablecerDatosSession(){
      /**
       * Reconsultar datos de Session
       *
       * Este metodo es util para cuando, una vez este logueado el usuario
       * y ocurra algun cambio (actualizacion de datos, nuevas direcciones, etc)
       * este de nuevo sea un logueo en segundo plano con los datos actualizados 
       *
       * @access public
       * @return session
       */

      $user_data = $this->modAuth->getUserData($this->session->userdata('login_user')['usuarios_id']);
      $user_data['user_data']['direcciones']=$user_data['user_address'];
      if($user_data['result']=='success') $this->modAuth->fillSession($user_data['user_data']);
      return true;
    }

    public function noLoginProm(){
      /**
       * Vista promocional para logueo
       *
       * Si intenta ingresar a checkout, sin estar logueado
       * se reedirecciona automaticamente a esta vista, en la que se 
       * recomienda al usuario registrarse, logearse, o ingresdar como invitado 
       *
       * @access public
       * @return session
       */

      $datos = [
        'vista' => "front",
        'view' => "checkout/promo_login",
        'custom_header' => "header_checkout",
        'custom_footer' => "footer_checkout"
      ];

      $this->load->view('normal_view', $datos);
    }



}
