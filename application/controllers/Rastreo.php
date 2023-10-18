<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Rastreo extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('rastreo');
        $this->load->helper(array('commun'));
        $this->load->model("general_model");
    }

    public function index()
    {
        if (isset($_REQUEST['pedido_nro'])) {
            $datos = [
                'vista' => "front",
                'css_data' => [ 
                    'assets/css/pages/front/rastreo/detalle_pedido.css?'.rand() 
                ],
                'view' => "mi-cuenta/ordersview",
                'pedido' => $this->general_model->getOrderDetails($_GET['pedido_nro'])
            ];

            if ($datos['pedido']['data']!==array()) {
                $this->load->view('normal_view', $datos);
            }else{
                $_SESSION['mensaje_error'] = "Ups, no encontramos tu pedido, verifica los datos e intenta nuevamente";
                redirect(base_url("rastrea-tu-pedido"));
            }
        }else if (isset($_POST['id']) && isset($_POST['correo'])) {
            $id = $_POST['id'];
            $correo = $_POST['correo'];
            $datos = array();
            $datos['vista'] = "front";
            $datos['css_data'] = array(
                base_url().'assets/css/pages/front/rastreo/detalle_pedido.css?v='.rand(99,9999),
            );
            $datos['view'] = "mi-cuenta/ordersview";
            $datos['pedido'] = $this->general_model->getOrderDetails($id,$correo);
            if ($datos['pedido']['data']!=array()) {
                $this->load->view('normal_view', $datos);
            }else{
                $_SESSION['mensaje_error'] = "Ups, no encontramos tu pedido, verifica los datos e intenta nuevamente";
                redirect(base_url()."rastrea-tu-pedido");
            }
        }else{
            $_SESSION['mensaje_error'] = "Ups, no encontramos tu pedido, verifica los datos e intenta nuevamente";
            redirect(base_url("rastrea-tu-pedido"));
        }
    }

    public function rastreaTuPedido(){
        $datos = [
            'vista' => "front",
            'view' => "mi-cuenta/rastrea-tu-pedido",
            'css_data' => [ 'assets/css/pages/front/rastreo/index.css?'.rand() ]
        ];
        $this->load->view('normal_view',$datos);
    }

}
