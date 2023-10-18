<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Cuenta extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('cuenta');
        $this->load->library('session');
        $this->load->helper(array('commun'));
        $this->load->model("menu_model");
        $this->load->model("categorias_model");
        $this->load->model("productos_model");
        $this->load->model("usuarios_model", "mdUser");
        $this->load->model("general_model", 'mdGen');
        $this->load->model("auth_model", "modAuth");
        $this->load->model("address_model", "mdAddUse");
    }

    public function index()
    { 
      $datos = [
        'vista' => "front",
        'view' => "mi-cuenta/index"
      ];
      $this->load->view('normal_view',$datos);
    }

    public function orders()
    {
      $datos = array();
      $datos['vista'] = "front";
      $datos['view'] = "mi-cuenta/orders";
      if (isset($_SESSION['usuarios_id'])) {
        $datos['pedidos'] = $this->mdGen->getMyOrders();
      }else{
        $datos['pedidos'] = NULL;
      }
      $this->load->view('normal_view',$datos);
    }
    
    public function ordersview($id=0)
    {
      $datos = [
        'vista' => "front",
        'view' => "mi-cuenta/ordersview",
        'css_data' => [ base_url('assets/css/pages/front/rastreo/detalle_pedido.css').'?v='.rand(99,9999) ],
        'pedido' => (isset($_SESSION['usuarios_id'])) ? $this->mdGen->getOrderDetails($id) : null
      ];
      $this->load->view('normal_view',$datos);
    }


    public function address()
    {
      
      $datos = [
        'vista' => 'front',
        'view' => 'mi-cuenta/list-address',
        'address' => $this->mdAddUse->getAddresByUser($_SESSION['usuarios_id'])
      ];
      $this->load->view('normal_view', $datos);
    }

    public function editAddress($nomdir='')
    {
      
      $datos = [
        'vista' => 'front',
        'view' => 'mi-cuenta/edit-address',
        'js_data' => [ 'assets/js/pages/checkout/index.js?'.rand(),
                       'assets/js/function.js?'.rand() 
                      ],
        'dptos' => $this->mdGen->obtenerDepartamentos()
      ];
      if(isset($nomdir) && $nomdir!==''){
        $text = explode('-', $nomdir);
        $id = intval($text[0]);
        $datos['address']=$this->mdAddUse->getAddresById($id)[0];
        $datos['muni'] = $this->mdGen->obtenerMunicipios(['where' => ['departamento_id', $datos['address']['id_dpto']]]);
      }
      $this->restablecerDatosSession();
      $this->load->view('normal_view', $datos);
    }

    public function saveAddress()
    {
      $id=$this->input->post('txtId');
      if($id!==''){
        $data=[
          'dni' => $this->input->post('txtDni'),
          'nombre' => $this->input->post('txtName'),
          'id_dpto' => $this->input->post('pedidos_departamento'),
          'id_muni' => $this->input->post('pedidos_localidad'),
          'barrio' => $this->input->post('txtBar'),
          'direccion' => $this->input->post('txtDir'),
          'telefono' => $this->input->post('txtTel'),
          'persona' => $this->input->post('txtPersona'),
          'referencia' => $this->input->post('txtRef')
        ];
        $this->mdAddUse->updateAddress($data, $this->input->post('txtId'));
        $this->session->set_flashdata('success', 'Se ha actualizado con exito tu direccion');
      }else{
        $data=[
          'id_usuario'=> $_SESSION['usuarios_id'],
          'dni' => $this->input->post('txtDni'),
          'nombre' => $this->input->post('txtName'),
          'id_dpto' => $this->input->post('pedidos_departamento'),
          'id_muni' => $this->input->post('pedidos_localidad'),
          'barrio' => $this->input->post('txtBar'),
          'direccion' => $this->input->post('txtDir'),
          'telefono' => $this->input->post('txtTel'),
          'persona' => $this->input->post('txtPersona'),
          'referencia' => $this->input->post('txtRef')
        ];
        $this->mdAddUse->addAddress($data);        
        $this->session->set_flashdata('success', 'Se ha creado con exito tu nueva direccion');
      }

      //En caso de tener vacia el DNI del usuario...actualizamos
      if($this->session->userdata('login_user')['dni']===''){
        $data=['dni'=>$this->input->post('txtDni')];
        $this->db->where('usuarios_id', $this->session->userdata('login_user')['usuarios_id'])->update('usuarios', $data);
        $this->session->set_flashdata('info', 'Se ha creado con exito tu nueva direccion');
      }

      $this->restablecerDatosSession();
      redirect(base_url()."mi-cuenta/address");
    }

    public function dirpredeterminada($id, $name){
      //Colocamos todas las direcciondes del usuario como NoPredetermimadas
      $data=['prede'=>0];
      $filter = ['id_usuario', $_SESSION['usuarios_id']];
      $this->mdAddUse->updateAddressMultiple($data, $filter);
      
      //Colocamos solo la seleccionada como Predeterminada
      $data=['prede'=>1];
      $this->mdAddUse->updateAddress($data, $id);
      $this->session->set_flashdata('success', 'direccion como predeterminada');
      $this->restablecerDatosSession();
      redirect(base_url("mi-cuenta/address"));
    }

    public function editAccount()
    {
      $datos = [
        'vista' => 'front',
        'view' => 'mi-cuenta/edit-account',
        'js_data' => [
          'assets/js/function.js?'.rand()
        ],
        'departamentos' => $this->mdGen->obtenerDepartamentos(),
        'muni' => (isset($_SESSION['usuarios_departamento']) && $_SESSION['usuarios_departamento']!=='') ? $this->mdGen->obtenerMunicipios(['where' => ['departamento_id', $_SESSION['usuarios_departamento']]]) : null
      ];
      
      $this->restablecerDatosSession();
      $this->load->view('normal_view', $datos);
    }

    public function deleteAddress($id){
      $filter=[['id_dir', $id]];
      $this->mdAddUse->deleteAddress($filter);
      $this->session->set_flashdata('success', 'direccion eliminada');
      $this->restablecerDatosSession();
      redirect(base_url("mi-cuenta/address"));
    } 

    public function guardaruser(){
      $datos = array();
      if ($this->input->post('usuarios_id')==$_SESSION['usuarios_id']) {
        if ($this->input->post('usuarios_id')!="" and $this->input->post('usuarios_id')!=0) {
          $ingresar = $this->mdUser->edit();
        }
        if ($ingresar['data']) {
          $user_data = $this->modAuth->getUserData($this->input->post('usuarios_id'));
          $this->modAuth->fillSession($user_data['user_data']);
          $this->session->set_flashdata('success', 'Se ha actualizado con exito tus datos');
          redirect(base_url()."mi-cuenta/edit-account", $datos);
        }else {
            $this->session->set_flashdata('error', '!Ups, ocurrio un error al intentar guardar tus datos');
            redirect(base_url()."mi-cuenta/edit-account", $datos);
        }
      }else{
          $this->session->set_flashdata('error', '!Ups, ocurrio un error al intentar guardar tus datos. Al parecer es algun tipo de seguridad');
          redirect(base_url("mi-cuenta/edit-account"), $datos);
      }
      $this->restablecerDatosSession();

    }

    public function restablecerDatosSession(){
      //print_r($this->session->userdata('login_user'));
      //print '<br>'.PHP_EOL;

      $user_data = $this->modAuth->getUserData($this->session->userdata('login_user')['usuarios_id']);
      $user_data['user_data']['direcciones']=$user_data['user_address'];
      if($user_data['result']=='success') $this->modAuth->fillSession($user_data['user_data']);
      
      //print_r($this->session->userdata('login_user'));
      //exit();
    }

}
