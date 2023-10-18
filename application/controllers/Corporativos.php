<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller.php');
class Corporativos extends MY_Controller
{
    public function __construct()
    {
        parent::__construct('corporativos');
        $this->load->helper(array('commun'));
        $this->load->model("corporativos_model");
        $this->load->model("medios_model");
        $this->load->model("general_model");
        $this->load->library('phpmailing');
    }

    public function index(){
        
        $datos = [
            'vista' => "front",
            'custom_header' => "header_corporativos",
            'css_data' => [
                'assets/plugins/owl/dist/assets/owl.carousel.min.css',
                'assets/css/pages/front/corporativos/index.css?'.rand(),
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css',
                'https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900'
            ],
            'js_data' => [
                'assets/plugins/owl/dist/owl.carousel.min.js',
                'assets/js/front/corporativos/index.js?'.rand()
            ],            
            'banner_corporativos' => $this->general_model->bannerCorporativos(),
            'view' => "corporativos/index"
        ];
        
        $this->db->where('medios_id', $datos['banner_corporativos']['banner_imagen_mobile']);
        $res = $this->db->get('medios')->result_array();
        foreach($res as $key => $value) $datos['banner_corporativos']['banner_imagen_mobile_url'] = $value['medios_url'];       

        $this->load->view('normal_view',$datos);
    }

    public function mailform(){
        if (isset($_POST['nombre_completo']) && isset($_POST['correo_electronico']) && isset($_POST['numero_celular']) && isset($_POST['mensaje'])) {
            $guardar = $this->corporativos_model->save();    
            //Create a new PHPMailer instance
            $mail = new Phpmailing();
            //Set who the message is to be sent from
            $mail->setFrom('admin@almadelascosas.com', 'Alma de las Cosas');
            //Set an alternative reply-to address
            $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las Cosas');
            //Set who the message is to be sent to
            $mail->addAddress($_POST['correo_electronico'], $_POST['nombre_completo']);
            //Set the subject line
            $mail->Subject = 'Corporativos - Alma de las Cosas';
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
            $cuerpo = '
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Corporativos - Alma de las Cosas</title>
                <style> *, ::after, ::before { box-sizing: border-box; } .row { display: -ms-flexbox; display: flex; -ms-flex-wrap: wrap; flex-wrap: wrap; margin-right: -15px; margin-left: -15px; } .col, .col-1, .col-10, .col-11, .col-12, .col-2, .col-3, .col-4, .col-5, .col-6, .col-7, .col-8, .col-9, .col-auto, .col-lg, .col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-auto, .col-md, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-auto, .col-sm, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-auto, .col-xl, .col-xl-1, .col-xl-10, .col-xl-11, .col-xl-12, .col-xl-2, .col-xl-3, .col-xl-4, .col-xl-5, .col-xl-6, .col-xl-7, .col-xl-8, .col-xl-9, .col-xl-auto { position: relative; width: 100%; padding-right: 15px; padding-left: 15px; } .col-12 { -ms-flex: 0 0 100%; flex: 0 0 100%; max-width: 100%; } .col-6 { -ms-flex: 0 0 50%; flex: 0 0 50%; max-width: 50%; } img{ max-width: 100%; } body { background: #F6F6F6; margin: 0px; font-family: sans-serif; } .text-center { text-align: center; } .text-left { text-align: left; } .text-right { text-align: right; } .border-right { border-right: 2px solid; } .top { background: #285858; color: #fff; padding: 15px; } .cuerpo { max-width: 600px; margin: auto; padding-left: 15px; padding-right: 15px; } img.logo { width: 60px; padding-top: 10px; padding-bottom: 10px; } .logo-info p { margin: 0px; } .logo-info p:first-child { margin-bottom: 5px; margin-top: 12px; } .border-right { border-right: 2px solid; } .image-info { min-height: 250px; } .image-info img{ max-width: 230px; margin-bottom: 25px; margin-top: 25px; } h1 { font-size: 22px; color: #707070; } p{ color: #707070; font-size: 16px; } .top p { color: #fff; } .info-cliente { padding-right: 40px; padding-left: 40px; } .btn-ws { background-color: #1FD161; box-shadow: 0px 10px 24px #1fd16180; color: #fff; border: none; border-radius: 44px; padding: 10px 30px; font-weight: bold; font-size: 16px; text-decoration: none; margin-top: 25px; display: inline-block; margin-bottom: 25px; } .contact { padding: 25px; } .box { box-shadow: 0px 8px 10px #00000029; border-radius: 22px; padding: 20px; padding-bottom: 30px; background: #fff; } h2 { font-size: 20px; color: #707070; } .copyright p { font-size: 12px; color: #C3C3C3; } .footer { margin-top: 50px; } ul.redes li { border-radius: 100%; display: inline-block; background-color: transparent; padding: 10px; cursor: pointer; margin-bottom: 23px; height: 40px; width: 40px; font-size: 17px; color: #fff; } ul.redes { padding: 0px; list-style: none; } .redes img { height: 20px; }                </style>
            </head>
            <body>
                <div class="cuerpo" style="background:#F6F6F6;">
                    <div class="col-12 top">
                        <div class="row">
                            <div class="col-6 text-right border-right">
                                <img class="logo" src="https://almadelascosas.com/assets/img/corporativos/LOGO_ALMA_BLANCO.png" alt="Logo">
                            </div>
                            <div class="col-6 logo-info" style="padding-top:10px;">
                                <p>Línea</p>
                                <p><strong>Empresas</strong></p>
                            </div>
                
                        </div>
                    </div>
                    <div class="image-info text-center">
                        <img src="https://almadelascosas.com/assets/img/mail-pedido-recibido.png" alt="Mail">
                    </div>
                    <div class="info-cliente text-center">
                        <h1>¡Hola, '.$_POST['nombre_completo'].'!</h1>
                        <p>Hemos recibido tu solicitud, en breve uno de nuestros asesores se pondrá en contacto contigo para ayudarte en todo lo que necesites</p>
                    </div>
                    <div class="contact">
                        <div class="box text-center" style="box-shadow: 0px 8px 10px #00000029;
                        border-radius: 22px;
                        padding: 20px;
                        padding-bottom: 30px;
                        background: #fff;">
                            <h2 class="text-center">¿No te hemos contactado aún?</h2>
                            <p class="text-left">Escríbenos directamente a nuestra línea de WhatsApp para atender tu solicitud en instantes</p>
                            <a class="btn-ws" style="color: #fff!important;" href="https://api.whatsapp.com/send?phone=+573502045177&text=Hola Alma de las Cosas! Me interesa la línea corporativa para mi empresa">Contactarme con un asesor/a</a>
                        </div>
                    </div>
                    <div class="footer text-center">
                        <ul class="redes">
                            <li><a href="https://www.facebook.com/almadelascosas"><img src="https://almadelascosas.com/assets/img/facebook-icon.png" alt="icon"></a></li>
                            <li><a href="https://instagram.com/almadelascosas?igshid=YmMyMTA2M2Y="><img src="https://almadelascosas.com/assets/img/logo_instagram.png" alt="icon"></a></li>
                            <li><a href="https://vm.tiktok.com/ZMNJk3rvD/"><img src="https://almadelascosas.com/assets/img/logo_tiktok.png" alt="icon"></a></li>
                        </ul>
                        <div class="copyright">
                            <p>Copyright © 2022 <br>Todos los derechos reservados</p>
                        </div>
                    </div>
                </div>
            </body>
            </html>
            ';
            $mail->msgHTML(utf8_decode($cuerpo));
            //Replace the plain text body with one created manually
            $mail->AltBody = 'This is a plain-text message body';
            //Attach an image file
            //$mail->addAttachment('images/phpmailer_mini.png');

            //send the message, check for errors
            if ($mail->send()) {
                $datos['result']= 1;
                $datos['mensaje']= "Envío Éxitoso";
                //Create a new PHPMailer instance
                $mail = new Phpmailing();
                //Set who the message is to be sent from
                $mail->setFrom('admin@almadelascosas.com', 'Alma de las Cosas');
                //Set an alternative reply-to address
                $mail->addReplyTo('admin@almadelascosas.com', 'Alma de las Cosas');
                //Set who the message is to be sent to
                $mail->addAddress("admin@almadelascosas.com", "Alma de las Cosas");
                //Set the subject line
                $mail->Subject = 'Nuevo formulario Corporativos';
                //Read an HTML message body from an external file, convert referenced images to embedded,
                //convert HTML into a basic plain-text alternative body
                //$mail->msgHTML(file_get_contents('contents.html'), __DIR__);
                $cuerpo = '
                <!DOCTYPE html>
                <html lang="es">
                <head>
                    <meta charset="UTF-8">
                    <meta http-equiv="X-UA-Compatible" content="IE=edge">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Corporativos</title>
                </head>
                <body>
                    <h3>Nuevo Correo Corporativos</h3>
                    <p>
                    Nombre cliente: '.utf8_decode($_POST['nombre_completo']).'<br>
                    Correo: '.utf8_decode($_POST['correo_electronico']).'<br>
                    '.utf8_decode('Número').': '.utf8_decode($_POST['numero_celular']).'<br>
                    Mensaje: '.utf8_decode($_POST['mensaje']).'
                    </p>
                </body>
                </html>
                ';
                $mail->msgHTML($cuerpo);
                //Replace the plain text body with one created manually
                $mail->AltBody = 'This is a plain-text message body';
                //Attach an image file
                //$mail->addAttachment('images/phpmailer_mini.png');

                //send the message, check for errors
                if ($mail->send()) {
                    $datos['result']= 1;
                    $datos['mensaje']= "Envío Éxitoso";
                    redirect(base_url()."regalos-corporativos?mensaje=sucess");
                } else {
                    $datos['result']= 0;
                    $datos['mensaje']= "Error al Enviar";
                    redirect(base_url()."regalos-corporativos?mensaje=failed");
                }
            } else {
                $datos['result']= 0;
                $datos['mensaje']= "Error al Enviar";
                redirect(base_url()."regalos-corporativos?mensaje=failed");
            }

        }
    }

    public function admin(){
        if (isset($_SESSION['usuarios_id']) && isset($_SESSION['tipo_accesos'])) {
            $datos = array();
            $datos['view'] = "corporativos/bandeja_entrada";
            $datos['css_data'] = array(
                '/assets/css/pages/corporativos/index.css?v='.rand(99,999),
            );
            $datos['js_data'] = array(
                'assets/js/pages/corporativos/index.js?v='.rand(99,9999),
            );
            $filtros = array();
            $page = 1;
            $limit = 12;
            $limite = array();
            if (!isset($_GET['page'])) {
                $limite=array($limit,$page-1);
            }else{
                $page = $_GET['page']-1;
                $paginado = $limit*$page;
                $limite=array($limit,$paginado);
            }
            $datos['corporativos'] = $this->corporativos_model->getAll($filtros,$limite);
            $this->load->view('normal_view', $datos);
        }else{
            redirect(base_url()."auth/login");
        }

    }

    public function edit_revisado(){
        $datos = array();
        $bandeja_corporativos_id = 0;
        if (isset($_POST['id'])) {
            $bandeja_corporativos_id = $_POST['id'];
            $this->db->set('bandeja_corporativos_revisado', $this->input->post("estatus"));
            $this->db->where('bandeja_corporativos_id', $bandeja_corporativos_id);
            $this->db->update('bandeja_corporativos');
            $datos['result'] = 1;
            $datos['mensaje'] = "Editado Correctamente";
        }else{
            $datos['result'] = 0;
            $datos['mensaje'] = "ID no existente";
        }
        echo json_encode($datos);
    }


}