<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Api extends MY_Controller
{
    public function __construct()
    {
        define('API_KEY','4MEB88QRJZ94KMYTXJL351TD5OVTU397');
        $SERVER_AUTH_USER = "almajin";
        $SERVER_AUTH_TOKEN = "Developer.123";
        parent::__construct('api');
        $this->load->helper(array('commun'));
        $this->load->model("auth_model", "modAuth");
        $this->load->model("usuarios_model", "modUsu");
        $this->load->model("Address_model", "modAddr");
        $this->load->model("general_model");
        $this->load->model("categorias_model");
        $this->load->model("productos_model");
        $this->load->model("mailing_model");
        $this->load->model("pedidos_model");
        $this->load->model("metodos_Pagos_model", "mdMpago");
        $this->load->model("Departamentos_model", "mdDpto");
        $this->load->model("Municipios_model", "mdMuni");
        $this->load->model("Address_model", "mdAddUse");
        $this->load->helper('commun');
    }


    public function method_default()
    {
        $return_data=['error' => 1,
                   'mensaje' => "No existe metodo al que esta intentando ingresar",
                   'data' => NULL];
        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    public function index()
    {
      
    }

    public function deleteSession(){
        log_message('info', '<--- INICIO EJECUCION API : deleteSession - '.date('d-m-Y H:i:s'));
        $today = date('Y-m-d');
        $yesterday = date("Y-m-d H:i:s", strtotime($today."- 2 days"));

        $this->modAuth->deleteOldSessions($yesterday);
        $carpeta = FCPATH.'application/ci_sessions/*';

        $files = glob($carpeta);
        foreach($files as $file){
            $fechaMod = date("d-m-Y H:i:s", filectime($file));

            $fechaFile = strtotime($fechaMod);
            $strFecha = strtotime($yesterday); 

            if($fechaFile <= $strFecha){ 
                //print $file.' : '.$fechaMod.'<br>';
                unlink($file); 
            }
        }
        log_message('info', '<--- FINALIZACION EJECUCION API : deleteSession - '.date('d-m-Y H:i:s'));
    }

    public function fechahora(){
        print date('d-m-y H:i:s');
    }

    public function versionphp(){
        /**
         * Informacion PHP
         *
         * Este metodo ss usado cuando deseamos obtener informacion
         * de el servidor PHP (version PHP, phpmyadmin, etc)
         *
         * @access public
         * @return view Vista de la informacion de servidor php
        */
        phpinfo();
    }

    function getpost(){
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
            header('Access-Control-Allow-Credentials: true');
            header('Access-Control-Max-Age: 86400');    // cache for 1 day
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
                header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
        
            if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
                header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
        
         
        }
        $postdata = file_get_contents("php://input");

        /** Variables Session **/

        

        return $postdata;
    }

    public function registro(){
        
        if (!isset($_REQUEST['email']) || !isset($_REQUEST['name']) || !isset($_REQUEST['lastname']) || !isset($_REQUEST['password']) ) {
            $ingresar['error']=1;
            $ingresar['mensaje']="Debes llenar todos los campos requeridos.";
            $ingresar['data'] = NULL;
            echo json_encode($ingresar, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $_REQUEST["tipo_accesos"] = 6;

        $ingresar = array();
        $ingresar['error']=0;
        $ingresar['mensaje']="";
        $this->db->select('usuarios_id');
        $this->db->where('email', $_REQUEST['email']);
        $usuario = $this->db->get('usuarios');
        if ($usuario->num_rows() > 0) {
            $ingresar['error']=1;
            $ingresar['mensaje']="Este usuario ya existe, intenta con otro email.";
            $ingresar['data'] = NULL;
            echo json_encode($ingresar, JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            $query = $this->db->query('SHOW COLUMNS FROM usuarios');
            $valores = $query->result_array();
            $data = array();
            foreach ($valores as $key => $value) {
                $field = $value["Field"];
                if ($value["Field"]!="usuarios_id" && $value["Field"]!="password" && isset($_REQUEST[$value["Field"]]) && $_REQUEST[$value["Field"]]!=NULL) {
                  $data += [$value["Field"]=>$_REQUEST[$value["Field"]]];  
                }
            }
            $data += ["password"=>encodeItem($_REQUEST["password"])];
            $ingresar['data'] = $this->db->insert('usuarios', $data);
            $ingresar['mensaje']="Registro Exitoso, dirigete al login para iniciar sesion.";
            echo json_encode($ingresar, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    public function updateDataUser($idUser){
        /**
         * Actualizacion datos de Usuario
         *
         * Este metodo es usado para la actualizacion de datos del usuario
         * este puede enviar desde 1 a todos los campos en una sola sentencia
         *
         * @access public
         * @param int $idUser ID del usuario a modificar
         * @param array $data datos a modificar, puede enviar desde un solo datos, hasta todos los datos necesarios
         * @return array
         */

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);

        $dataUser = $this->db->where('usuarios_id', $idUser)->get('usuarios')->row_array();

        if(!isset($dataUser['usuarios_id'])){
            $return_data=[
                'error' => 1,
                'mensaje' => "Usuario con id ".$idUser." no existe",
                'data' => NULL
            ];
        }else{          
            $postdata['data']['usuarios_id']=$idUser;
            $this->modUsu->editData($postdata['data']);

            $return_data=[
                'error' => 0,
                'mensaje' =>  "Actualizacion de usuario exitosa",
                'data' => NULL
            ];
        }        
        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
    }

    
    public function login(){
        /**
         * Logueo usuario
         *
         * Este metodo es usado para Logueo de usuario con datos de Email y Contraseña
         * primer logueo realizado, antes de redes sociales
         *
         * @access public
         * @param string $email correo electronico registrado
         * @param string $password contraseña secreta
         * @return array $return [error=(0=Encontrado, 1=No encontrado), mensaje=String, data=array usuario] 
         */

        $return_data=array();
        if (!isset($_REQUEST['email']) || !isset($_REQUEST['password']) ) {
            $ingresar['error']=1;
            $ingresar['mensaje']="Debes llenar todos los campos requeridos.";
            $ingresar['data'] = NULL;
            echo json_encode($ingresar, JSON_UNESCAPED_UNICODE);
            exit;
        }

        $this->db->select('usuarios.usuarios_id')
                 ->where('usuarios.email', $_REQUEST['email'])
                 ->where('usuarios.password',encodeItem($_REQUEST['password']))
                 ->where('socialmedia_providerid', null);
        $sel_user = $this->db->get('usuarios');

        if($sel_user->num_rows()=='1'){
            //Get user_id.
            $user_id = $sel_user->row_array();

            //Get User Data
            $user_data = $this->getUserData($user_id['usuarios_id']);
            
            //Check User Data
            if($user_data['result']=='success'){                
                //Obtenemos direcciones del usuario
                $user_data['user_data']['direcciones']=$this->modAddr->getAddresByUser($user_id['usuarios_id']);

                //Fill session data
                $this->fillSession($user_data['user_data']);
                //Log successfull login
                $this->logAuthenticationSuccess($user_id['usuarios_id']);
                
                foreach ($_SESSION as $key => $value) {
                    $return_data[$key] = $value;
                }

                $return_data["departamento_session"] = $_SESSION['usuarios_departamento'];
                $return_data["municipio_session"] = $_SESSION['usuarios_municipio'];

                if(intval($_SESSION['usuarios_departamento'])!==0){
                    $dpto = $this->mdDpto->getById($_SESSION['usuarios_departamento']);
                    $return_data["departamento_nombre_session"] = $dpto[0]['departamento'];
                }

                if(intval($_SESSION['usuarios_municipio'])!==0){
                    $muni = $this->mdMuni->getById($_SESSION['usuarios_municipio']);
                    $return_data["municipio_nombre_session"] = $muni[0]['municipio'];
                }

                unset($return_data['url_redirect']);
                unset($return_data['tipo_accesos']);
                unset($return_data['usuarios_comision']);

                //Set return data
                $return_data['error'] = 0;
                $return_data['mensaje'] = "Autenticacion Exitosa";
                $return_data['data'] = $user_data['user_data'];
            }
            else{
                //Log error
                $this->logAuthenticationFail($_REQUEST['email'], $_REQUEST['password'], $user_data['mensaje']);

                //Set return data
                $return_data['error'] = 1;
                $return_data['mensaje'] = "Los datos ingresados son incorrectos, intente de nuevo";
                $return_data['data'] = NULL;
            }
        }else{
            //Log error
            $this->logAuthenticationFail($_REQUEST['email'], $_REQUEST['password'], $this->lang->line('error_user_not_found'));

            //Set return data
            $return_data['error'] = 1;
            $return_data['mensaje'] = "Los datos ingresados son incorrectos, intente de nuevo";
            $return_data['data'] = NULL;
        }

        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
        
    }

    public function remenberme(){
        $ingresar = array();

        if (!isset($_REQUEST['email']) || $_REQUEST['email']==NULL) {
            $ingresar['error']=1;
            $ingresar['mensaje']="Debes ingresar un correo.";
            echo json_encode($ingresar, JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $ingresar['error']=0;
        $ingresar['mensaje']="";
        $this->db->select('usuarios_id');
        $this->db->where('email', $_REQUEST['email']);
        $usuario = $this->db->get('usuarios');
        if ($usuario->num_rows() > 0) {
            $ingresar['error']=0;
            $ingresar['mensaje']="El enlace de recuperación de contraseña se ha enviado a tu correo, por favor verifica.";
            echo json_encode($ingresar, JSON_UNESCAPED_UNICODE);
            exit;
        }else{
            $ingresar['error']=1;
            $ingresar['mensaje']="El correo ingresado no se encuentra registrado en nuestra base de datos.";
            echo json_encode($ingresar, JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    function getUserData($user_id)
    {
        //Initialize return values
        $return_data = array();
        $return_data['result'] = "";
        $return_data['mensaje'] = "";
        $return_data['user_data'] = array();

        //Obtenemos datos del trabajador
        $this->db->select('*');
        $this->db->where('users.usuarios_id', $user_id);
        $sel_datos = $this->db->get('usuarios AS users');
        //Comprobamos los registros
        if($sel_datos->num_rows()!='1'){
            //Set return data
            $return_data['result'] = 'error';
            $return_data['mensaje'] = $this->lang->line('error_user_data_not_found');
        }
        else{
            //Set return data
            $return_data['result'] = 'success';
            $return_data['user_data'] = $sel_datos->row_array();
        }

        //Return data
        return $return_data;
    }
    
    function fillSession($user_data){
        //Session SET
        $this->session->set_userdata($user_data);
        $this->session->set_userdata('logged_in', '1');
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

    public function obtenerSlides(){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $return = array();

        $this->db->select('banner_app.*,medios.medios_url as image_url');
        $this->db->join('medios','medios.medios_id=banner_app.banner_app_imagen_url','left');
        $banner_app = $this->db->get('banner_app');

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();

        foreach ($banner_app->result_array() as $key => $value) {
            $tipo = '';
            if ($value['banner_app_tipo']==0) {
                $tipo = 'Categoria';
            }elseif ($value['banner_app_tipo']==1) {
                $tipo = 'Producto';
            }else{
                $tipo = 'Enlace Externo';
            }
            array_push($return["data"], array(
                'url_imagen' => base_url().$value['image_url'],
                'texto' => $value['banner_app_texto'],
                'tipo_banner' => $tipo,
                'enlace_externo' => $value['banner_app_enlace_redireccion'],
                'id_redireccion' => $value['banner_app_id_redireccion'],
            ));
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
 
    }
    
    public function obtenerCategorias(){
        
        $return = array();
        $filtros = array();
        
        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        if (isset($postdata->filtros)) {
            $postdata = $postdata->filtros;
    
            foreach ($postdata as $key => $value) {
                if ($key=="limit") {
                    $filtros['limit'] = array();
                    $filtros['limit'] = $value;
                }
                if ($key=="where_arr") {
                    $filtros['where_arr'] = array();
                    foreach ($value as $key2 => $value2) {
                        $filtros['where_arr'][$key2] = $value2;
                    }
                }
            }
        }
        if (isset($postdata['filtros'])) {
            $postdata = $postdata['filtros'];
            foreach ($postdata as $key => $value) {
                if ($key=="limit") {
                    $filtros['limit'] = array();
                    $filtros['limit'] = $value;
                }
                if ($key=="where_arr") {
                    $filtros['where_arr'] = array();
                    foreach ($value as $key2 => $value2) {
                        $filtros['where_arr'][$key2] = $value2;
                    }
                }
            }
        }
        
        $categorias = $this->categorias_model->getAll($filtros);

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();

        foreach ($categorias->result_array() as $key => $value) {
            $imagen = '';
            if ($value['medios_url']==NULL || $value['medios_url']=="") {
                $imagen = base_url()."assets/img/Not-Image.png";
            }else{
                $imagen = base_url().$value['medios_url'];
            }
            array_push($return["data"], array(
                'categorias_id' => $value['categorias_id'],
                'categorias_padre_id' => $value['categorias_padre'],
                'categorias_nombre' => $value['categorias_nombre'],
                'categorias_imagen' => $imagen
            ));
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
 
    }
    
    public function obtenerUltimosVistos(){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        
        $return = array();

        $filtros = array();
        $productos = $this->productos_model->getIdsLatestViews();

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();

        foreach ($productos->result_array() as $key => $value) {
            $imagen = '';
            if ($value['medios_url']==NULL || $value['medios_url']=="") {
                $imagen = base_url()."assets/img/Not-Image.png";
            }else{
                $imagen = base_url().$value['medios_url'];
            }
            
            $ubicaciones = array();

            $ubi = explode("/",$value['productos_ubicaciones_envio']);

            for ($i=0; $i < count($ubi); $i++) {
                $ub = explode(",",$ubi[$i]);
                if (isset($ub[1])) {
                    array_push($ubicaciones,array(
                        "departamento" => $ub[0],
                        "municipio" => $ub[1]
                    ));
                }
            }
            $entrega_local_arr = array( 
                "", 
                "Mismo día", 
                "Recíbelo mañana", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            $entrega_nacional_arr = array(
                "", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            if ($value['productos_entrega_local']!="") {
                $entrega_local = $entrega_local_arr[$value['productos_entrega_local']];
            }
            if ($value['productos_entrega_nacional']!="") {
                $entrega_nacional = $entrega_nacional_arr[$value['productos_entrega_nacional']];
            }

            array_push($return["data"], array(
                'productos_id' => $value['productos_id'],
                'productos_titulo' => $value['productos_titulo'],
                'productos_enlace_imagen' => $imagen,
                'productos_vendedor' => $value['usuarios_id'],
                'productos_nombre_vendedor' => $value['name'],
                'productos_envio_local' => $value['productos_envio_local'],
                'productos_valor_envio_local' => $value['productos_valor_envio_local'],
                'productos_valor_envio_local_seteado' => "$ ".number_format($value['productos_valor_envio_local'], 0, ',', '.'),
                'productos_ubicaciones' => $ubicaciones,
                'productos_entrega_local' => $entrega_local,
                'productos_entrega_nacional' => $entrega_nacional,
                'productos_envio_nacional' => $value['productos_envio_nacional'],
                'productos_valor_envio_nacional' => $value['productos_valor_envio_nacional'],
                'productos_valor_envio_nacional_seteado' => "$ ".number_format($value['productos_valor_envio_nacional'], 0, ',', '.'),
                'productos_precio' => "$ ".number_format($value['productos_precio'], 0, ',', '.'),
                'productos_precio_simple' => $value['productos_precio']
            ));
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }
    
    public function obtenerRecomendados(){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        
        $return = array();

        $filtros = array();

        $filtros['orderby'] = array("ventas", "DESC");
        $filtros['where'] = array("productos_estatus", 1);
        $page=1;
        $limite=array(10,0);

        $productos = $this->productos_model->getAll($filtros,$page,$limite);

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();

        foreach ($productos->result_array() as $key => $value) {
            $imagen = '';
            if ($value['medios_url']==NULL || $value['medios_url']=="") {
                $imagen = base_url()."assets/img/Not-Image.png";
            }else{
                $imagen = base_url().$value['medios_url'];
            }
            
            $ubicaciones = array();

            $ubi = explode("/",$value['productos_ubicaciones_envio']);

            for ($i=0; $i < count($ubi); $i++) {
                $ub = explode(",",$ubi[$i]);
                if (isset($ub[1])) {
                    array_push($ubicaciones,array(
                        "departamento" => $ub[0],
                        "municipio" => $ub[1]
                    ));
                }
            }
            $entrega_local_arr = array( 
                "", 
                "Mismo día", 
                "Recíbelo mañana", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            $entrega_nacional_arr = array(
                "", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            if ($value['productos_entrega_local']!="") {
                $entrega_local = $entrega_local_arr[$value['productos_entrega_local']];
            }
            if ($value['productos_entrega_nacional']!="") {
                $entrega_nacional = $entrega_nacional_arr[$value['productos_entrega_nacional']];
            }

            array_push($return["data"], array(
                'productos_id' => $value['productos_id'],
                'productos_titulo' => $value['productos_titulo'],
                'productos_enlace_imagen' => $imagen,
                'productos_vendedor' => $value['usuarios_id'],
                'productos_nombre_vendedor' => $value['name'],
                'productos_envio_local' => $value['productos_envio_local'],
                'productos_valor_envio_local' => $value['productos_valor_envio_local'],
                'productos_valor_envio_local_seteado' => "$ ".number_format($value['productos_valor_envio_local'], 0, ',', '.'),
                'productos_ubicaciones' => $ubicaciones,
                'productos_entrega_local' => $entrega_local,
                'productos_entrega_nacional' => $entrega_nacional,
                'productos_envio_nacional' => $value['productos_envio_nacional'],
                'productos_valor_envio_nacional' => $value['productos_valor_envio_nacional'],
                'productos_valor_envio_nacional_seteado' => "$ ".number_format($value['productos_valor_envio_nacional'], 0, ',', '.'),
                'productos_precio' => "$ ".number_format($value['productos_precio'], 0, ',', '.'),
                'productos_precio_simple' => $value['productos_precio']
            ));
        
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerBannerNoticias(){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $return = array();

        $this->db->select('banner_home_noticias.*,medios.medios_url as image_url');
        $this->db->join('medios','medios.medios_id=banner_home_noticias.banner_home_noticias_imagen','left');
        $banner_app = $this->db->get('banner_home_noticias');

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();

        foreach ($banner_app->result_array() as $key => $value) {
            
            $imagen = '';
            if ($value['image_url']==NULL || $value['image_url']=="") {
                $imagen = base_url()."assets/img/Not-Image.png";
            }else{
                $imagen = base_url().$value['image_url'];
            }

            array_push($return["data"], array(
                'banner_home_noticias_texto' => $value['banner_home_noticias_texto'],
                'banner_home_noticias_imagen' => $imagen
            ));
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
 
    }
    
    public function obtenerCategoriasVendor(){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $return = array();

        $this->db->select('usuarios_categorias_id');
        $vendor = $this->db->get('usuarios');

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();

        $categoriasArr = array();

        foreach ($vendor->result_array() as $key => $value) {
            if (!in_array($value['usuarios_categorias_id'], $categoriasArr)) {
                array_push($categoriasArr, $value['usuarios_categorias_id']);
            }
        }

        $this->db->select('*');
        $this->db->where_in('categorias_id',$categoriasArr);
        $this->db->where_not_in('categorias_id', 15);
        $categorias = $this->db->get("categorias");

        foreach ($categorias->result_array() as $key => $value) {
            array_push($return["data"], array(
                'categorias_id' => $value['categorias_id'],
                'categorias_nombre' => $value['categorias_nombre']
            ));
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
 
    }
    
    public function obtenerVendedorPorCategoria(){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        if (isset($_REQUEST['categorias_id'])) {
            $return = array();
    
            $return['error'] = 0;
            $return['mensaje'] = "Consulta realizada con exito";
            $return['data'] = array();
            
            $this->db->select('*');
            $this->db->join("medios","medios.medios_id=usuarios.image_profile","left");
            $this->db->where('usuarios_categorias_id',$_REQUEST['categorias_id']);
            $vendor = $this->db->get("usuarios");
    
            foreach ($vendor->result_array() as $key => $value) {
                $imagen = '';
                if ($value['medios_url']==NULL || $value['medios_url']=="") {
                    $imagen = base_url()."assets/img/Not-Image.png";
                }else{
                    $imagen = base_url().$value['medios_url'];
                }
                array_push($return["data"], array(
                    'usuarios_id' => $value['usuarios_id'],
                    'nombre_vendedor' => $value['name'],
                    'apellido_vendedor' => $value['lastname'],
                    'imagen_perfil' => $imagen
                ));
            }

        }else{
            $return = array();
    
            $return['error'] = 1;
            $return['mensaje'] = "Debes enviar el ID de la categoria";
            $return['data'] = array();

        }


        echo json_encode($return, JSON_UNESCAPED_UNICODE);
 
    }

    public function obtenerNuevos(){
        
        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $return = array();

        $filtros = array();
        $page = 1;
        $limite = array(10,0);

        $filtros['where'] = array("productos_estatus", 1);
        $productos = $this->productos_model->getAll($filtros,$page,$limite);
        print $this->db->last_query();
        exit();

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['data'] = array();

        foreach ($productos->result_array() as $key => $value) {
            $imagen = '';
            if ($value['medios_url']==NULL || $value['medios_url']=="") {
                $imagen = base_url()."assets/img/Not-Image.png";
            }else{
                $imagen = base_url().$value['medios_url'];
            }
            $ubicaciones = array();

            $ubi = explode("/",$value['productos_ubicaciones_envio']);

            for ($i=0; $i < count($ubi); $i++) {
                $ub = explode(",",$ubi[$i]);
                if (isset($ub[1])) {
                    array_push($ubicaciones,array(
                        "departamento" => $ub[0],
                        "municipio" => $ub[1]
                    ));
                }
            }

            $entrega_local_arr = array( 
                "", 
                "Mismo día", 
                "Recíbelo mañana", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            $entrega_nacional_arr = array(
                "", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            if ($value['productos_entrega_local']!="") {
                $entrega_local = $entrega_local_arr[$value['productos_entrega_local']];
            }
            if ($value['productos_entrega_nacional']!="") {
                $entrega_nacional = $entrega_nacional_arr[$value['productos_entrega_nacional']];
            }

            array_push($return["data"], array(
                'productos_id' => $value['productos_id'],
                'productos_titulo' => $value['productos_titulo'],
                'productos_enlace_imagen' => $imagen,
                'productos_vendedor' => $value['usuarios_id'],
                'productos_nombre_vendedor' => $value['name'],
                'productos_envio_local' => $value['productos_envio_local'],
                'productos_valor_envio_local' => $value['productos_valor_envio_local'],
                'productos_valor_envio_local_seteado' => "$ ".number_format($value['productos_valor_envio_local'], 0, ',', '.'),
                'productos_ubicaciones' => $ubicaciones,
                'productos_entrega_local' => $entrega_local,
                'productos_entrega_nacional' => $entrega_nacional,
                'productos_envio_nacional' => $value['productos_envio_nacional'],
                'productos_valor_envio_nacional' => $value['productos_valor_envio_nacional'],
                'productos_valor_envio_nacional_seteado' => "$ ".number_format($value['productos_valor_envio_nacional'], 0, ',', '.'),
                'productos_precio' => "$ ".number_format($value['productos_precio'], 0, ',', '.'),
                'productos_precio_simple' => $value['productos_precio']
            ));
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    public function products(){
        
        $return = array();
        $filtros = array();
        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        if (isset($postdata->filtros)) {
            $postdata = $postdata->filtros;
            foreach ($postdata as $key => $value) {
                if ($key=="limit") {
                    $filtros['limit'] = array();
                    $filtros['limit'] = $value;
                }
                if ($key=="search") {
                    $filtros['search'] = $value;
                }
                if ($key=="orderby") {
                    $filtros['orderby'] = $value;
                }
                if ($key=="categorias_arr") {
                    $filtros['categorias_arr'] = array();
                    $filtros['categorias_arr'] = $value;
                }
                if ($key=="where_arr") {
                    $filtros['where_arr'] = array();
                    foreach ($value as $key2 => $value2) {
                        $filtros['where_arr'][$key2] = $value2;
                    }
                }
            }
        }
        if (isset($postdata['filtros'])) {
            $postdata = $postdata['filtros'];
            foreach ($postdata as $key => $value) {
                if ($key=="limit") {
                    $filtros['limit'] = array();
                    $filtros['limit'] = $value;
                }
                if ($key=="search") {
                    $filtros['search'] = $value;
                }
                if ($key=="orderby") {
                    $filtros['orderby'] = $value;
                }
                if ($key=="categorias_arr") {
                    $filtros['categorias_arr'] = array();
                    $filtros['categorias_arr'] = $value;
                }
                if ($key=="where_arr") {
                    $filtros['where_arr'] = array();
                    foreach ($value as $key2 => $value2) {
                        $filtros['where_arr'][$key2] = $value2;
                    }
                }
            }
        }
        
        $this->db->select('*');
        $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","left");
        if (
            isset($_SESSION['municipio_session']) 
            && $_SESSION['municipio_session']!=0 
            && (
                !isset($_SESSION['tipo_accesos']) 
                || $_SESSION['tipo_accesos']==6
                )
            ) {
            $this->db->group_start();
            $this->db->where("productos_envio_local",0);
            $this->db->or_where("productos_envio_nacional",1);
            $this->db->or_where("productos_envio_local",1);
            $this->db->like("productos_ubicaciones_envio",$_SESSION['departamento_session'].",".$_SESSION['municipio_session']);
            $this->db->group_end();
        }
        if (isset($filtros['where']) && $filtros['where']!=NULL && $filtros['where']!="") {
            $this->db->group_start();
            $this->db->where($filtros['where'][0],$filtros['where'][1]);
            $this->db->group_end();
        }
        if (isset($filtros['where_in']) && $filtros['where_in']!=NULL && $filtros['where_in']!=""
        && isset($filtros['where_in_name']) && $filtros['where_in_name']!=NULL && $filtros['where_in_name']!=""
        ) {
            $this->db->group_start();
            $this->db->where_in($filtros['where_in_name'],$filtros['where_in']);
            $this->db->group_end();
        }
        if (isset($filtros['where_not_in']) && $filtros['where_not_in']!=NULL && $filtros['where_not_in']!=""
        && isset($filtros['where_not_in_name']) && $filtros['where_not_in_name']!=NULL && $filtros['where_not_in_name']!=""
        ) {
            $this->db->group_start();
            $this->db->where_not_in($filtros['where_not_in_name'],$filtros['where_not_in']);
            $this->db->group_end();
        }
        if ((isset($filtros['minprice']) && isset($filtros['maxprice'])) && ($filtros['maxprice']!=NULL && $filtros['minprice']!=NULL) ) {
            $this->db->group_start();
            $this->db->where("productos_precio >=", $filtros['minprice']); 
            $this->db->where("productos_precio <=", $filtros['maxprice']); 
            $this->db->group_end();
        }
        if (isset($filtros['like']) && $filtros['like']!=NULL && $filtros['like']!="") {
            $this->db->group_start();
            $this->db->like($filtros['like_name'], $filtros['like']);
            $this->db->group_end();
        }
        if (isset($filtros['search']) && $filtros['search']!=NULL && $filtros['search']!="") {
            $this->db->like("productos_titulo", $filtros['search']);
        }
        if (isset($filtros['categorias_arr']) && ($filtros['categorias_arr']!=NULL && $filtros['categorias_arr']!="" && $filtros['categorias_arr']!=array())) {
            $productos_categorias = array();
            $this->db->group_start();
            for ($i=0; $i < count($filtros['categorias_arr']); $i++) { 
                if ($i==0) {
                    $this->db->like("productos_categorias","/,/".$filtros['categorias_arr'][$i]."/,/");
                }else{
                    $this->db->or_like("productos_categorias","/,/".$filtros['categorias_arr'][$i]."/,/");
                }
            }
            $this->db->group_end();
        }
        if (isset($filtros['where_arr']) && $filtros['where_arr']!=NULL && $filtros['where_arr']!="") {
            $this->db->group_start();
            foreach ($filtros['where_arr'] as $key => $value) {
                $this->db->where($key,$value);
            }
            $this->db->group_end();
        }
        if (isset($filtros['orderby']) && $filtros['orderby']!=NULL) {
            if ($filtros['orderby']=="defecto") {
                $this->db->order_by("productos_fecha_creacion","DESC");
            }
            if ($filtros['orderby']=="sale") {
                $this->db->order_by("ventas","DESC");
            }
            if ($filtros['orderby']=="rate_medium") {
                $this->db->order_by("productos_fecha_creacion","DESC");
            }
            if ($filtros['orderby']=="date") {
                $this->db->order_by("productos_fecha_creacion","DESC");
            }
            if ($filtros['orderby']=="price_low") {
                $this->db->order_by("productos_precio","ASC");
            }
            if ($filtros['orderby']=="price_high") {
                $this->db->order_by("productos_precio","DESC");
            }
        }else{
            $this->db->order_by("productos.productos_fecha_creacion","DESC");
        }
        $this->db->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left");
        if (isset($filtros['limit']) && is_array($filtros['limit'])==1) {
            $this->db->limit($filtros['limit'][0],$filtros['limit'][1]);
        }
        $productos = $this->db->where('productos.productos_estatus', 1)->get('productos');
        
        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con exito";
        $return['orderby'] = array(
            array(
                "value" => "defecto",
                "text" => "Por defecto"
            ),
            array(
                "value" => "sale",
                "text" => "Por popularidad"
            ),
            array(
                "value" => "rate_medium",
                "text" => "Por calificación media"
            ),
            array(
                "value" => "date",
                "text" => "Por Los Últimos"
            ),
            array(
                "value" => "price_low",
                "text" => "Por Precio: bajo a alto"
            ),
            array(
                "value" => "price_high",
                "text" => "Por Precio: alto a bajo"
            )
        );
        $return['data'] = array();

        foreach ($productos->result_array() as $key => $value) {
            $imagen = '';
            $imagen = image($value['medios_url']);
            $ubicaciones = array();
            $ubi = explode("/",$value['productos_ubicaciones_envio']);
            for ($i=0; $i < count($ubi); $i++) {
                $ub = explode(",",$ubi[$i]);
                if (isset($ub[1])) {
                    array_push($ubicaciones,array(
                        "departamento" => $ub[0],
                        "municipio" => $ub[1]
                    ));
                }
            }
            $entrega_local_arr = array( 
                "", 
                "Mismo día", 
                "Recíbelo mañana", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            $entrega_nacional_arr = array(
                "", 
                "1 - 2 días hábiles ", 
                "2 - 3 días hábiles ",
                "3 - 4 días hábiles ",
                "4 - 5 días hábiles ",
                "5 - 7 días hábiles "
            );
            if ($value['productos_entrega_local']!="") {
                $entrega_local = $entrega_local_arr[$value['productos_entrega_local']];
            }
            if ($value['productos_entrega_nacional']!="") {
                $entrega_nacional = $entrega_nacional_arr[$value['productos_entrega_nacional']];
            }

            array_push($return["data"], array(
                'productos_id' => $value['productos_id'],
                'productos_titulo' => $value['productos_titulo'],
                'productos_enlace_imagen' => $imagen,
                'productos_vendedor' => $value['usuarios_id'],
                'productos_nombre_vendedor' => $value['name'],
                'productos_envio_local' => $value['productos_envio_local'],
                'productos_valor_envio_local' => $value['productos_valor_envio_local'],
                'productos_valor_envio_local_seteado' => "$ ".number_format($value['productos_valor_envio_local'], 0, ',', '.'),
                'productos_ubicaciones' => $ubicaciones,
                'productos_entrega_local' => $entrega_local,
                'productos_entrega_nacional' => $entrega_nacional,
                'productos_envio_nacional' => $value['productos_envio_nacional'],
                'productos_valor_envio_nacional' => $value['productos_valor_envio_nacional'],
                'productos_valor_envio_nacional_seteado' => "$ ".number_format($value['productos_valor_envio_nacional'], 0, ',', '.'),
                'productos_precio' => "$ ".number_format($value['productos_precio'], 0, ',', '.'),
                'productos_precio_simple' => $value['productos_precio']
            ));
        }

        $return['precios'] = $this->productos_model->maxminPrice();

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    public function singleProduct(){
        
        $return = array();

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        if (isset($_REQUEST['id'])) {

            $return['data'] = array();
            $return['data']['imagenes'] = array();

            $this->db->select('*')
            ->join("medios","medios.medios_id=productos.productos_imagen_destacada","left")
            ->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left")
            ->where("productos_id",$_REQUEST['id']);
            $query = $this->db->get('productos');
            $imagenes = array();
            foreach ($query->result_array() as $key2 => $value2) {
                if ($value2['productos_video']=="") {
                    $video = "";
                }else{
                    $video = base_url().$value2['productos_video'];
                }

                $return['data']['productos_id'] = $value2['productos_id'];
                array_push($return['data']['imagenes'], image($value2['medios_url']));
                $return['data']['productos_titulo'] = $value2['productos_titulo'];
                $return['data']['productos_descripcion_corta'] = strip_tags($value2['productos_descripcion_corta']);
                $return['data']['productos_descripcion_larga'] = strip_tags($value2['productos_descripcion_larga']);
                $return['data']['productos_video'] = $video;
                $return['data']['productos_vendedor'] = $value2['usuarios_id'];
                $return['data']['productos_nombre_vendedor'] = $value2['name'];

                $return["data"]["productos_envio_local"] = $value2['productos_envio_local'];
                $return["data"]["productos_valor_envio_local"] = $value2['productos_valor_envio_local'];
                $return["data"]["productos_envio_nacional"] = $value2['productos_envio_nacional'];
                $return["data"]["productos_valor_envio_nacional"] = $value2['productos_valor_envio_nacional'];
                $return["data"]["ubicaciones"]=array();
                $return["data"]["ubicaciones_texto"]=array();
        
                $ubicaciones = explode("/",$value2['productos_ubicaciones_envio']);
                $departamentos = array();
                $municipios = array();
                for ($i=0; $i < count($ubicaciones); $i++) {
                    $dep = explode(",",$ubicaciones[$i]);
                    if (isset($dep[1])) {
                        array_push($departamentos, $dep[0]);
                        array_push($municipios, $dep[1]);

                        array_push($return["data"]["ubicaciones"],array(
                            "departamento" => $dep[0],
                            "municipio" => $dep[1]
                        ));
                    }
                }

                if ($departamentos!=array() && $municipios!=array()) {
                    $this->db->select("*");
                    $this->db->join("departamentos","departamentos.id_departamento=municipios.departamento_id","inner");
                    $this->db->where_in("id_municipio",$municipios);
                    $res = $this->db->get("municipios");
                    foreach ($res->result_array() as $key3 => $value3) {
                        array_push($return['data']['ubicaciones_texto'],array(
                            "departamento" => $value3['departamento'],
                            "municipio" => $value3['municipio']
                        ));
                    }
                }

                $entrega_local_arr = array( 
                    "", 
                    "Mismo día", 
                    "Recíbelo mañana", 
                    "1 - 2 días hábiles ", 
                    "2 - 3 días hábiles ",
                    "3 - 4 días hábiles ",
                    "4 - 5 días hábiles ",
                    "5 - 7 días hábiles "
                );
                $entrega_nacional_arr = array(
                    "", 
                    "1 - 2 días hábiles ", 
                    "2 - 3 días hábiles ",
                    "3 - 4 días hábiles ",
                    "4 - 5 días hábiles ",
                    "5 - 7 días hábiles "
                );
                if ($value2['productos_entrega_local']!="") {
                    $entrega_local = $entrega_local_arr[$value2['productos_entrega_local']];
                }
                if ($value2['productos_entrega_nacional']!="") {
                    $entrega_nacional = $entrega_nacional_arr[$value2['productos_entrega_nacional']];
                }

                $return['data']['productos_entrega_local'] = $entrega_local;
                $return['data']['productos_entrega_nacional'] = $entrega_nacional;


                $return['data']['productos_peso'] = $value2['productos_peso']." kg";
                $productos_dimensiones = "";
                $productos_dimensiones_arr = explode('/,/',$value2['productos_dimensiones']);
                if ($value2['productos_dimensiones']!="/,//,//,//,/" && $value2['productos_dimensiones']!="") {
                    for ($i=1; $i < count($productos_dimensiones_arr)-1; $i++) {
                        if ($i==1) {
                            $productos_dimensiones .= $productos_dimensiones_arr[$i]." cm";
                        }else{
                            $productos_dimensiones .= ",".$productos_dimensiones_arr[$i]." cm";
                        }
                    }
                }
                $return['data']['productos_dimensiones'] = $productos_dimensiones;
                $return['data']['productos_precio'] = "$ ".number_format($value2['productos_precio'], 0, ',', '.');
                $return['data']['productos_precio_simple'] = $value2['productos_precio'];
                $imagenes = explode("/,/",$value2["productos_imagenes"]);
                $relations = $value2['productos_relacionados'];
            }

            $return['data']['productos_relacionados'] = array(); 

            $productos_relacionados = array(0);
            if ($relations!="") {
                $productos_relacionados = explode(",", $relations);
            }
            $this->db->select("*");
            $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","left");
            $this->db->join("usuarios","usuarios.usuarios_id=productos.productos_vendedor","left");
            
            for ($i=0; $i < count($productos_relacionados); $i++) {
                if ($i==0) {
                    $this->db->where("productos_id",$productos_relacionados[$i]);
                }else{
                    $this->db->or_where("productos_id",$productos_relacionados[$i]);
                }
            }
            $datos_productos_relacionados = $this->db->get("productos");

            foreach ($datos_productos_relacionados->result_array() as $key => $value) {
                $imagen = '';
                if ($value['medios_url']==NULL || $value['medios_url']=="") {
                    $imagen = base_url()."assets/img/Not-Image.png";
                }else{
                    $imagen = base_url().$value['medios_url'];
                }
                array_push($return['data']['productos_relacionados'],array(
                    'productos_id'=>$value['productos_id'],
                    'productos_titulo'=>$value['productos_titulo'],
                    'productos_enlace_imagen'=>$imagen,
                    'productos_nombre_vendedor' => $value['name']." ".$value['lastname'],
                    'productos_precio' => "$ ".number_format($value['productos_precio'], 0, ',', '.'),
                    'productos_precio_simple' => $value['productos_precio']
                ));
            }

            $this->db->select("*");
            $this->db->where_in("medios_id", $imagenes);
            $cons = $this->db->get("medios");

            
            foreach ($cons->result_array() as $key => $value) {
                array_push($return['data']['imagenes'], base_url().$value['medios_url']);
            }
            
            $return['data']['addons'] = array();
            $this->db->select("*");
            $this->db->where("addons_producto_id",$_REQUEST['id']);
            $addons = $this->db->get("addons_productos");
            
            foreach ($addons->result_array() as $key => $value) {
                if ($value['addons_titulo']!="") {
                    $tipo_addons="";
                    $opciones=array();
                    if ($value['addons_tipo']=="multiple" || $value['addons_tipo']=="checkboxes") {
                        if ($value['addons_opciones']!="" && $value['addons_opciones']!="/,/1/,/],[") {
                            $op_arr = explode("],[",$value['addons_opciones']);
                            for ($i=0; $i < count($op_arr)-1; $i++) {
                                $opc = explode("/,/",$op_arr[$i]);
                                array_push($opciones, array(
                                    "valor" => $i,
                                    "texto" => $opc[0],
                                    "cargo" => $opc[2]
                                ));
                            }
                        }
                    }
                    if ($value['addons_tipo']=="multiple") {
                        $tipo_addons = "seleccion_unica";
                    }
                    if ($value['addons_tipo']=="checkboxes") {
                        $tipo_addons = "seleccion_multiple";
                    }
                    if ($value['addons_tipo']=="short_text") {
                        $tipo_addons = "texto_corto";
                    }
                    if ($value['addons_tipo']=="long_text") {
                        $tipo_addons = "texto_largo";
                    }
                    
                    array_push($return['data']['addons'], array(
                            "addons_id" => $value['addons_id'],
                            "titulo" => $value['addons_titulo'],
                            "tipo" => $tipo_addons,
                            "requerido" => $value['addons_requerido'],
                            "opciones" => $opciones
                        )
                    );

                }
            }

            $return['error'] = 0;
            $return['mensaje'] = "Consulta realizada con exito";

        }else{
            $return['error'] = 1;
            $return['mensaje'] = "Debe enviar el ID del producto";
            $return['data'] = NULL;
        }

        echo json_encode($return, JSON_UNESCAPED_UNICODE);

    }

    public function metodosPagos(){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $return = array();

        $this->db->select("*");
        $query = $this->db->get("metodos_pagos");

        $return['data'] = array();
        
        foreach ($query->result_array() as $key => $value) {

            array_push($return["data"], array(
                "metodo_id" => $value['metodos_pagos_id'],
                "metodo_titulo" => $value['metodos_pagos_titulo'],
                "metodo_imagen" => base_url().$value['metodos_pagos_imagen']
            )); 
        }

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con éxito.";

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    /**
     *API - Metodos de Pago
     *
     * Api que retorna el listado de metodos de pago (de 2 tablas) ya que existen 2 tablas con esta informacion con diferentes ID
     * en la api puede recibir el id, para retirnar su respectiva informacion, en caso de que no obtenga el id, la respuesta sera el listado
     * de todos los metodos de pago existentes (de ambas tablas)
     *
     * @access public
     * @param Integer $id (Opcional) ID del metodo de pago
     * @return Array $datos 
    */
    public function metodosPagosNew($id=''){

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        
        if (isset($postdata->session)) {
            foreach ($postdata->session as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }
        if (!isset($postdata->session) && isset($postdata['session'])) {
            foreach ($postdata['session'] as $key => $value) {
                $_SESSION[$key] = $value;
            }
        }

        $return = array();

        if($id!==''){
            if(intval($id)<10){
                $query = $this->db->where('metodos_pagos_id', $id)->get("metodos_pagos")->result_array()[0];
                $return["data"][] = [
                    "metodo_id" => $query['metodos_pagos_id'],
                    "metodo_titulo" => $query['metodos_pagos_titulo'],
                    "metodo_imagen" => base_url().$query['metodos_pagos_imagen'],
                    "metodo_url" => ''
                ]; 
            }else if(intval($id)>10){
                $query = $this->mdMpago->getMetodoPagoById($id);
                $return["data"][] = [
                    "metodo_id" => $query['alma_metodos_pagos_id'],
                    "metodo_titulo" => $query['alma_metodos_pagos_titulo'],
                    "metodo_imagen" => base_url().$query['alma_metodos_pagos_imagenes'],
                    "metodo_url" => $query['alma_metodos_pagos_template'],
                ]; 
            }
        }else{

            //Metodos Antiguos
            $query = $this->db->get("metodos_pagos")->result_array();
            foreach($query as $metpag):
                $return["data"][] = [
                    "metodo_id" => $metpag['metodos_pagos_id'],
                    "metodo_titulo" => $metpag['metodos_pagos_titulo'],
                    "metodo_imagen" => base_url().$metpag['metodos_pagos_imagen'],
                    "metodo_url" => ''
                ]; 
            endforeach;

            //Metodos Nuevos
            $query = $this->mdMpago->getAll(1);
            foreach($query as $metpag):
                $return["data"][] = [
                    "metodo_id" => $metpag['alma_metodos_pagos_id'],
                    "metodo_titulo" => $metpag['alma_metodos_pagos_titulo'],
                    "metodo_imagen" => base_url().$metpag['alma_metodos_pagos_imagenes'],
                    "metodo_url" => $metpag['alma_metodos_pagos_template']
                ]; 
            endforeach;
        }

        $return['error'] = 0;
        $return['mensaje'] = "Consulta realizada con éxito.";

        echo json_encode($return, JSON_UNESCAPED_UNICODE);
    }

    function sessionstart($sessionvar=array()){
        if ($sessionvar!=array()) {
            foreach ($sessionvar as $key => $value) {
                $this->session->set_userdata($key, $value);
            }
        }
    }

    public function obtenerDepartamentos($id_departamento=0){
      $datos = array();
      $filter = array();
      $departamentos = $this->general_model->obtenerDepartamentos();
      $datos['data'] = $departamentos->result_array();
      $datos['mensaje'] = "Consulta realizada con éxito";
      
      $datos['error'] = 0;

      echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerMunicipios($id_departamento=0, $id_municipio=0){
        $datos = array();
        if ($id_departamento!==0 && $id_departamento!=='') {
            $filter['where'] = array('departamento_id', $id_departamento);
            $datos['data'] = $this->general_model->obtenerMunicipios($filter);
            $datos['mensaje'] = "Consulta realizada con éxito"; 
            $datos['error'] = 0; 
        }else{
            $datos['mensaje'] = "Debe enviar el ID del departamento"; 
            $datos['error'] = 1; 
            $datos['data'] = NULL; 
        }
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function cambioUbicacion($user_id = 0,$departamento_id = 0,$municipio_id=0){
        $datos = array();
        if ($user_id!=0 && $departamento_id!=0 && $municipio_id!=0) {
            $this->db->set('usuarios_departamento', $departamento_id);
            $this->db->set('usuarios_municipio', $municipio_id);
            $this->db->where('usuarios_id', $user_id);
            $this->db->update('usuarios');
            $datos['mensaje'] = "Modificado con éxito"; 
            $datos['error'] = 0;
        }elseif ($_REQUEST['user_id']!=0 && $_REQUEST['departamento_id']!=0 && $_REQUEST['municipio_id']!=0) {
            $this->db->set('usuarios_departamento', $_REQUEST['departamento_id']);
            $this->db->set('usuarios_municipio', $_REQUEST['municipio_id']);
            $this->db->where('usuarios_id', $_REQUEST['user_id']);
            $this->db->update('usuarios');
            $datos['mensaje'] = "Modificado con éxito"; 
            $datos['error'] = 0;
        }else{
            $datos['mensaje'] = "Debe enviar todas las variables correctamente"; 
            $datos['error'] = 1;
        }
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }
    
    public function obtenerPedidos($id = 0){
        $datos['data'] = array(); 
        if ($id!=0) {
            
            $this->db->select("*");
            $this->db->join("alma_pedidos","alma_pedidos_detalle.pedidos_detalle_pedidos_id=alma_pedidos.pedidos_id","inner");
            $this->db->join("productos","alma_pedidos_detalle.pedidos_detalle_producto=productos.productos_id","inner");
            $this->db->join("medios","medios.medios_id=productos.productos_imagen_destacada","inner");
            $this->db->where("alma_pedidos.pedidos_usuarios_id",$id);
            //$this->db->group_by("alma_pedidos.pedidos_id");
            $pedidos_detalle = $this->db->get("alma_pedidos_detalle");
            
            foreach ($pedidos_detalle->result_array() as $key => $value) {
                
                $fecha = $value['pedidos_fecha_creacion'];
                $fecha = fecha_esp($fecha);

                $precio_total_seteado = "$ ".number_format($value['pedidos_precio_total'], 0, ',', '.');
                $color = 0;
                if($value['pedidos_estatus']==6){ 
                    $estatus = "Rechazado"; 
                    $color = hexdec("F17362");
                }elseif ($value['pedidos_estatus']==5) {
                    $estatus = "Rechazado"; 
                    $color = hexdec("F17362");
                }elseif ($value['pedidos_estatus']==4) {
                    $estatus = "En Preparación"; 
                    $color = hexdec("5F5F5F");
                }elseif ($value['pedidos_estatus']==1) {
                    $estatus = "Esperando confirmación de pago"; 
                    $color = hexdec("5F5F5F");
                }elseif ($value['pedidos_estatus']=="1") {
                    $estatus = "Esperando confirmación de pago"; 
                    $color = hexdec("5F5F5F");
                }else {
                    switch ($value['pedidos_estatus']) {
                        case 'Enviado':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("F4B127");
                            break;
                        case 'En preparación':
                                $estatus = $value['pedidos_estatus'];
                                $color = hexdec("5F5F5F");
                            break;
                        case 'Esperando confirmación de pago':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("5F5F5F");
                            break;
                        case 'Esperando confirmación de Pago':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("5F5F5F");
                            break;
                        case 1:
                            $estatus = 'Esperando confirmación de Pago';
                            $color = hexdec("5F5F5F");
                            break;
                        case 1:
                            $estatus = "Esperando confirmación de Pago";
                            $color = hexdec("5F5F5F");
                            break;
                        case "1":
                            $estatus = "Esperando confirmación de Pago";
                            $color = hexdec("5F5F5F");
                            break;
                        case 'Cancelado':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("F17362");
                            break;
                        default:
                            $estatus = $value['pedidos_estatus'];
                            break;
                      }
                  }

                $titulo_producto = $value['productos_titulo'];
                $agg = 1;
                for ($i=0; $i < count($datos['data']); $i++) {
                    if ($datos['data'][$i]['pedidos_id']==$value['pedidos_id']) {
                        $agg = 0;
                        $datos['data'][$i]['pedidos_mas_productos'] = $datos['data'][$i]['pedidos_mas_productos']+1;
                    }
                }
                $imagen = image($value['medios_url']);

                if ($agg == 1) {
                    array_push($datos['data'], array(
                        "pedidos_id" => $value['pedidos_id'],
                        "pedidos_fecha" => $fecha,
                        "pedidos_imagen_producto" => $imagen,
                        "pedidos_titulo_producto" => $titulo_producto,
                        "pedidos_mas_productos" => 0,
                        "pedidos_precio_total" => $value['pedidos_precio_total'],
                        "pedidos_precio_total_seteado" => $precio_total_seteado,
                        "pedidos_estatus" => $estatus,
                        "pedidos_estatus_color" => $color,
                    ));
                }
            }

            $datos['mensaje'] = "Consulta realizada con Éxito"; 
            $datos['error'] = 0;
        }else{
            $datos['mensaje'] = "Debe enviar un ID de usuario válido."; 
            $datos['error'] = 1;
        }
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }
    
    public function detallePedido($id = 0){
        $datos = array();
        $datos['data'] = array(); 
        if ($id!=0) {
            $this->db->select("*");
            $this->db->join("alma_pedidos","alma_pedidos.pedidos_id=alma_pedidos_detalle.pedidos_detalle_pedidos_id","inner");
            $this->db->join("productos","productos.productos_id=alma_pedidos_detalle.pedidos_detalle_producto","inner");
            $this->db->join("departamentos","alma_pedidos.pedidos_departamento=departamentos.id_departamento","left");
            $this->db->join("municipios","alma_pedidos.pedidos_municipio=municipios.id_municipio","left");
            $this->db->join("medios","productos.productos_imagen_destacada=medios.medios_id","left");
            $this->db->where("alma_pedidos.pedidos_id",$id);
            $pedido = $this->db->get("alma_pedidos_detalle")->result_array();
            
            $this->db->select("*");
            $this->db->where("pedidos_id",$id);
            $this->db->group_by("productos_id,estatus");
            $this->db->order_by("fecha_modificacion","ASC");
            $estatus_cons = $this->db->get("pedidos_estatus_productos");
            
            $datos['data']['productos'] = array(); 
            
            foreach ($pedido as $key => $value) {
                $estatus = ""; 
                $color = ""; 
                $fecha = $value['pedidos_fecha_creacion'];
                $fecha = fecha_esp($fecha);
                if($value['pedidos_estatus']==6){ 
                    $estatus = "Rechazado"; 
                    $color = hexdec("F17362"); 
                }elseif ($value['pedidos_estatus']==5) {
                    $estatus = "Rechazado"; 
                    $color = hexdec("F17362"); 
                }elseif ($value['pedidos_estatus']==4) {
                    $estatus = "En Preparación"; 
                    $color = hexdec("5F5F5F"); 
                }elseif ($value['pedidos_estatus']==1) {
                    $estatus = "Esperando confirmación de pago"; 
                    $color = hexdec("5F5F5F"); 
                }elseif ($value['pedidos_estatus']=="1") {
                    $estatus = "Esperando confirmación de pago"; 
                    $color = hexdec("5F5F5F"); 
                }else {
                    switch ($value['pedidos_estatus']) {
                        case 'Enviado':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("F4B127"); 
                            break;
                        case 'En preparación':
                            $color = hexdec("5F5F5F"); 
                            $estatus = $value['pedidos_estatus'];
                            break;
                        case 'Esperando confirmación de pago':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("5F5F5F"); 
                            break;
                        case 'Esperando confirmación de Pago':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("5F5F5F"); 
                            break;
                        case 1:
                            $estatus = 'Esperando confirmación de Pago';
                            $color = hexdec("5F5F5F"); 
                            break;
                        case 1:
                            $estatus = "Esperando confirmación de Pago";
                            $color = hexdec("5F5F5F"); 
                            break;
                        case "1":
                            $estatus = "Esperando confirmación de Pago";
                            $color = hexdec("5F5F5F"); 
                            break;
                        case 'Cancelado':
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("F17362"); 
                            break;
                        default:
                            $estatus = $value['pedidos_estatus'];
                            $color = hexdec("5F5F5F"); 
                            break;
                    }
                }
                
                $estatus_productos = array(
                    array(
                        "index" => 1,
                        "titulo" => "Pedido Recibido",
                        "activo" => 0,
                        "estado" => "Confirmado",
                        "fecha" => "",
                    ),
                    array(
                        "index" => 2,
                        "titulo" => "Pedido en preparación",
                        "activo" => 0,
                        "estado" => "En preparación",
                        "fecha" => "",
                    ),
                    array(
                        "index" => 3,
                        "titulo" => "Pedido Enviado",
                        "activo" => 0,
                        "estado" => "Enviado",
                        "fecha" => "",
                    ),
                );
                $indexes = 0;
                $nro_guia = "";
                $empresa_trans = "";
                $es_canc = 0;
                foreach ($estatus_cons->result_array() as $key2 => $value2) {
                    if ($value2['productos_id']==$value['productos_id']) {
                        for ($i=0; $i < count($estatus_productos); $i++) {
                            if ( $estatus_productos[$i]['estado']=="Cancelado"
                            || $estatus_productos[$i]['estado']=="Rechazado" ) 
                            {
                                $es_canc = 1;
                            }
                        }
                        
                        for ($i=0; $i < count($estatus_productos); $i++) {
                            
                            if (($value2['estatus']=="Confirmado" || $value2['estatus']=="") && $estatus_productos[$i]['estado']=="Confirmado") {
                                if ($es_canc!=1) {
                                    $estatus_productos[$i]['activo']=1;
                                    $estatus_productos[$i]['fecha']=$value2['fecha_modificacion'];
                                }
                            }
                            if ($value2['estatus']=="En preparación" && $estatus_productos[$i]['estado']=="En preparación") {
                                if ($es_canc!=1) {
                                    $estatus_productos[$i]['activo']=1;
                                    $estatus_productos[$i]['fecha']=$value2['fecha_modificacion'];
                                }
                            }
                            if ($value2['estatus']=="Enviado" && $estatus_productos[$i]['estado']=="Enviado") {
                                if ($es_canc!=1) {
                                    $estatus_productos[$i]['activo']=1;
                                    $estatus_productos[$i]['fecha']=$value2['fecha_modificacion'];
                                }
                            }
                            if (($value2['estatus']=="Rechazado")
                            || ($value2['estatus']=="Cancelado")
                            || ($value2['estatus']=="Reembolsado")) 
                            {
                                $es_canc = 1;
                                $titulo = "Pedido cancelado";
                                $estatus_productos = array();
                                array_push($estatus_productos,array(
                                    "index"     => 1,
                                    "titulo"    => $titulo,
                                    "activo"    => 1,
                                    "estado"    => $value2['estatus'],
                                    "fecha"     => $value2['fecha_modificacion'],
                                ));
                            }
                        }
                        if ($es_canc==1) {
                            $titulo = "Pedido cancelado";
                            $estatus_productos = array();
                            array_push($estatus_productos,array(
                                "index"     => 1,
                                "titulo"    => $titulo,
                                "activo"    => 1,
                                "estado"    => $value2['estatus'],
                                "fecha"     => $value2['fecha_modificacion'],
                            ));
                        }
                        
                        if (($value2['nro_guia']!="" && $value2['nombre_empresa']!="") 
                        && ($nro_guia=="" && $empresa_trans=="")) 
                        {
                            $nro_guia = $value2['nro_guia'];
                            $empresa_trans = $value2['nombre_empresa'];
                        }
                    }
                }

                $precio_seteado = "$ ".number_format($value['pedidos_detalle_producto_precio'], 0, ',', '.');
                $precio_total_seteado = "$ ".number_format($value['pedidos_precio_total'], 0, ',', '.');
                $precio_total_envio = "$ ".number_format($value['pedidos_precio_envio'], 0, ',', '.');
                $precio_total_sin_envio = "$ ".number_format($value['pedidos_precio_total']-$value['pedidos_precio_envio'], 0, ',', '.');
                $imagen = image($value['medios_url']);
                
                $datos['data']['pedidos_id'] = $id;
                $datos['data']['pedidos_nombre_cliente'] = $value['pedidos_nombre'];
                
                $datos['data']['pedidos_estatus'] = $estatus; 
                $datos['data']['pedidos_estatus_color'] = $color; 
                $datos['data']['pedidos_fecha'] = $fecha;
                $datos['data']['pedidos_precio_envio'] = $precio_total_envio;
                $datos['data']['pedidos_precio_total'] = $precio_total_seteado;
                $datos['data']['pedidos_precio_total_sin_envio'] = $precio_total_sin_envio;
                array_push($datos['data']['productos'],array(
                    'productos_id' => $value['productos_id'],
                    'productos_imagen' => $imagen,
                    'productos_titulo' => $value['productos_titulo'],
                    'productos_cantidad' => $value['pedidos_detalle_producto_cantidad'],
                    'productos_precio' => $value['pedidos_detalle_producto_precio'],
                    'productos_precio_seteado' => $precio_seteado,
                    'productos_estatus' => $estatus_productos,
                    'productos_nro_guia' => $nro_guia,
                    'productos_empresa' => $empresa_trans,
                ));
                
                $datos['data']['pedidos_direccion'] = $value['pedidos_direccion'];
                $datos['data']['pedidos_departamento'] = $value['departamento'];
                $datos['data']['pedidos_municipio'] = $value['municipio'];

            }

            $datos['mensaje'] = "Consulta realizada con Éxito"; 
            $datos['error'] = 0;
        }else{
            $datos['mensaje'] = "Debe enviar un ID de pedido válido."; 
            $datos['error'] = 1;
        }
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function generarPreOrden(){

        $datos = array();
        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);
        if (isset($postdata["checkout"])
        && isset($postdata["checkout"]["productos"])
        && isset($postdata["checkout"]["pedido"])) {
            $query = $this->db->query('SHOW COLUMNS FROM alma_pedidos');
            $valores = $query->result_array();
            $data = array();
            foreach ($valores as $key => $value) {
                $field = $value["Field"];
                if (isset($postdata["checkout"]["pedido"][$value["Field"]]) 
                && $postdata["checkout"]["pedido"][$value["Field"]]!=NULL) 
                {
                    $data += [$value["Field"]=>$postdata["checkout"]["pedido"][$value["Field"]]];
                }
            }
            $data += ["pedidos_fecha_creacion"=>date('Y-m-d H:i:s')];            
            $data += ["pedidos_estatus" => 1];   
            $data += ["pedidos_canal" => 'app'];  
            $ingresar['data'] = $this->db->insert('alma_pedidos', $data);    
            $ultimoId = $this->db->insert_id();    
            $pedido = array();    
            foreach ($postdata["checkout"]["pedido"] as $key => $value) {
                $pedido[$key] = $value;
            }    
            $pedido["pedidos_id"] = $ultimoId;            
            $pedidos_productos_agg = array();    
            foreach ($postdata["checkout"]["productos"] as $key => $value) {
                $addons = array(
                    "addons" => $value['productos_addons']
                );
                $addons = json_encode($addons, JSON_UNESCAPED_UNICODE);
                $ubicaciones_envio = "";
                $cont = 0;
                for ($i=0; $i < count($value['productos_ubicaciones_envio']); $i++) {
                    $cont++;
                    if ($cont==1) {
                        $ubicaciones_envio = $value['productos_ubicaciones_envio'][$i]['departamento'].",".$value['productos_ubicaciones_envio'][$i]["municipio"];
                    }else{
                        $ubicaciones_envio .= "/".$value['productos_ubicaciones_envio'][$i]['departamento'].",".$value['productos_ubicaciones_envio'][$i]["municipio"];
                    }
                }
                array_push($pedidos_productos_agg, array(
                    "pedidos_detalle_pedidos_id" => $pedido["pedidos_id"],
                    "pedidos_detalle_producto" => $value['productos_id'],
                    "pedidos_detalle_producto_cantidad" => $value['productos_cantidad'],
                    "pedidos_detalle_producto_addons" => $addons,
                    "pedidos_detalle_producto_precio" => $value['productos_precio'],
                    "pedidos_detalle_producto_envio_local" => $value['productos_envio_local'],
                    "pedidos_detalle_productos_valor_envio_local" => $value['productos_valor_envio_local'],
                    "pedidos_detalle_productos_ubicaciones_envio" => $ubicaciones_envio,
                    "pedidos_detalle_productos_envio_nacional" => $value['productos_envio_nacional'],
                    "pedidos_detalle_productos_valor_envio_nacional" => $value['productos_valor_envio_nacional'],
                    "pedidos_detalle_vendedor" => $value['productos_vendedor'],
                    "pedidos_detalle_estatus" => 1
                ));
                
            }
            $this->db->insert_batch('alma_pedidos_detalle', $pedidos_productos_agg);
            //$this->mailing_model->mailPedidoRecibido($this->pedidos_model->singleNew($ultimoId));
            
            $datos['pedidos_id'] = $ultimoId;
            $datos['mensaje'] = "Orden generada con éxito."; 
            $datos['error'] = 0;
            
        }else{
            $datos['mensaje'] = "Error con los datos recibidos, por favor verifique e intente de nuevo."; 
            $datos['error'] = 1;
        }
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    /*
    // FIXME: change by the right merchant payment server url
    private const val SERVER_URL = "https://my-merchant-server.herokuapp.com/" // without / at the end, example https://myserverurl.com

    // FIXME: change by your public key
    private const val PUBLIC_KEY = "80379999:testpublickey_typOGRpuj5CCePMdSdkz2c5FqPJL7rtqyDe9VqC8hp5dI"// "<REPLACE_ME>"

    // Environment TEST or PRODUCTION, refer to documentation for more information
    // FIXME: change by your targeted environment
    private const val PAYMENT_MODE = "TEST"

    // TRUE to display a "register the card" switch in the payment form
    private const val ASK_REGISTER_PAY = false

    // FIXME: Change by the right REST API Server Name (available in merchant BO: Settings->Shop->REST API Keys)
    private const val API_SERVER_NAME = "https://api.payzen.lat" // without / at the end, example https://myapiservername.com

    // Payment parameters
    // FIXME: change currency for your targeted environment
    private const val CURRENCY = "EUR"
    private const val AMOUNT = "100"
    private const val ORDER_ID = ""

    //Customer informations
    private const val CUSTOMER_EMAIL = "customeremail@domain.com"
    private const val CUSTOMER_REFERENCE = "customerReference"

    //Basic auth
    // FIXME: set your basic auth credentials
    private const val SERVER_AUTH_USER = "cjvcdesarrollador@gmail.com"
    private const val SERVER_AUTH_TOKEN = "1qazAlma"
    private const val CREDENTIALS: String = "$SERVER_AUTH_USER:$SERVER_AUTH_TOKEN"
     */

    public function createPaymentPayzen($pedidos_id = 0){
        $response = '{ "webService": "", "version": "", "applicationVersion": "", "status": "FAILED", "answer": {     "formToken": "",     "_type": "" }, "ticket": "", "serverDate": "", "applicationProvider": "", "metadata": null, "mode": "TEST", "serverUrl": "https://api.payzen.lat", "_type": "V4/WebService/Response"}';
        $response = json_decode($response);

        $post = $this->getpost();
        $post=json_decode($post, JSON_UNESCAPED_UNICODE);
        if (isset($post['pedidos_id']) && $post['pedidos_id']!="" && $post['pedidos_id']!=0) {
            $pedidos_id=$post['pedidos_id'];
        }
        if ($pedidos_id!=0) {
            $pedido = $this->pedidos_model->singleNew($pedidos_id);
            if (isset($pedido['pedido']['pedidos_id'])) {
                
                require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/vendor/autoload.php';
                require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/keys.PCI.php';
                require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/payzen/helpers.php';
                /** 
                    * Initialize the SDK 
                    * see keys.php
                    */
                $client = new Lyra\Client();
                $precio = floatval($pedido['pedido']['pedidos_precio_total']."00");
                /**
                    * I create a formToken
                    */
                $store = array(
                    "amount" => $precio, 
                    "currency" => "COP", 
                    "orderId" => "Pedido Nro ".$pedido['pedido']['pedidos_id'], 
                    "customer" => array(
                        "email" => $pedido['pedido']['pedidos_correo'],
                        "billingDetails" => array(
                            "language" => "ES"
                        )
                    )
                );
                $response = $client->post("V4/Charge/CreatePayment", $store);
    
                /* I check if there are some errors */
                if ($response['status'] != 'SUCCESS') {
                    /* an error occurs, I throw an exception */
                    display_error($response);
                    $error = $response['answer'];
                    throw new Exception("error " . $error['errorCode'] . ": " . $error['errorMessage']);
                } 
            }
        }
        /* everything is fine, I extract the formToken */
        echo json_encode($response);
    }

    public function getConfigPayzen(){
        
        $datos = array(
            "SERVER_URL" => "https://almadelascosas.co/api/",
            "PUBLIC_KEY" => "80379999:testpublickey_typOGRpuj5CCePMdSdkz2c5FqPJL7rtqyDe9VqC8hp5dI",
            "PAYMENT_MODE" => "TEST",
            "ASK_REGISTER_PAY" => false,
            "API_SERVER_NAME" => "https://api.payzen.lat",
            "SERVER_AUTH_USER" => "admin@almadelascosas.com",
            "SERVER_AUTH_TOKEN" => "almajin.Developer.123",
        );

        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerPreferenceMercadoPago($pedido){
        $order = [
            "items" => [
                array(
                    "title" => "Pedido #".$pedido['pedido']['pedidos_id'],
                    "quantity" => 1,
                    "currency_id" => "COP",
                    "unit_price" => intval($pedido['pedido']['pedidos_precio_total'])
                )
            ],
            "payer" => array(
                "email" => $pedido['pedido']['pedidos_correo']
            )
        ];
        $order = json_encode($order, JSON_UNESCAPED_UNICODE);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mercadopago.com/checkout/preferences',
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $order,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer TEST-737037781546252-072613-6ec8668d0de2667b89832928644e31a0-1166475224'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
    }
    public function obtenerPrefencia($id){
        $pedido = $this->pedidos_model->singleNew($id);
        $this->obtenerPreferenceMercadoPago($pedido);
    }

    public function paymentMercadoPago($id=0){
        $datos = array();
        $data = array();
        $pedido = $this->pedidos_model->singleNew($id);
        if ($id!=0) {
            if (isset($pedido['pedido']['pedidos_id'])) {
                $preference = json_decode(file_get_contents(base_url()."obtener_preference.php?pedidos_id=".$pedido['pedido']['pedidos_id']."&pedidos_precio_total=".$pedido['pedido']['pedidos_precio_total']."&pedidos_correo=".$pedido['pedido']['pedidos_correo']));

                $data['id'] = $preference->id;
                $data['client_id'] = $preference->client_id;
                $data['public_key_test'] = "TEST-e0a6c087-720f-47ed-9b05-2d41a3ce23ba";
                $data['public_key'] = "APP_USR-1c6c2f65-91f2-4e8d-9e9a-0e91ccdd6ce0";
                $datos['error'] = 0;
                $datos['mensaje'] = "Consulta realizada con éxito";
                $datos['data'] = $data;
            }else{
                $datos['data'] = "";
                $datos['error'] = 1;
                $datos['mensaje'] = "No se encontró su pedido";
            }
        }else{
            $datos['data'] = "";
            $datos['error'] = 1;
            $datos['mensaje'] = "Id de pedido incorrecto, por favor verifique e intente de nuevo";            
        }

        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function obtenerMetodosMP(){
        $datos = array();
        $data = array();
        require_once $_SERVER['DOCUMENT_ROOT'].'/application/libraries/mercadopago/vendor/autoload.php';        
        // PRODUCTION
        //MercadoPago\SDK::setAccessToken("APP_USR-737037781546252-072613-81cac146ff52e9ee8c693a407ab495b3-1166475224");
        // TEST
        //MercadoPago\SDK::setAccessToken("TEST-737037781546252-072613-6ec8668d0de2667b89832928644e31a0-1166475224");
        MercadoPago\SDK::configure(['ACCESS_TOKEN' => 'TEST-737037781546252-072613-6ec8668d0de2667b89832928644e31a0-1166475224']);
        MercadoPago\SDK::setAccessToken("TEST-737037781546252-072613-6ec8668d0de2667b89832928644e31a0-1166475224");
        $payment_methods = MercadoPago\SDK::get("/v1/payment_methods");
        echo json_encode($payment_methods['body'], JSON_UNESCAPED_UNICODE);
        
    }

    public function cstatusPedido(){
        $datos = array();
        $data = array();
        
        $post = $this->getpost();
        $post = json_decode($post, JSON_UNESCAPED_UNICODE);
        if ( isset($post['status'])
        && isset($post['pedidos_id']) ) {
                $this->db->select("pedidos_id,pedidos_fecha_creacion");
                $this->db->where("pedidos_id",$post['pedidos_id']);
                $pedido = $this->db->get("alma_pedidos");
                $pedido_fecha = "";
                if ($pedido->num_rows() > 0) {
                    foreach ($pedido->result_array() as $key => $value) {
                        $pedido_fecha = $value['pedidos_fecha_creacion'];
                    }
                    $estatus = "Pendiente";
                    if ($post['status']=="approved") {
                        $estatus = "En preparación";
                    }
                    elseif ($post['status']=="in_process") {
                        $estatus = "Pendiente";
                    }
                    elseif ($post['status']=="rejected") {
                        $estatus = "Rechazado";
                    }
                    $this->db->set('pedidos_fecha_creacion', $pedido_fecha);
                    $this->db->set('pedidos_estatus', $estatus);
                    $this->db->where('pedidos_id', $post['pedidos_id']);
                    $this->db->update('alma_pedidos');                   
                    $datos['data'] = array();
                    $datos['error'] = 0;
                    $datos['mensaje'] = "Consulta realizada con éxito.";
                }else{
                    $datos['data'] = array();
                    $datos['error'] = 1;
                    $datos['mensaje'] = "Este pedido no existe, por favor verifica e intenta nuevamente.";
                }
        }else{
            $datos['data'] = array();
            $datos['error'] = 1;
            $datos['mensaje'] = "Los datos recibidos son incorrectos.";
        }
        echo json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function createPayment(){
        //$pedido = $this->pedidos_model->generarPreOrdenNew();
        $pedido=[
            'pedidos_id' => rand(1000,9999), 
            'pedido_metodo_pago' => 2
        ];
        $template='';
        $urlpay='';

        
        
        $datos=[
            'data' => $pedido,
            'error' => 0,
            'urlroute' => base_url('checkout/paymenstep/'.$pedido['pedidos_id']),
            'mensaje' => "Consulta realizada con éxito"
        ];
        print json_encode($datos);
    } 

    public function generarPayment($idmetodo, $idpedido){
        $datoMetodoPago = $this->mdMpago->getMetodoPagoById($idmetodo);
        $slug = $this->seo_url($datoMetodoPago['alma_metodos_pagos_titulo']);
        $datos=[
            'mensaje' => 'mensaje de retorno satisfactorio',
            'error' => 0,
            'urlpago' => base_url('checkout/pago/'.$slug.'/'.$idmetodo.'/'.$idpedido)
        ];
        print json_encode($datos, JSON_UNESCAPED_UNICODE);
    }

    public function getPedidosNoEfectuados(){
        log_message('info', '<--- INICIO EJECUCION API CHRONJOB: getPedidosNoEfectuados - '.date('d-m-Y H:i:s'));
        date_default_timezone_set("America/Bogota");

        $date_now = date('d-m-Y');
        $date_antier = strtotime('-2 day', strtotime($date_now));
        $date_antier = date('d-m-Y', $date_antier);

        $fecIni = $date_antier.' 00:00:00';
        $fecFin = $date_antier.' 23:59:59';

        $idPed=[];
        $resultado=[
            'est'=>'Fail',
            'msj'=>'',
            'pdNoExiste'=>[],
            'pdNoInv'=>[],
            'pdMod'=>[],
            'pedMod'=>[],
            'fecha'=>$fecIni.'_'.$fecFin
        ];

        $this->db->where("pedidos_estatus", '1')
                 ->where("pedidos_fecha_creacion BETWEEN '".$fecIni."' AND '".$fecFin."'");
        $pedidosStandBy = $this->db->get("alma_pedidos")->result_array();

        foreach($pedidosStandBy as $key => $ped) $idPed[]=$ped['pedidos_id'];

        if(count($idPed)>0){
            $pedidosDetalle = $this->db->where_in("pedidos_detalle_pedidos_id", $idPed)->order_by('pedidos_detalle_pedidos_id','ASC')->get("alma_pedidos_detalle")->result_array();
            foreach($pedidosDetalle as $key => $det):
                //print 'PED: '.$det['pedidos_detalle_pedidos_id'].' => PROD_ID: '.$det['pedidos_detalle_producto'].' => CANT: '.$det['pedidos_detalle_producto_cantidad'].'<br>';
                
                $this->db->select("productos_gestion_inv, productos_stock, productos_titulo");
                $this->db->where("productos_id", $det['pedidos_detalle_producto']);
                $producto = $this->db->get("productos")->result_array();

                if(count($producto)>0){
                    $producto = $producto[0];

                    if (intval($producto['productos_gestion_inv'])===1) {
                        $stock = intval($producto['productos_stock']) + intval($det['pedidos_detalle_producto_cantidad']);

                        $this->db->set('productos_stock', $stock);
                        $this->db->where('productos_id', $det['pedidos_detalle_producto']);
                        $this->db->update('productos');

                        $resultado['pdMod'][]=$det['pedidos_detalle_producto'];
                    }else{
                        $resultado['pdNoInv'][]=$det['pedidos_detalle_producto'];
                    }
                }else{
                    $resultado['pdNoExiste'][]=$det['pedidos_detalle_producto'];
                } 

                
                $this->db->set('pedidos_estatus', 'No Efectuado');
                $this->db->where('pedidos_id', $det['pedidos_detalle_pedidos_id']);
                $this->db->update('alma_pedidos');
                
                $resultado['pedMod'][]=$det['pedidos_detalle_pedidos_id'];
                //print 'Pedido: ID=>'.$det['pedidos_detalle_pedidos_id'].' Ha sido actualizado a Estado=No Efectuado <br>';

            endforeach;
        }else{
            $resultado['est']='ok';
            $resultado['msj']='No se ha encontrado pedidos en el rango de la fechas: Ini ';
        }

        $resultado['est']='ok';
        $resultado['msj']='Se ha actualizado '.count($idPed).' pedidos';

        log_message('info', '<--- FINALIZADO EJECUCION API CHRONJOB: getPedidosNoEfectuados - '.date('d-m-Y H:i:s'));

        print json_encode($resultado, JSON_UNESCAPED_UNICODE);
    }


    /**
    * Registro por Redes Sociales (Firebase)
    *
    * la APP envia los datos de la red social, segun se elija
    * este envia unos datos en Json, la cual se recibe y con estos se consulta
    * si existe, se notificara en respuesta encode json...
    * sino... se iniciara el registro en la BD de ALDC
    *
    * @access public
    * @param array $_request->data datos que genera el login de firebase
    * @return array respuesta de un SI o NO ha quedado el registro
    */
    public function getRegistrerFirebase(){
        $return = array();

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);

        if(!isset($postdata['data'])){ print json_encode(['resp'=>'ok', 'estado'=>'error', 'msg'=>'dato faltante: data']); exit(); } 
        if(!isset($postdata['token'])){ print json_encode(['resp'=>'ok', 'estado'=>'error', 'msg'=>'dato faltante: token']); exit(); } 
        if(!isset($postdata['crear'])){ print json_encode(['resp'=>'ok', 'estado'=>'error', 'msg'=>'dato faltante: crear']); exit(); } 

        $data = $postdata['data'];

        $result = $this->modUsu->getUserSocialMedia($data['providerId'], $data['email']);

        if(count($result)===0){
            if($postdata['crear']==='si'){
                if($data['displayName']!=='' && $data['displayName']!==null){
                    $nom = explode(' ', $data['displayName']);
                    $cantNom = count($nom);
                    $userName = strtolower(str_replace(" ", ".", $data['displayName'].'.'.$data['providerId']));

                    if($cantNom>=3){
                        $ultimo = $cantNom-1;
                        $penultimo = $cantNom-2;
                        $lastName = $nom[$penultimo].' '.$nom[$ultimo];
                        $firstName='';
                        for($i=0 ; $i<$penultimo ; $i++) $firstName.= ' '.$nom[$i];
                    }else if($cantNom<3){
                        $firstName=$nom[0];
                        $lastName =$nom[1];
                    }
                }else{
                    $firstName= '(Sin Nombre)';
                    $lastName = '(Sin Apellido)';
                    $userName = $data['email'];
                }
                
                $pass = $this->modAuth->generatePassword();
                $passEncode = encodeItem($pass);

                $data=[
                    'name' => $firstName,
                    'lastname' => $lastName,
                    'email' => $data['email'],
                    'username' => $userName,
                    'password' => $passEncode,
                    'tipo_accesos' => 6,
                    'regcanal' => 'app',
                    'socialmedia_providerid' => $data['providerId'],
                    'socialmedia_uid' => $data['uid'],
                    'socialmedia_token' => $postdata['token']
                ];
                $id = $this->modUsu->insertUserMediaSocial($data);

                //Datos Necesarios para App
                $user_data = $this->getUserData($id);
                $this->fillSession($user_data['user_data']);
                $this->logAuthenticationSuccess($id);
                
                foreach ($_SESSION as $key => $value) $return_data[$key] = $value;

                $return_data["departamento_session"] = $_SESSION['usuarios_departamento'];
                $return_data["departamento_nombre_session"] = 'No Configurado';
                $return_data["municipio_session"] = $_SESSION['usuarios_municipio'];
                $return_data["municipio_nombre_session"] = 'No Configurado';

                unset($return_data['url_redirect'], $return_data['tipo_accesos'], $return_data['usuarios_comision']);

                $return_data['error'] = 0;
                $return_data['mensaje'] = "registro y autenticacion exitosa";              
            }else{
                $return_data['error'] = 0;
                $return_data['mensaje'] = "usuario no existe, no creado";
                $return_data['data'] = NULL;
            }
        }else{
            $result = $result[0];
            //Datos Necesarios para App
            $user_data = $this->getUserData($result['usuarios_id']);

            //Obtenemos direcciones del usuario
            $user_data['user_data']['direcciones']=$this->modAddr->getAddresByUser($result['usuarios_id']);

            $this->fillSession($user_data['user_data']);
            $this->logAuthenticationSuccess($result['usuarios_id']);
            
            foreach ($_SESSION as $key => $value) $return_data[$key] = $value;

            $return_data["departamento_session"] = $_SESSION['usuarios_departamento'];
            $return_data["departamento_nombre_session"] = 'No Configurado';
            $return_data["municipio_session"] = $_SESSION['usuarios_municipio'];
            $return_data["municipio_nombre_session"] = 'No Configurado';

            if(intval($_SESSION['usuarios_departamento'])!==0){
                $dpto = $this->mdDpto->getById($_SESSION['usuarios_departamento']);
                $return_data["departamento_nombre_session"] = $dpto[0]['departamento'];
            }

            if(intval($_SESSION['usuarios_municipio'])!==0){
                $muni = $this->mdMuni->getById($_SESSION['usuarios_municipio']);
                $return_data["municipio_nombre_session"] = $muni[0]['municipio'];
            }

            unset($return_data['url_redirect'], $return_data['tipo_accesos'], $return_data['usuarios_comision']);

            $return_data['error'] = 0;
            $return_data['mensaje'] = "autenticacion exitosa";       
        }   

        echo json_encode($return_data, JSON_UNESCAPED_UNICODE);
    }

    function seo_url($vp_string){
        $url = strtolower($vp_string);
        //Rememplazamos caracteres especiales latinos
        $find = array('á', 'é', 'í', 'ó', 'ú', 'ñ');
        $repl = array('a', 'e', 'i', 'o', 'u', 'n');
        $url = str_replace ($find, $repl, $url);
        // Añadimos los guiones
        $find = array(' ', '&', '\r\n', '\n', '+'); 
        $url = str_replace ($find, '-', $url);
        // Eliminamos y Reemplazamos demás caracteres especiales
        $find = array('/[^a-z0-9\-<>]/', '/[\-]+/', '/<[^>]*>/');
        $repl = array('', '-', '');
        $url = preg_replace ($find, $repl, $url);
        return $url;
    }

    /*
    ** ***** Direccion de Usuario  *****
    */
    public function direccionUsuario($tipo='', $id=''){
        $method = strtolower($this->input->server('REQUEST_METHOD'));

        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);

        switch($method){
            case 'post':  //Insert
                $this->mdAddUse->addAddress($postdata['data']);
                $return_data['error'] = 0;
                $return_data['mensaje'] = "registro de dirección guardada con exito"; 
                break;
            case 'get':  //Consulta
                $return_data['data'] = ($tipo==='direccion') ? $this->mdAddUse->getAddresById($id) : $this->mdAddUse->getAddresByUser($id);
                if(count($return_data['data'])>0){
                    $return_data['mensaje'] = "consulta de dirección exitosa";
                }else{
                    $return_data['mensaje'] = "no se ha encontrado resultados";
                }
                $return_data['error'] = 0;
                 
                break;
            case 'put':  //Actualización
                $this->mdAddUse->updateAddress($postdata['data'], $postdata['filter']['id_dir']);
                $return_data['error'] = 0;
                $return_data['mensaje'] = "Actualización de direccion exitosa"; 
                break;
            case 'delete':  //Eliminar
                $filter=[['id_dir', $postdata['filter']['id_dir']]];
                $this->mdAddUse->deleteAddress($filter);
                $return_data['error'] = 0;
                $return_data['mensaje'] = "Eliminada direccion"; 
                break;
            default:
                $return_data['error'] = 1;
                $return_data['mensaje'] = "Metodo desconocido, intente generar Update, Post o Get";
                break;
        }
        print json_encode($return_data, JSON_UNESCAPED_UNICODE);
    } 

    public function usuarioTokenPush($idUser){
        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);

        $usuario = $this->modUsu->single($idUser);

        if(!isset($usuario['usuarios_id'])){
            $data = ['error' => '1',
                    'mensaje' => "no se encontro usuario con este ID"];
            print json_encode($data, JSON_UNESCAPED_UNICODE);
            exit();
        }

        $data=[
            'usuarios_id'=>$idUser, 
            'token_push'=>$postdata['data']['tokenpush']
        ];

        if(count($usuario)===0){
            $return_data['error'] = 1;
            $return_data['mensaje'] = "no se encontro usuario con este ID";
        }else{          
            $this->modUsu->editData($data);
            $return_data['error'] = 0;
            $return_data['mensaje'] = "Actualización de dato Token exitosa"; 
        }
        print json_encode($return_data, JSON_UNESCAPED_UNICODE);
        exit();
    }

    /**
    * Registro por Redes Sociales (Firebase)
    *
    * la APP envia los datos de la red social, segun se elija
    * este envia unos datos en Json, la cual se recibe y con estos se consulta
    * si existe, se notificara en respuesta encode json...
    * sino... se iniciara el registro en la BD de ALDC
    *
    * @access public
    * @param array $_request->data datos que genera el login de firebase
    * @return array respuesta de un SI o NO ha quedado el registro
    */
    public function videosProductosPorCategoria(){
        $postdata = $this->getpost();
        $postdata=json_decode($postdata, JSON_UNESCAPED_UNICODE);

        if(!isset($postdata['data'])){ print json_encode(['resp'=>'ok', 'estado'=>'error', 'msg'=>'dato faltante: data']); exit(); }

        $prodMod=[];
        $productos = $this->productos_model->getProductsVideoByCategory($postdata['data']['id']);

        foreach($productos as $key => $prod):
            $prod['linkpage']=base_url('tienda/single/'.$prod['productos_id'].'/'.limpiarUri($prod['productos_titulo']));
            $prodMod[]=$prod;
        endforeach;
        print json_encode($prodMod);
    }
}