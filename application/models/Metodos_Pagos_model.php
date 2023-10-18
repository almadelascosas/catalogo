<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Metodos_Pagos_model extends CI_Model {
	function __construct() {
		parent::__construct();
	}

	function tableName() {
		return 'alma_metodos_pagos';
	}

	function rules() {
		return [
			['alma_metodos_pagos_id', 'required'],
			['alma_metodos_pagos_titulo, alma_metodos_pagos_imagen, alma_metodos_pagos_template', 'text'],
		];
	}

	function getMetodoPagoById($id){
		return $this->db->where('alma_metodos_pagos_id', $id)->get($this->tableName())->result_array()[0];
	}

	function getAll($estado=''){
		if($estado!=='') $this->db->where('alma_metodos_pagos_estatus', $estado);
		return $this->db->get($this->tableName())->result_array();
	}


}