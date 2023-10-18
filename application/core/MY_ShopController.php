<?php
class MY_ShopController extends CI_Controller {

    // List of classes that can be accessed when the user is
    // not authenticated
    protected $_open_controllers = array();
    protected $url_redirect = "";
    public function __construct(){
          parent::__construct();
              // Check auth
        $CI =& get_instance();
				$CI->load->model('shop_model');
        $CI->load->model('cartweb_model');
        $categorias = $CI->shop_model->getCategorias();
        $this->load->vars(array('categorias'=>$categorias));


        $cart = $CI->cartweb_model->getFullCart();
        $this->load->vars(array('cart'=>$cart));

        $url_server = base_url();
        $url_dir = FCPATH;
        $global_data = array('ruta_imagenes'=>$url_server."/attachments/images_products_ps");
        $this->load->vars($global_data);
        $global_data = array('check_imagenes'=>$url_dir."/attachments/images_products_ps");
        $this->load->vars($global_data);

        $global_data = array('ruta_iconos'=>$url_server."/attachments/");
        $this->load->vars($global_data);

    }

    // ----------------------------------------------------------------
    private function _check_auth(){

    }
}
