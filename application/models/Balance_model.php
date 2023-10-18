<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Balance_model extends CI_Model {
    public function getAll($filtros=array()){
        $this->db->select('*');
        if (isset($filtros['limit'])) {
            $this->db->limit($filtros['limit'][0],$filtros['limit'][1]);
        }
        if (isset($filtros['where'])) {
            $this->db->where($filtros['where'][0],$filtros['where'][1]);
        }
        if ($_SESSION['tipo_accesos']!=1 && $_SESSION['tipo_accesos']!=0) {
            $this->db->where("balance_pagos_usuarios_id",$_SESSION['usuarios_id']);
        }else{
            $this->db->join("usuarios","balance_pagos.balance_pagos_usuarios_id=usuarios.usuarios_id","inner");
        }
        $this->db->order_by('balance_pagos_id', 'DESC');
        $balances = $this->db->get('balance_pagos');
        return $balances;
    }

    public function agregarSaldo($tipo=NULL,$pedido_id=0,$productos=array()){
        $datos = array();
        if ($tipo!=NULL) {
            
            $this->db->select('*');
            $this->db->where('pedidos_id', $pedido_id);
            $estatus_productos = $this->db->get('pedidos_estatus_productos');
            
            $this->db->select('pedidos_productos,pedidos_productos_cantidad');
            $this->db->where('pedidos_id', $pedido_id);
            $pr_pedido = $this->db->get('pedidos');
            $productos_arr = array();
            $productos_cant_arr = array();
            foreach ($pr_pedido->result_array() as $key => $value) {
                array_push($productos_arr, $value['pedidos_productos']);
                array_push($productos_cant_arr, $value['pedidos_productos_cantidad']);
            }
            
            $this->db->select('productos_id,productos_vendedor,productos_precio');
            $this->db->where_in('productos_id', $productos);
            $productos = $this->db->get('productos');

            $vendedores = array();

            foreach ($productos->result_array() as $key => $value) {
                if (!in_array($value['productos_vendedor'], $vendedores)) {
                    array_push($vendedores,$value['productos_vendedor']);
                }
            }

            $this->db->select('usuarios_id,usuarios_comision');
            $this->db->where_in('usuarios_id', $vendedores);
            $usuarios = $this->db->get('usuarios');

            $insert_bach = array();

            foreach ($usuarios->result_array() as $key3 => $value3) {
                $ok = 0;
                $this_bach = array();
                $this_bach['balance_pagos_concepto'] = "Pedido #".$pedido_id;
                $saldo = 0;
                foreach ($estatus_productos->result_array() as $key => $value) {
                    if ($value['estatus']=="Enviado") {
                        foreach ($productos->result_array() as $key2 => $value2) {
                            if ($value['productos_id']==$value2['productos_id'] && $value3['usuarios_id']==$value2['productos_vendedor']) {
                                $cantidad = 0;
                                for ($i=0; $i < count($productos_arr); $i++) {
                                    if ($productos_arr[$i]==$value2['productos_id']) {
                                        $cantidad = $productos_cant_arr[$i];
                                    }
                                }
                                $this_price=$value2['productos_precio']*$cantidad;
                                $saldo=$saldo+$this_price;
                                $ok = 1;
                            }
                        }
                    }
                }
                $descuento = $saldo/100;
                $descuento = $descuento*$value3['usuarios_comision'];
                
                $saldo = $saldo-$descuento;
                $saldo = $saldo+9900;

                $this_bach['balance_pagos_usuarios_id'] = $value3['usuarios_id'];
                $this_bach['balance_pagos_tipo'] = $tipo;
                $this_bach['balance_pagos_valor'] = $saldo;

                $this->db->select('*');
                $this->db->where('balance_pagos_usuarios_id', $this_bach['balance_pagos_usuarios_id']);
                $this->db->where('balance_pagos_tipo', $this_bach['balance_pagos_tipo']);
                $this->db->where('balance_pagos_valor', $this_bach['balance_pagos_valor']);
                $this->db->where('balance_pagos_concepto', $this_bach['balance_pagos_concepto']);
                $verify = $this->db->get('balance_pagos');
                
                if ($verify->num_rows() == 0 && $ok==1) {
                    array_push($insert_bach, $this_bach);
                }

            }
            
            if ($insert_bach!=array()) {
                $this->db->insert_batch('balance_pagos', $insert_bach);
            }

        }
    }

}