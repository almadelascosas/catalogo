<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//require_once(APPPATH.'core/classes/User.php');

class Auth_model extends CI_Model {

	//Function that authenticate User from login form.
	function authenticateUser($email, $password, $socMed=null){

		//Initialize return values
		$return_data = array();
		$return_data['result'] = "";
		$return_data['message'] = "";
		$return_data['url'] = "";
		
		//Search a valid username-password pair on database
		$this->db->select('usuarios.usuarios_id')
		         ->where('usuarios.email', $email)
		         ->where('usuarios.socialmedia_providerid', $socMed)
		         ->where('usuarios.password',encodeItem($password));

		$sel_user = $this->db->get('usuarios');

		//If only 1 registry found, success
		if($sel_user->num_rows()=='1'){

			//Get user_id.
			$user_id = $sel_user->row_array();


			//Get User Data
			$user_data = $this->getUserData($user_id['usuarios_id']);
			//Check User Data


			if($user_data['result']=='success'){

				//Fill session data
				$user_data['user_data']['direcciones']=$user_data['user_address'];
				$this->fillSession($user_data['user_data']);


				//Log successfull login
				$this->logAuthenticationSuccess($user_id['usuarios_id']);


				//Set return data
				$return_data['result'] = 1;
				$return_data['data'] = $user_data;

				$return_data['url_redirect'] = $this->session->userdata('url_redirect');
			}
			else{
				//Log error
				$this->logAuthenticationFail($email, $password, $user_data['message']);

				//Set return data
				$return_data['result'] = 0;
				$return_data['message'] = $user_data['message'];
			}
		}else{
			//Log error
			$this->logAuthenticationFail($email, $password, $this->lang->line('error_user_not_found'));

			//Set return data
			$return_data['result'] = 0;
			$return_data['message'] = $this->lang->line('error_user_not_found');
		}

		//Return data
		//print_r($return_data);
		return $return_data;
	}

	//Function that resets user password and send a new one
	function rememberUser($email){
		//Instantiate CI
		$CI =& get_instance();
		$CI->load->model('email_model');

		//Initialize return values
		$return_data = array();
		$return_data['result'] = "";
		$return_data['message'] = "";
		$return_data['url'] = "";

		//Check if username or email are null
		if($email==''){
			//Set return data
			$return_data['result'] = "error";
			$return_data['message'] = $this->lang->line('error_data_incomplete');
			return $return_data;
		}


		//Search for user on users table
		$this->db->select('u.user_id, u.username, ud.email, ud.name');
		$this->db->where('ud.email', $email);
		$this->db->where('u.active', 1);
		$this->db->join('users_data AS ud', 'ud.user_id=u.user_id');
		$sel_userdata = $this->db->get('users AS u');

		//Check num of results
		if($sel_userdata->num_rows()=='1'){
			//Obtain userdata
			$user_data = $sel_userdata->row_array();

			//Generate new password
			$new_password = $this->generatePassword();

			//update password
			$result = $this->updatePasswordUser($user_data['user_id'], $new_password);

			//If update OK
			if($result){
				//Prepare email
				$subject = $this->config->item('app_company')." - ".$this->lang->line('txt_remember_password');
				$email_data = array( 'name'=>$user_data['name'],
									'username'=>$user_data['username'],
									'password'=>$new_password,
									'url'=>base_url());
				//Send
				$send_email = $CI->email_model->sendTemplate($user_data['name'], $user_data['email'], $subject, $email_data, 'auth_remember_password');

				//check result
				if($send_email){
					//Set return data
					$return_data['result'] = "success";
					$return_data['message'] = $this->lang->line('conf_data_send');
				}
				else{
					//Set return data
					$return_data['result'] = "error";
					$return_data['message'] = $this->lang->line('error_unable_send_email');
				}
			}
			else{
				//Set return data
				$return_data['result'] = "error";
				$return_data['message'] = $this->lang->line('error_password_not_updated');
			}
		}
		//If 0 or more than 1, error
		else{
			//Set return data
			$return_data['result'] = "error";
			$return_data['message'] = $this->lang->line('error_unable_find_email');
		}

		return $return_data;
	}

	//Function that generates a new user
	function registerUser($email, $username='', $password='', $is_admin='0'){
		//Instantiate CI
		$CI =& get_instance();
		$CI->load->model('email_model');

		//Initialize return values
		$return_data = array();
		$return_data['result'] = "";
		$return_data['message'] = "";
		$return_data['url'] = "";

		//Check if email is null
		if($email==''){
			//Set return data
			$return_data['result'] = "error";
			$return_data['message'] = $this->lang->line('error_data_incomplete');
			return $return_data;
		}

		//Check if email exist
		$this->db->select('user_id');
		$this->db->where('email', $email);
		$sel_email = $this->db->get('users_data');

		if($sel_email->num_rows()=='0'){

			//Generate new password
			if($password==''){
				$new_password = $this->generatePassword();
			}
			else{
				$new_password = $password;
			}


			//Insert userdata
			$ins_data = array();
			$ins_data['hash'] = encodeItem($new_password.$email.date('YmdHis'));
			$ins_data['username'] = $email;
			$ins_data['is_admin'] = $is_admin;
			$ins_user = $this->db->insert('users', $ins_data);

			//If user is inserted ok
			if($ins_user){
				//Get User_id
				$user_id = $this->db->insert_id();

				//Add email to users_data
				$ins_data = array();
				$ins_data['user_id'] = $user_id;
				if($username!=''){
					$ins_data['name'] = $username;
				}
				$ins_data['email'] = $email;
				$ins_data['profile_id'] = 3;
				$ins_usersdata = $this->db->insert('users_data', $ins_data);

				//update password
				$result = $this->updatePasswordUser($user_id, $new_password);

				//If update OK
				if($result){
					//Prepare email
					$subject = $this->config->item('app_company')." - ".$this->lang->line('txt_user_registration');
					$email_data = array('username'=>$email,
										'password'=>$new_password,
										'url'=>base_url());
					//Send
					$send_email = $CI->email_model->sendTemplate($email, $email, $subject, $email_data, 'auth_new_registration');

					//check result
					if($send_email){
						//Set return data
						$return_data['result'] = "success";
						$return_data['message'] = $this->lang->line('conf_data_send');
					}
					else{
						//Set return data
						$return_data['result'] = "error";
						$return_data['message'] = $this->lang->line('error_unable_send_email');
					}
				}
				else{
					//Set return data
					$return_data['result'] = "error";
					$return_data['message'] = $this->lang->line('error_password_not_updated');
				}
			}
			else{
				//Set return data
				$return_data['result'] = "error";
				$return_data['message'] = $this->lang->line('error_user_not_created');
			}
		}
		//Email existe
		else{
			//Set return data
			$return_data['result'] = "error";
			$return_data['message'] = $this->lang->line('error_email_exists');
		}

		return $return_data;
	}

	function updatePasswordUser($user_id, $new_password){
		//Update password of selected user
		$upd_data = array();
		$upd_data['password'] = $this->encodeItem($new_password);

		$this->db->where('user_id', $user_id);
		$upd_password = $this->db->update('users', $upd_data);

		return $upd_password;
	}

	function logAuthenticationSuccess($user_id){
		//Fill data to insert.
		$ins_data = array();
		$ins_data['user_id'] = $user_id;
		$ins_data['date'] = date('Y-m-d');
		$ins_data['time'] = date('H:i:s');
		$ins_data['user_agent'] = $this->input->user_agent();
		$ins_data['ip'] = $this->input->ip_address();

		//Insertamos datos.
		$ins_log = $this->db->insert('auth_login_success', $ins_data);

		return true;
	}

	function logAuthenticationFail($username, $password, $reason){
		//Fill data to insert
		$ins_data = array();
		$ins_data['username'] = $username;
		$ins_data['password'] = $password;
		$ins_data['reason'] = $reason;
		$ins_data['date'] = date('Y-m-d');
		$ins_data['time'] = date('H:i:s');
		$ins_data['user_agent'] = $this->input->user_agent();
		$ins_data['ip'] = $this->input->ip_address();

		//Insertamos datos.
		$ins_log = $this->db->insert('auth_login_fail', $ins_data);

		return true;
	}

	function fillSession($user_data){
		//Session SET
		$this->session->set_userdata($user_data);
		$this->session->set_userdata('login_user', $user_data);
		$this->session->set_userdata('logged_in', '1');
		$this->session->set_userdata('departamento_session', $_SESSION['usuarios_departamento']);
		$this->session->set_userdata('municipio_session', $_SESSION['usuarios_municipio']);

		if ($_SESSION['usuarios_municipio']!=0) {
			$this->db->select("m.municipio,d.departamento");
			$this->db->where("m.id_municipio",$_SESSION['usuarios_municipio']);
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
		//Debemos buscar sus permisos y guardarlos
		/*$this->db->select('LOWER(p.rutaPermiso) as rutaPermiso')
			->from("permisos as p");
		if($user_data['tipo']!=0)
		{
			$this->db->where('pu.idusuario', $user_data['idusuario'])
			->join('permisos_usuarios as pu', 'p.idpermiso = pu.idpermiso');
		$los_permisos = indexar_array_por_campo( $this->db->get()->result_array(),"rutaPermiso");
		$this->session->set_userdata('mis_permisos', $los_permisos );
		$tmp = array_reverse($los_permisos);
	}
	*/
		if ($_SESSION['tipo_accesos']===6) {
			$this->session->set_userdata('url_redirect',"mi-cuenta");
		}else{
			$this->session->set_userdata('url_redirect',"panel");
		}
	}

	function getUserData($user_id){
		//Initialize return values
		$return_data = array();
		$return_data['result'] = "";
		$return_data['message'] = "";
		$return_data['user_data'] = array();

		//Obtenemos datos del trabajador
		$this->db->select('*');
		$this->db->where('users.usuarios_id', $user_id);
		$sel_datos = $this->db->get('usuarios AS users');

		//Obtenemos direcciones del usuario
		$sel_direcciones = $this->db->where('id_usuario', $user_id)->order_by('id_dir', 'ASC')->get('direccion_usuario');

		//Comprobamos los registros
		if($sel_datos->num_rows()!='1'){
			//Set return data
			$return_data['result'] = 'error';
			$return_data['message'] = $this->lang->line('error_user_data_not_found');
		}
		else{
			//Set return data
			$return_data['result'] = 'success';
			$return_data['user_data'] = $sel_datos->row_array();
			$return_data['user_address'] = $sel_direcciones->result_array();
		}

		//Return data
		return $return_data;
	}



	function generatePassword(){
		//Feed strings
		$group1 = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$group2 = "$&#@";
		$group3 = "0123456789";

		$password = "";

		for($i=1;$i<5;$i++){
			$password .= substr($group1,rand(0,strlen($group1)),1);
		}
		for($i=1;$i<3;$i++){
			$password .= substr($group2,rand(0,strlen($group2)),1);
		}
		for($i=1;$i<5;$i++){
			$password .= substr($group3,rand(0,strlen($group3)),1);
		}

		return $password;
	}

	function authenticateUserSocialMedia($email, $provider){
		$return_data = array();
		$return_data['result'] = "";
		$return_data['message'] = "";
		$return_data['url'] = "";
		
		$this->db->select('usuarios.usuarios_id');
		$this->db->where('usuarios.email', $email);
		$this->db->where('usuarios.socialmedia_providerid', $provider);

		$sel_user = $this->db->get('usuarios');

		if($sel_user->num_rows()=='1'){
			$user_id = $sel_user->row_array();
			$user_data = $this->getUserData($user_id['usuarios_id']);
			$user_data['user_data']['direcciones']=$user_data['user_address'];
			if($user_data['result']=='success'){
				$this->fillSession($user_data['user_data']);
				$this->logAuthenticationSuccess($user_id['usuarios_id']);
				$return_data['result'] = 1;
				$return_data['url_redirect'] = base_url().$this->session->userdata('url_redirect');
			}
			else{
				$this->logAuthenticationFail($email, $password, $user_data['message']);
				$return_data['result'] = 0;
				$return_data['message'] = $user_data['message'];
			}
		}
		else{
			$password='sinContrasenia';
			$this->logAuthenticationFail($email, $password, $this->lang->line('error_user_not_found'));
			$return_data['result'] = 0;
			$return_data['message'] = $this->lang->line('error_user_not_found');
		}

		//Return data
		return $return_data;
	}

	function deleteOldSessions($fecha){
		$this->db->where('date <=', $fecha)->delete('auth_login_success');
		$this->db->where('date <=', $fecha)->delete('auth_login_fail');
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
