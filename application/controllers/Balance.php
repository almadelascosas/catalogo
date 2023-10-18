<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Balance extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('balance');
        $this->load->helper(array('commun'));
        $this->load->model("balance_model");
        $this->load->model("productos_model");
        $this->load->model("pedidos_model");
        $this->load->model("medios_model");
    }

    public function index()
    {
      $datos = array();
      $datos['view'] = "balance/index";
      $datos['css_data'] = array();
      $datos['js_data'] = array(
          'assets/js/pages/balance/index.js?v='.rand(99,9999),
      );
      $datos['balances'] = $this->balance_model->getAll();
      $this->load->view('normal_view', $datos);
    }

    public function solicitar_retiro(){
        $datos = array();
        $data = array(
            'balance_pagos_concepto' => 'Retiro',
            'balance_pagos_estatus' => 'pendiente',
            'balance_pagos_usuarios_id' => $_POST['idusu'],
            'balance_pagos_tipo' => 'debito',
            'balance_pagos_valor' => $_POST['valor_solicitado'],
        );
        $this->db->insert('balance_pagos', $data);
        
    }

    public function confirmar_retiro(){
        $datos = array();
        
        $data = array(
            'balance_pagos_estatus' => 'aprobado',
            'balance_pagos_nota' => $_POST['nota'],
        );
        
        $this->db->where('balance_pagos_id', $_POST['balance_id']);
        $this->db->update('balance_pagos', $data);

        echo "success";
    }

}