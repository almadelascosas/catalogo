<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct('auth');
        $this->load->helper(array('commun'));
        $this->load->model('usuarios_model', 'modUser');
        $this->load->model('auth_model', 'modAuth');
    }

    public function index()
    {
        $uri = explode("?",$_SERVER["REQUEST_URI"]);
        $req="";
        if (isset($uri[1])) $req = "?return_url=".$uri[1];
        redirect('auth/login'.$req);
    }

    public function login()
    {
        $this->load->view('login');
    }


    public function authenticate()
    {
        //Load model
        $this->load->model('auth_model');
        $result = $this->auth_model->authenticateUser($this->input->post('email'), $this->input->post('pass'));

        if ($result['result']) {            
            switch(intval($result['data']['user_data']['tipo_accesos'])){
                case 1:
                case 8:
                    $redirect_page='panel';
                    break;
                case 6:
                    $redirect_page='mi-cuenta/orders';
                    break;
                default:
                    $redirect_page='mi-cuenta/edit-account';
                    break;
            }
            redirect($redirect_page);
        } else {
            $datos['error'] = 1;
            $_SESSION['message_tipo']='error';
            $_SESSION['message']='<b>!Lo Sentimos...</b><br>No hemos encontrado usuario registrado';
            redirect("auth/login");
        }
    }

    public function remember()
    {
        //Load model
        $this->load->model('auth_model');

        //Check credentials
        $result = $this->auth_model->rememberUser($this->input->post('email'));

        //If return_url is set
        if ($this->input->post('return_url')!='') {
            $result['url'] = $this->input->post('return_url');
        }

        //Return JSON
        echo json_encode($result);
        return;
    }

    public function logout()
    {
        //Create logout logic.
        $this->session->sess_destroy();
        redirect("auth/login");
    }

    public function inscripcion($idcampania)
    {
        $datos = array();
        $datos['view'] = "campanias/inscripcion_campania";
        $datos['message'] = "";
        $this->load->model('campanias_model');
        $this->load->helper('commun');
        $datos['mi_campania'] = "";
        $mi_campania = $this->campanias_model->getCampania($idcampania);
        $datos['error'] = 0;
        $datos['mi_campania'] = array();
        if ($mi_campania['error']) {
            $datos['error'] = 1;
        } else {
            $datos['mi_campania'] = $mi_campania['data'];
        }
        $this->load->view('ajax_view', $datos);
    }

    public function inscribirse()
    {
        $return_data = array();
        $this->load->model('campanias_model');
        $ins_data = array();
        $ins_data['idcampania'] = $this->input->post('idcampania');
        $ins_data['idcliente'] = 0;
        $ins_data['telefono_cliente'] = $this->input->post('telefono_cliente');
        $ins_data['nombre_cliente'] = $this->input->post('nombre_cliente');
        $ins_data['observaciones'] = $this->input->post('observaciones');
        $telefono = trim($this->input->post('telefono_cliente'));
        $telefono = str_replace(' ', '', $telefono);
        $cliente = $this->db->where("REPLACE(telefonoFijo,' ', '') LIKE '%$telefono%'", null, false)->select('*')->from('clientes')->limit(1)->get()->row_array();
        //$return_data['query'] = $this->db->last_query();
        if (isset($cliente['idcliente'])) {
            $ins_data['idcliente'] = $cliente['idcliente'];
        }

        //Check si existe
        $existe = $this->db->where('idcliente', $cliente['idcliente'])->where('idcampania', $ins_data['idcampania'])
      ->from('campanias_clientes')->limit(1)->get()->num_rows();
        if ($existe>0) {
            //$return_data['query2'] = $this->db->last_query();
            $return_data['result'] = 2;
            $return_data['message'] = "<h2 class='datos_guardados'>Datos ya guardados</h2><p class='datos_guardados'>Ya estaba apuntado en esta campaña.<br>En breve contactaremos con usted. Muchas gracias</p>";
            echo json_encode($return_data);
            exit;
        }
        $this->db->trans_start();
        $sql = $this->db->insert('campanias_clientes', $ins_data);
        $return_data['result'] = 1;
        $return_data['message'] = "<h2 class='datos_guardados'>Datos guardados</h2><p class='datos_guardados'>En breve contactaremos con usted. Muchas gracias</p>";
        if (!$sql) {
            $return_data['result'] = 1;
            $return_data['message'] = "<p>No se han podido guardar los datos</p>";
        } else {

        //Guardamos la tarea!!
            if ($ins_data['idcliente']!=0) {
                $this->campanias_model->guardarTarea($this->input->post('idcampania'), $ins_data['idcliente'], $this->input->post('observaciones'));
            }

            if (!isset($cliente['idcliente'])) {
                //Hemos guardado la tarea pero no hemos encontrado el cliente.
                //Mandamos email
                $this->load->library('email');
                $this->email->initialize($this->config->item('email_config'));
                $this->email->to('aleman@sisfarma.es');
                $mi_campania = $this->campanias_model->getCampania($this->input->post('idcampania'));

                $this->email->subject('Cliente no reconocido en campaña');
                $this->email->from('crm@sisfarma.pro', 'CRM Sisfarma');
                $mensaje = "Campaña: ".$mi_campania['data']['nombreCampania'];
                $mensaje .= "\nCliente: ".$ins_data['nombre_cliente'];
                $this->email->message($mensaje);
                if (! $this->email->send()) {
                    echo $this->email->print_debugger();
                }
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $return_data['result'] = 0;
            $return_data['message'] = "<p>No se han podido guardar los datos</p>";
        }
        echo json_encode($return_data);
    }

    function registerSocialMedia(){
        $getUsersSM = $this->modUser->getUserSocialMedia($_POST['data']['providerId'], $_POST['data']['email']);

        if(count($getUsersSM)===0){
            if(isset($_POST['data']['displayName']) && $_POST['data']['displayName']!=='' && $_POST['data']['displayName']!==null){
                $nom = explode(' ', $_POST['data']['displayName']);
                $cantNom = count($nom);
                $userName = strtolower(str_replace(" ", ".", $_POST['data']['displayName'].'.'.$_POST['data']['providerId']));

                if($cantNom>=3){
                    $ultimo = $cantNom-1;
                    $penultimo = $cantNom-2;
                    $lastName = $nom[$penultimo].' '.$nom[$ultimo];
                    $firstName='';
                    for($i=0 ; $i<$penultimo ; $i++) $firstName.= ' '.$nom[$i];
                }else if($cantNom<3){
                    $firstName=$nom[0];
                    $lastName = $nom[1];                
                }    
            }else{
                $firstName= '(Sin Nombre)';
                $lastName = '(Sin Apellido)';
                $userName = $_POST['data']['email'];
            }            

            $pass = $this->modAuth->generatePassword();
            $passEncode = encodeItem($pass);

            $data=[
                'name' => $firstName,
                'lastname' => $lastName,
                'email' => $_POST['data']['email'],
                'username' => $userName,
                'password' => $passEncode,
                'tipo_accesos' => 6,
                'socialmedia_providerid' => $_POST['data']['providerId'],
                'socialmedia_uid' => $_POST['data']['uid'],
                'socialmedia_token' => $_POST['token']
            ];

            $id = $this->modUser->insertUserMediaSocial($data);

            $result = $this->modAuth->authenticateUser($_POST['data']['email'], $pass, $_POST['data']['providerId']);

            if ($result['result']===1) {
                $this->session->set_flashdata('success', '!Bienvenido, Ya eres parte de esta familia Alma De las Cosas');
                $this->session->set_flashdata('warning', 'Completa tus datos de informacion, para ayudarte en tus compras');
                redirect('mi-cuenta/edit-account');
            } else {
                $datos['error'] = 1;
                $this->load->view('login', $datos);
            }
        }else{
            $_SESSION['message_tipo']='danger';
            $_SESSION['message']='<b>!Lo Sentimos...</b><br> ya existe un registro con esta red social. <br> Intenta iniciar session';
            redirect("auth/login");
        }
    }

    public function loginSocialMedia()
    {
        $result = $this->modAuth->authenticateUserSocialMedia($_POST['data']['email'], $_POST['data']['providerId']);

        if ($result['result']) {
            if (isset($_POST['return_url']) && $_POST['return_url']!="") {
                $return = str_replace_first("/","",$_POST['return_url']);
                $redireccion = base_url().$return;
                redirect($redireccion);
            } else {
                redirect("mi-cuenta/orders");
            }
        } else {
            $datos['error'] = 1;
            //$this->load->view('login', $datos);
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
