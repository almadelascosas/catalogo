<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
class Medios extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('medios');
        $this->load->helper(array('commun'));
        $this->load->model("medios_model");
        $this->load->model("usuarios_model", "mdUsuarios");
    }

    public function index()
    {
        $page = 1;
        if (isset($_GET['page'])) $page = $_GET['page'];

        $registroMedios = $this->medios_model->getAll($page);

        $datos = [
          'view' => "medios/index",
          'css_data' => [
            '/assets/plugins/dropify/dropify.min.css',
          ],
          'js_data' => [ 
            'assets/plugins/dropify/dropify.min.js',
            'assets/js/pages/medios/index.js?'.rand() 
          ],
          'medios' => $registroMedios,
          'mdUsuarios' => $this->mdUsuarios
        ];

        //Verificacion Imagen miniatura
        foreach($registroMedios as $key => $med) getMiniaturaProduct($med['medios_id']);

        $this->load->view('normal_view', $datos);
    }
    
    public function paginadoGaleria()
    {
        if (isset($_POST['page'])) {
            $datos = array();
            $page = $_POST['page'];
            $datos['medios'] = $this->medios_model->getAll($page);
            $this->load->view('themes/admin/galeria/paginado', $datos);
        }
    }

    public function agregar()
    {
        $datos=[
            'view' => "medios/add",
            'css_data' => [
                    '/assets/plugins/dropify/dropify.min.css'
                ],
            'js_data' => [
                    'assets/plugins/dropify/dropify.min.js',
                    'assets/js/pages/medios/index.js?'.rand()
                ],
            'medio' => $this->medios_model->vacio()
        ];
        $this->load->view('normal_view', $datos);
    }

    public function editar($id, $nombre)
    {
        $datos = [
            'view' => "medios/add",
            'css_data' => [
                    'assets/plugins/dropify/dropify.min.css'
                ],
            'js_data' => [
                    'assets/plugins/dropify/dropify.min.js',
                    'assets/js/pages/medios/index.js?'.rand()
                ],
            'medio' => $this->medios_model->single($id)
        ];
        $this->load->view('normal_view', $datos);
    }

    public function guardar(){
        if(isset($_FILES['medios_attachment']) && $_FILES['medios_attachment']!=='') $subida = $this->upload();
        $ingresar = ($this->input->post('medios_id')!=="" && $this->input->post('medios_id')!==0) ? $this->medios_model->edit($subida) : $this->medios_model->save($subida);

        if ($ingresar['data']) {
            $this->session->set_flashdata('success', '!Excelente, se ha guardado el registro');
        }else {
            $this->session->set_flashdata('error', '!Ups, no fue posible guardar registro de Medios');
        }
        redirect(base_url("medios"), []);
    }
    
    public function guardarAjax(){
        $datos = array();
        $subida = $this->upload();
        $ingresar = $this->medios_model->save($subida);
        if ($ingresar['data']) {
            //Exito
            $id = $this->db->insert_id();
            $datos['medio'] = json_encode($this->medios_model->single($id));
            $datos['result'] = 1;
            $datos['mensaje'] = "";
        }else {
            $datos['result'] = 0;
            $datos['mensaje'] = "Hubo un error inesperado, intente de nuevo";
        }
        echo json_encode($datos);
    }
    
    public function delete()
    {
        $datos = array();
        $id=$this->input->post("id");

        //Eliminamos Imagenes
        $medios = $this->db->where_in('medios_id', $id)->get('medios')->result_array();
        $medio = $medios[0];
        if(file_exists($medio['medios_enlace_miniatura'])) unlink($medio['medios_enlace_miniatura']);
        if(file_exists($medio['medios_url'])) unlink($medio['medios_url']);

        $query = $this->db->delete('medios', array('medios_id' => $id));

        if ($query) {
            // $datos['mensaje']="Eliminado con éxito";
            $this->session->set_flashdata('success', '!Excelente, se ha eliminado imagen(es) y registro');
            $datos['result']=1;
        }else {
            //$datos['mensaje']="Hubo un error, intente de nuevo!";
            $this->session->set_flashdata('error', '!Ups, no fue posible eliminar el registro de Medios');
            $datos['result']=0;
        }
        echo json_encode($datos);
    }


    public function deletemultiple(){
        $medios = $this->db->where_in('medios_id', $_POST['ckMedio'])->get('medios')->result_array();
        $cont=0;
        foreach($medios as $med):
            if(file_exists($med['medios_enlace_miniatura'])) unlink($med['medios_enlace_miniatura']);
            if(file_exists($med['medios_url'])) unlink($med['medios_url']);

            $this->db->delete('medios', ['medios_id' => $med['medios_id']]);
            $cont++;
        endforeach;

        if($cont>0){
            $this->session->set_flashdata('success', '!Excelente, se ha eliminado '.$cont.' registro(s) e imagen(es)');
        }else{
            $this->session->set_flashdata('error', '!Ups, no fue posible eliminar el(los) registro(s) de Medios');
        }
        redirect('medios', 'refresh');
        exit();
    }

    public function upload(){
        $datos = array();

        if($_FILES['medios_attachment']['type']=='image/png' 
        || $_FILES['medios_attachment']['type']=='image/jpeg' 
        || $_FILES['medios_attachment']['type']=='image/jpg' 
        || $_FILES['medios_attachment']['type']=='image/gif'){
            $nombre_fichero =  "assets/uploads/".limpiarUri(date("m-Y"));
            if (file_exists($nombre_fichero)) {
                
            }else{
                mkdir("assets/uploads/".limpiarUri(date("m-Y")), 0777);
            }
            $max_ancho = 1280;
            $max_alto = 900;

            /*
            $max_ancho_2 = 1280;
            $max_alto_2 = 1280;
            */

            $max_ancho_2 = 260;
            $max_alto_2 = 260;

            $medidasimagen= getimagesize($_FILES['medios_attachment']['tmp_name']);
                $nombrearchivo=$_FILES['medios_attachment']['name'];
                //Redimensionar
                $rtOriginal=$_FILES['medios_attachment']['tmp_name'];
                $rtOriginal_2=$_FILES['medios_attachment']['tmp_name'];

                if($_FILES['medios_attachment']['type']=='image/jpeg'){
                    $original = imagecreatefromjpeg($rtOriginal);
                }
                if($_FILES['medios_attachment']['type']=='image/jpg'){
                    $original = imagecreatefromjpeg($rtOriginal);
                }
                else if($_FILES['medios_attachment']['type']=='image/png'){
                    $original = imagecreatefrompng($rtOriginal);
                }
                else if($_FILES['medios_attachment']['type']=='image/gif'){
                    $original = imagecreatefromgif($rtOriginal);
                }
                if($_FILES['medios_attachment']['type']=='image/jpeg'){
                    $original_2 = imagecreatefromjpeg($rtOriginal_2);
                }
                if($_FILES['medios_attachment']['type']=='image/jpg'){
                    $original_2 = imagecreatefromjpeg($rtOriginal_2);
                }
                else if($_FILES['medios_attachment']['type']=='image/png'){
                    $original_2 = imagecreatefrompng($rtOriginal_2);
                }
                else if($_FILES['medios_attachment']['type']=='image/gif'){
                    $original_2 = imagecreatefromgif($rtOriginal_2);
                }
                
                list($ancho,$alto)=getimagesize($rtOriginal);
                list($ancho_2,$alto_2)=getimagesize($rtOriginal_2);

                $x_ratio = $max_ancho / $ancho;
                $y_ratio = $max_alto / $alto;

                $x_ratio_2 = $max_ancho_2 / $ancho_2;
                $y_ratio_2 = $max_alto_2 / $alto_2;


                if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
                    $ancho_final = $ancho;
                    $alto_final = $alto;
                }
                elseif (($x_ratio * $alto) < $max_alto){
                    $alto_final = ceil($x_ratio * $alto);
                    $ancho_final = $max_ancho;
                }
                else{
                    $ancho_final = ceil($y_ratio * $ancho);
                    $alto_final = $max_alto;
                }

                if( ($ancho_2 <= $max_ancho_2) && ($alto_2 <= $max_alto_2) ){
                    $ancho_final_2 = $ancho_2;
                    $alto_final_2 = $alto_2;
                }
                elseif (($x_ratio_2 * $alto_2) < $max_alto_2){
                    $alto_final_2 = ceil($x_ratio_2 * $alto_2);
                    $ancho_final_2 = $max_ancho_2;
                }
                else{
                    $ancho_final_2 = ceil($y_ratio_2 * $ancho_2);
                    $alto_final_2 = $max_alto_2;
                }

                $lienzo=imagecreatetruecolor($ancho_final,$alto_final);
                $negro = imagecolorallocate($lienzo, 0, 0, 0);
                // Hacer el fondo transparente
                imagecolortransparent($lienzo, $negro); 
                imagecopyresampled($lienzo,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
                
                $lienzo2=imagecreatetruecolor($ancho_final_2,$alto_final_2); 
                $negro = imagecolorallocate($lienzo2, 0, 0, 0);
                // Hacer el fondo transparente
                imagecolortransparent($lienzo2, $negro);
                imagecopyresampled($lienzo2,$original_2,0,0,0,0,$ancho_final_2, $alto_final_2,$ancho_2,$alto_2);

                imagedestroy($original);
                imagedestroy($original_2);
 
                $cal=9;
                $rutaElemento = "assets/uploads/".limpiarUri(date("m-Y"))."/".limpiarUri(date("d-m-Y_his"))."_".limpiarConPunto($_FILES['medios_attachment']['name']);
                $rutaElemento400 = "assets/uploads/".limpiarUri(date("m-Y"))."/miniatura_".limpiarUri(date("d-m-Y_his"))."_".limpiarConPunto($_FILES['medios_attachment']['name']);

                if($_FILES['medios_attachment']['type']=='image/jpeg'){
                    imagejpeg($lienzo,$rutaElemento);
                    imagejpeg($lienzo2,$rutaElemento400);
                }
                else if($_FILES['medios_attachment']['type']=='image/jpg'){
                    imagejpeg($lienzo,$rutaElemento);
                    imagejpeg($lienzo2,$rutaElemento400);
                }
                else if($_FILES['medios_attachment']['type']=='image/png'){
                    imagepng($lienzo,$rutaElemento);
                    imagepng($lienzo2,$rutaElemento400);
                }
                else if($_FILES['medios_attachment']['type']=='image/gif'){
                    imagegif($lienzo,$rutaElemento);
                    imagegif($lienzo2,$rutaElemento400);
                }
                $datos["medios_url"] = $rutaElemento;
                $datos["medios_enlace_miniatura"] = $rutaElemento400;
                $nombreEx = explode(".",$_FILES["medios_attachment"]["name"]);
                $datos["medios_titulo"] = "";
                for ($i=0; $i < count($nombreEx); $i++) {
                    if ($i!=count($nombreEx)-1) {
                        $datos["medios_titulo"] .= $nombreEx[$i];
                    }
                }
                if (isset($_SESSION['usuarios_id'])) {
                    $datos["medios_user"] = $_SESSION['usuarios_id'];
                }else{
                    $datos["medios_user"] = 0;
                }
                $datos["success"] = 1;

        }else{
            $nombre_fichero =  "assets/uploads/".limpiarUri(date("m-Y"));
            $rutaElemento = "assets/uploads/".limpiarUri(date("m-Y"))."/".limpiarUri(date("d-m-Y_his"))."_".limpiarConPunto($_FILES['medios_attachment']['name']);
            if (file_exists($nombre_fichero)) {
                if (move_uploaded_file($_FILES["medios_attachment"]["tmp_name"], $rutaElemento)) {
                    $datos["medios_url"] = $rutaElemento;
                    $nombreEx = explode(".",$_FILES["medios_attachment"]["name"]);
                    $datos["medios_titulo"] = "";
                    for ($i=0; $i < count($nombreEx); $i++) {
                        if ($i!=count($nombreEx)-1) {
                            $datos["medios_titulo"] .= $nombreEx[$i];
                        }
                    }
                    if (isset($_SESSION['usuarios_id'])) {
                        $datos["medios_user"] = $_SESSION['usuarios_id'];
                    }else{
                        $datos["medios_user"] = 0;
                    }
                    $datos["success"] = 1;
    
                } else {
                    $datos["success"] = 1;
                }
            } else {
                mkdir("assets/uploads/".limpiarUri(date("m-Y")), 0777);
                if (move_uploaded_file($_FILES["medios_attachment"]["tmp_name"], $rutaElemento)) {
                    $datos["medios_url"] = $rutaElemento;
                    $nombreEx = explode(".",$_FILES["medios_attachment"]["name"]);
                    $datos["medios_titulo"] = "";
                    for ($i=0; $i < count($nombreEx); $i++) {
                        if ($i!=count($nombreEx)-1) {
                            $datos["medios_titulo"] .= $nombreEx[$i];
                        }
                    }
                    if (isset($_SESSION['usuarios_id'])) {
                        $datos["medios_user"] = $_SESSION['usuarios_id'];
                    }else{
                        $datos["medios_user"] = 0;
                    }
                    $datos["success"] = 1;
                } else {
                    $datos["success"] = 1;
                }
            }
        }

        return $datos;

    }

    public function uploadUpdate(){
        $datos = array();

        if($_FILES['medios_attachment']['type']=='image/png' 
        || $_FILES['medios_attachment']['type']=='image/jpeg' 
        || $_FILES['medios_attachment']['type']=='image/jpg' 
        || $_FILES['medios_attachment']['type']=='image/gif'){
            $nombre_fichero =  "assets/uploads/".limpiarUri(date("m-Y"));
            if (file_exists($nombre_fichero)) {
                
            }else{
                mkdir("assets/uploads/".limpiarUri(date("m-Y")), 0777);
            }
            $max_ancho = 1280;
            $max_alto = 900;

            /*
            $max_ancho_2 = 1280;
            $max_alto_2 = 1280;
            */

            $max_ancho_2 = 260;
            $max_alto_2 = 260;

            $medidasimagen= getimagesize($_FILES['medios_attachment']['tmp_name']);
                $nombrearchivo=$_FILES['medios_attachment']['name'];
                //Redimensionar
                $rtOriginal=$_FILES['medios_attachment']['tmp_name'];
                $rtOriginal_2=$_FILES['medios_attachment']['tmp_name'];

                if($_FILES['medios_attachment']['type']=='image/jpeg'){
                    $original = imagecreatefromjpeg($rtOriginal);
                }
                if($_FILES['medios_attachment']['type']=='image/jpg'){
                    $original = imagecreatefromjpeg($rtOriginal);
                }
                else if($_FILES['medios_attachment']['type']=='image/png'){
                    $original = imagecreatefrompng($rtOriginal);
                }
                else if($_FILES['medios_attachment']['type']=='image/gif'){
                    $original = imagecreatefromgif($rtOriginal);
                }
                if($_FILES['medios_attachment']['type']=='image/jpeg'){
                    $original_2 = imagecreatefromjpeg($rtOriginal_2);
                }
                if($_FILES['medios_attachment']['type']=='image/jpg'){
                    $original_2 = imagecreatefromjpeg($rtOriginal_2);
                }
                else if($_FILES['medios_attachment']['type']=='image/png'){
                    $original_2 = imagecreatefrompng($rtOriginal_2);
                }
                else if($_FILES['medios_attachment']['type']=='image/gif'){
                    $original_2 = imagecreatefromgif($rtOriginal_2);
                }
                
                list($ancho,$alto)=getimagesize($rtOriginal);
                list($ancho_2,$alto_2)=getimagesize($rtOriginal_2);

                $x_ratio = $max_ancho / $ancho;
                $y_ratio = $max_alto / $alto;

                $x_ratio_2 = $max_ancho_2 / $ancho_2;
                $y_ratio_2 = $max_alto_2 / $alto_2;


                if( ($ancho <= $max_ancho) && ($alto <= $max_alto) ){
                    $ancho_final = $ancho;
                    $alto_final = $alto;
                }
                elseif (($x_ratio * $alto) < $max_alto){
                    $alto_final = ceil($x_ratio * $alto);
                    $ancho_final = $max_ancho;
                }
                else{
                    $ancho_final = ceil($y_ratio * $ancho);
                    $alto_final = $max_alto;
                }

                if( ($ancho_2 <= $max_ancho_2) && ($alto_2 <= $max_alto_2) ){
                    $ancho_final_2 = $ancho_2;
                    $alto_final_2 = $alto_2;
                }
                elseif (($x_ratio_2 * $alto_2) < $max_alto_2){
                    $alto_final_2 = ceil($x_ratio_2 * $alto_2);
                    $ancho_final_2 = $max_ancho_2;
                }
                else{
                    $ancho_final_2 = ceil($y_ratio_2 * $ancho_2);
                    $alto_final_2 = $max_alto_2;
                }

                $lienzo=imagecreatetruecolor($ancho_final,$alto_final);
                $negro = imagecolorallocate($lienzo, 0, 0, 0);
                // Hacer el fondo transparente
                imagecolortransparent($lienzo, $negro); 
                imagecopyresampled($lienzo,$original,0,0,0,0,$ancho_final, $alto_final,$ancho,$alto);
                
                $lienzo2=imagecreatetruecolor($ancho_final_2,$alto_final_2); 
                $negro = imagecolorallocate($lienzo2, 0, 0, 0);
                // Hacer el fondo transparente
                imagecolortransparent($lienzo2, $negro);
                imagecopyresampled($lienzo2,$original_2,0,0,0,0,$ancho_final_2, $alto_final_2,$ancho_2,$alto_2);

                imagedestroy($original);
                imagedestroy($original_2);
 
                $cal=9;
                $rutaElemento = "assets/uploads/".limpiarUri(date("m-Y"))."/".limpiarUri(date("d-m-Y_his"))."_".limpiarConPunto($_FILES['medios_attachment']['name']);
                $rutaElemento400 = "assets/uploads/".limpiarUri(date("m-Y"))."/miniatura_".limpiarUri(date("d-m-Y_his"))."_".limpiarConPunto($_FILES['medios_attachment']['name']);

                if($_FILES['medios_attachment']['type']=='image/jpeg'){
                    imagejpeg($lienzo,$rutaElemento);
                    imagejpeg($lienzo2,$rutaElemento400);
                }
                else if($_FILES['medios_attachment']['type']=='image/jpg'){
                    imagejpeg($lienzo,$rutaElemento);
                    imagejpeg($lienzo2,$rutaElemento400);
                }
                else if($_FILES['medios_attachment']['type']=='image/png'){
                    imagepng($lienzo,$rutaElemento);
                    imagepng($lienzo2,$rutaElemento400);
                }
                else if($_FILES['medios_attachment']['type']=='image/gif'){
                    imagegif($lienzo,$rutaElemento);
                    imagegif($lienzo2,$rutaElemento400);
                }
                $datos["medios_url"] = $rutaElemento;
                $datos["medios_enlace_miniatura"] = $rutaElemento400;
                $nombreEx = explode(".",$_FILES["medios_attachment"]["name"]);
                $datos["medios_titulo"] = "";
                for ($i=0; $i < count($nombreEx); $i++) {
                    if ($i!=count($nombreEx)-1) {
                        $datos["medios_titulo"] .= $nombreEx[$i];
                    }
                }
                if (isset($_SESSION['usuarios_id'])) {
                    $datos["medios_user"] = $_SESSION['usuarios_id'];
                }else{
                    $datos["medios_user"] = 0;
                }
                $datos["success"] = 1;

        }else{
            $nombre_fichero =  "assets/uploads/".limpiarUri(date("m-Y"));
            $rutaElemento = "assets/uploads/".limpiarUri(date("m-Y"))."/".limpiarUri(date("d-m-Y_his"))."_".limpiarConPunto($_FILES['medios_attachment']['name']);
            if (file_exists($nombre_fichero)) {
                if (move_uploaded_file($_FILES["medios_attachment"]["tmp_name"], $rutaElemento)) {
                    $datos["medios_url"] = $rutaElemento;
                    $nombreEx = explode(".",$_FILES["medios_attachment"]["name"]);
                    $datos["medios_titulo"] = "";
                    for ($i=0; $i < count($nombreEx); $i++) {
                        if ($i!=count($nombreEx)-1) {
                            $datos["medios_titulo"] .= $nombreEx[$i];
                        }
                    }
                    if (isset($_SESSION['usuarios_id'])) {
                        $datos["medios_user"] = $_SESSION['usuarios_id'];
                    }else{
                        $datos["medios_user"] = 0;
                    }
                    $datos["success"] = 1;
    
                } else {
                    $datos["success"] = 1;
                }
            } else {
                mkdir("assets/uploads/".limpiarUri(date("m-Y")), 0777);
                if (move_uploaded_file($_FILES["medios_attachment"]["tmp_name"], $rutaElemento)) {
                    $datos["medios_url"] = $rutaElemento;
                    $nombreEx = explode(".",$_FILES["medios_attachment"]["name"]);
                    $datos["medios_titulo"] = "";
                    for ($i=0; $i < count($nombreEx); $i++) {
                        if ($i!=count($nombreEx)-1) {
                            $datos["medios_titulo"] .= $nombreEx[$i];
                        }
                    }
                    if (isset($_SESSION['usuarios_id'])) {
                        $datos["medios_user"] = $_SESSION['usuarios_id'];
                    }else{
                        $datos["medios_user"] = 0;
                    }
                    $datos["success"] = 1;
                } else {
                    $datos["success"] = 1;
                }
            }
        }

        return $datos;

    }

    public function galeria(){
        $datos['medios'] = $this->medios_model->getAll();
        $this->load->view('themes/admin/galeria/index', $datos);
    }
    public function galeriaMini(){
        $datos['medios'] = $this->medios_model->getAll();
        $this->load->view('themes/admin/galeria/indexMini', $datos);
    }
    public function galeriaBanner(){
        $datos['medios'] = $this->medios_model->getAll();
        $this->load->view('themes/admin/galeria/indexBanner', $datos);
    }
}
