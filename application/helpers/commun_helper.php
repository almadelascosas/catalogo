<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

function delete_cache($uri_string=null)
{
    $CI =& get_instance();
    $path = $CI->config->item('cache_path');
    $path = rtrim($path, DIRECTORY_SEPARATOR);

    $cache_path = ($path == '') ? APPPATH.'cache/' : $path;

    $uri =  $CI->config->item('base_url').
            $CI->config->item('index_page').
            $uri_string;

    $cache_path .= md5($uri);

    return unlink($cache_path);
}
function getSignature ($params,$key)

{
    /**
     * Función que calcula la firma.
     * $ params: matriz que contiene los campos que se enviarán en la IPN.
     * $key : clave de TEST o PRODUCTION
     */
    //Inicialización de la variable que contendrá el string a cifrar
    $contenu_signature = "";
    //Ordenar los campos alfabéticamente
    ksort($params);
    foreach($params as $nom=>$valeur){
        //Recuperación de los campos vads_
        if (substr($nom,0,5)=='vads_'){
            //Concatenación con el separador  "+"
            $contenu_signature .= $valeur."+";
            }
    }
    //Añadir la clave al final del string
    $contenu_signature .= $key;
    //Codificación base64 del string cifrada con el algoritmo HMAC-SHA-256
    $sign = base64_encode(hash_hmac('sha256',$contenu_signature, $key, true));
    return $sign;
}
function httpPost($url, $data)
{
   	$curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function whatsappButton(){
    $CI =& get_instance();
    $CI->db->select("*");
    $CI->db->where("whatsapp_plugin_estatus",1);
    $CI->db->limit(1);
    $CI->db->order_by("whatsapp_plugin_cantidad","ASC");
    $whatsapp_plugin_cons = $CI->db->get("whatsapp_plugin");
    $datos_wp = array();
    foreach ($whatsapp_plugin_cons->result_array() as $key => $value) {
        $datos_wp[$key] = $value; 
    }
    $html = "";
    if ($datos_wp!=array()) {
        $html = '
        <div id="btn-whatsapp">
            <a id="btn-whatsapp-2" target="_blank" data-service_number="'.$datos_wp[0]["whatsapp_plugin_cantidad"].'" data-service_client_id="'.$datos_wp[0]["whatsapp_plugin_id"].'" class="btn-whatsapp" rel="noopener noreferrer" href="https://api.whatsapp.com/send?phone='.$datos_wp[0]["whatsapp_plugin_telefono"].'&text='.$datos_wp[0]["whatsapp_plugin_mensaje"].'">
                <svg style="pointer-events:none; display:block; height:50px; width:50px;" width="50px" height="50px" viewBox="0 0 1024 1024">
                <defs>
                <path id="htwasqicona-chat" d="M1023.941 765.153c0 5.606-.171 17.766-.508 27.159-.824 22.982-2.646 52.639-5.401 66.151-4.141 20.306-10.392 39.472-18.542 55.425-9.643 18.871-21.943 35.775-36.559 50.364-14.584 14.56-31.472 26.812-50.315 36.416-16.036 8.172-35.322 14.426-55.744 18.549-13.378 2.701-42.812 4.488-65.648 5.3-9.402.336-21.564.505-27.15.505l-504.226-.081c-5.607 0-17.765-.172-27.158-.509-22.983-.824-52.639-2.646-66.152-5.4-20.306-4.142-39.473-10.392-55.425-18.542-18.872-9.644-35.775-21.944-50.364-36.56-14.56-14.584-26.812-31.471-36.415-50.314-8.174-16.037-14.428-35.323-18.551-55.744-2.7-13.378-4.487-42.812-5.3-65.649-.334-9.401-.503-21.563-.503-27.148l.08-504.228c0-5.607.171-17.766.508-27.159.825-22.983 2.646-52.639 5.401-66.151 4.141-20.306 10.391-39.473 18.542-55.426C34.154 93.24 46.455 76.336 61.07 61.747c14.584-14.559 31.472-26.812 50.315-36.416 16.037-8.172 35.324-14.426 55.745-18.549 13.377-2.701 42.812-4.488 65.648-5.3 9.402-.335 21.565-.504 27.149-.504l504.227.081c5.608 0 17.766.171 27.159.508 22.983.825 52.638 2.646 66.152 5.401 20.305 4.141 39.472 10.391 55.425 18.542 18.871 9.643 35.774 21.944 50.363 36.559 14.559 14.584 26.812 31.471 36.415 50.315 8.174 16.037 14.428 35.323 18.551 55.744 2.7 13.378 4.486 42.812 5.3 65.649.335 9.402.504 21.564.504 27.15l-.082 504.226z"></path>
                </defs>
                <linearGradient id="htwasqiconb-chat" gradientUnits="userSpaceOnUse" x1="512.001" y1=".978" x2="512.001" y2="1025.023">
                    <stop offset="0" stop-color="#61fd7d"></stop>
                    <stop offset="1" stop-color="#2bb826"></stop>
                </linearGradient>
                <use xlink:href="#htwasqicona-chat" overflow="visible" fill="url(#htwasqiconb-chat)"></use>
                <g>
                    <path fill="#FFF" d="M783.302 243.246c-69.329-69.387-161.529-107.619-259.763-107.658-202.402 0-367.133 164.668-367.214 367.072-.026 64.699 16.883 127.854 49.017 183.522l-52.096 190.229 194.665-51.047c53.636 29.244 114.022 44.656 175.482 44.682h.151c202.382 0 367.128-164.688 367.21-367.094.039-98.087-38.121-190.319-107.452-259.706zM523.544 808.047h-.125c-54.767-.021-108.483-14.729-155.344-42.529l-11.146-6.612-115.517 30.293 30.834-112.592-7.259-11.544c-30.552-48.579-46.688-104.729-46.664-162.379.066-168.229 136.985-305.096 305.339-305.096 81.521.031 158.154 31.811 215.779 89.482s89.342 134.332 89.312 215.859c-.066 168.243-136.984 305.118-305.209 305.118zm167.415-228.515c-9.177-4.591-54.286-26.782-62.697-29.843-8.41-3.062-14.526-4.592-20.645 4.592-6.115 9.182-23.699 29.843-29.053 35.964-5.352 6.122-10.704 6.888-19.879 2.296-9.176-4.591-38.74-14.277-73.786-45.526-27.275-24.319-45.691-54.359-51.043-63.543-5.352-9.183-.569-14.146 4.024-18.72 4.127-4.109 9.175-10.713 13.763-16.069 4.587-5.355 6.117-9.183 9.175-15.304 3.059-6.122 1.529-11.479-.765-16.07-2.293-4.591-20.644-49.739-28.29-68.104-7.447-17.886-15.013-15.466-20.645-15.747-5.346-.266-11.469-.322-17.585-.322s-16.057 2.295-24.467 11.478-32.113 31.374-32.113 76.521c0 45.147 32.877 88.764 37.465 94.885 4.588 6.122 64.699 98.771 156.741 138.502 21.892 9.45 38.982 15.094 52.308 19.322 21.98 6.979 41.982 5.995 57.793 3.634 17.628-2.633 54.284-22.189 61.932-43.615 7.646-21.427 7.646-39.791 5.352-43.617-2.294-3.826-8.41-6.122-17.585-10.714z"></path>
                </g>
                </svg>
            </a>
        </div>
        ';
    }
    return $html;

}
function image($im){
    $res = base_url()."assets/img/Not-Image.png";
    if ($im!="") {
        $res = base_url().$im;
        $res =  str_replace(")","%29",$res);
        $res =  str_replace("(","%28",$res); 
    }
    return $res;
}
function getDepartamentos_new(){
    $CI =& get_instance();
    $CI->load->model("general_model");
    $datos = $CI->general_model->obtenerDepartamentos_new();
    return $datos;
}
function getMunicipios_new($id_departamento=0){
    $datos = array();
    if ($id_departamento!==0) {
        $CI =& get_instance();
        $filter=array();
        $filter['where'] = array("departamento_id",$id_departamento);
        $CI->load->model("general_model");
        $datos = $CI->general_model->obtenerMunicipios_new($filter);
    }
    return $datos;
}


function getDepartamentos(){
    $CI =& get_instance();
    $CI->load->model("general_model");
    $datos = $CI->general_model->obtenerDepartamentos();
    return $datos;
}

function getMunicipios($id_departamento=0){
    $datos = array();
    if ($id_departamento!==0) {
        $CI =& get_instance();
        $filter=array();
        $filter['where'] = array("departamento_id",$id_departamento);
        $CI->load->model("general_model");
        $datos = $CI->general_model->obtenerMunicipios($filter);
    }
    return $datos;
}
function getDepartamentoById($id){
    $CI =& get_instance();
    $CI->load->model("general_model");
    $dato = $CI->general_model->obtenerDepartamentoById($id);
    return $dato['departamento'];
}
function getMunicipioById($id){
    $CI =& get_instance();
    $CI->load->model("general_model");
    $dato = $CI->general_model->obtenerMunicipioById($id);
    return $dato['municipio'];
}

function getMunicipioByIdArray($ids){
    $CI =& get_instance();
    $CI->load->model("general_model");
    $dato = $CI->general_model->obtenerMunicipioByIdArray($ids);
    return $dato;
}

function obtenerMenu(){
    $CI =& get_instance();
    $CI->load->model("menu_model");
    $datos = $CI->menu_model->getAll();
    return $datos;
}
function obtenerMenuDash(){
    $CI =& get_instance();
    $CI->load->model("menu_model");
    $datos = $CI->menu_model->getAllDash();
    return $datos;
}
function productosCart(){
    if (isset($_SESSION['cart']) && $_SESSION['cart']!=NULL && $_SESSION['cart']!=array()) {
        $CI =& get_instance();
        $CI->load->model("productos_model");
        $id_productos = array();
        $filtros = array();
        foreach ($_SESSION['cart'] as $key => $value) {
            array_push($id_productos, $value['productos_id']);
        }
        $filtros['where_in_name'] = "productos_id";
        $filtros['where_in'] = $id_productos;
        $retorno = $CI->productos_model->getAll($filtros);
        return $retorno;
    }
}
function actualizarPedido($pedido = 0, $estatus = ""){
    if ($pedido!=0 && $estatus!="") {
        $CI =& get_instance();
        $est = "";
        $cons = $CI->db->select("pedidos_estatus")->where("pedidos_id",$pedido)->get("alma_pedidos");
        
        foreach ($cons->result_array() as $key => $value) {
            $est = $value['pedidos_estatus'];
        }
        if ($est == "" 
        || $est == "Esperando confirmación de pago"
        || $est == "Esperando confirmación de Pago"
        || $est == "1"
        || $est == 1
        ) {
            $CI->db->set('pedidos_estatus', $estatus);
            $CI->db->where('pedidos_id', $pedido);
            $CI->db->update('alma_pedidos');
        }
        return 1;
    }
}
function timeago($date) {
   $timestamp = $date;

   $strTime = array("segundo", "minuto", "hora", "dia", "mes", "año");
   $length = array("60","60","24","30","12","10");
   $currentTime = time();
		$diff     = time()- $timestamp;
		for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++) {
		$diff = $diff / $length[$i];
		}

		$diff = round($diff);
        if ($strTime[$i]=="mes") {
            return $diff . " " . $strTime[$i] . "(es)";
        }else{
            return $diff . " " . $strTime[$i] . "(s)";
        }
   
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function limpiarUri($s)
{
$s = str_replace('á', 'a', $s);
$s = str_replace('Á', 'A', $s);
$s = str_replace('é', 'e', $s);
$s = str_replace('É', 'E', $s);
$s = str_replace('í', 'i', $s);
$s = str_replace('Í', 'I', $s);
$s = str_replace('ó', 'o', $s);
$s = str_replace('Ó', 'O', $s);
$s = str_replace('Ú', 'U', $s);
$s= str_replace('ú', 'u', $s);

//Quitando Caracteres Especiales
$s= str_replace('"', '', $s);
$s= str_replace(':', '', $s);
$s= str_replace('.', '', $s);
$s= str_replace(',', '', $s);
$s= str_replace(';', '', $s);
$s= str_replace(' ', '-', $s);
$s= str_replace('(', '', $s);
$s= str_replace(')', '', $s);
$s= str_replace('+', '', $s);
$s= str_replace('*', '', $s);
$s= str_replace('!', '', $s);
$s= str_replace('$', '', $s);
$s= str_replace('&', '', $s);
$s= str_replace('=', '', $s);
$s= str_replace(']', '', $s);
$s= str_replace('[', '', $s);
return $s;
}
function limpiarConPunto($s)
{
$s = str_replace('á', 'a', $s);
$s = str_replace('Á', 'A', $s);
$s = str_replace('é', 'e', $s);
$s = str_replace('É', 'E', $s);
$s = str_replace('í', 'i', $s);
$s = str_replace('Í', 'I', $s);
$s = str_replace('ó', 'o', $s);
$s = str_replace('Ó', 'O', $s);
$s = str_replace('Ú', 'U', $s);
$s= str_replace('ú', 'u', $s);

//Quitando Caracteres Especiales
$s= str_replace('"', '', $s);
$s= str_replace(':', '', $s);
$s= str_replace(',', '', $s);
$s= str_replace(';', '', $s);
$s= str_replace(' ', '-', $s);
return $s;
}
/*function traeDatos($dato)
{
    $CI =& get_instance();
    $CI->load->model('contacto_model');
    $datos = $CI->contacto_model->getDatosContacto();
    echo $datos[$dato];
}*/
function debug($datos)
{
    echo "<pre>";
    var_dump($datos);
    echo "</pre>";
}
/*
function last_query()
{
  $CI =& get_instance();
  echo $CI->db->last_query();
}
*/

function existe_combinacion($total_combinaciones,$combinacion2)
{
  if(count($total_combinaciones)==0) return false;

  foreach($total_combinaciones as $combinacion_final)
  {
    $iguales = true;
    foreach($combinacion_final as $idproducto=>$idalmacen)
      if(!isset($combinacion2[$idproducto]) or $idalmacen!=$combinacion2[$idproducto]) $iguales = false;

    if($iguales) return true;
  }

  return false;
}

function last_query()
{
  $CI =& get_instance();
  echo $CI->db->last_query();
}

function fecha_formt($fecha)
{
    global $gmt;
    //$gmt = 6*60*60;
    $mi_fecha = strtotime($fecha);
    //$week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
    $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $month = date("n", $mi_fecha);
    $week_day_now = date("w");
    $date = date("d/", $mi_fecha).date("m/", $mi_fecha).date("Y", $mi_fecha);
    return $date;
}
function fecha_esp($fecha)
{
    global $gmt;
    //$gmt = 6*60*60;
    $mi_fecha = strtotime($fecha);
    //$week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
    $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $month = date("n", $mi_fecha);
    $week_day_now = date("w");
    $date = $months[$month].date(" d", $mi_fecha).",".date(" Y", $mi_fecha);
    return $date;
}
function fecha_esp_red($fecha)
{
    global $gmt;
    //$gmt = 6*60*60;
    $mi_fecha = strtotime($fecha);
    //$week_days = array ("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
    $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $month = date("n", $mi_fecha);
    $year = date("Y", $mi_fecha);
    $week_day_now = date("w");
    $date = date("d ", $mi_fecha).$months[$month]." ".$year;
    return $date;
}
function mes_esp($fecha)
{
    $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $month = date("n", $fecha);
    return $months[$month];
}
function mes_anio_esp($fecha)
{
    $months = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $month = date("n", $fecha);
    return $months[$month].date(" Y ", $fecha);
}

function eliminar_tildes($cadena)
{

    //Codificamos la cadena en formato utf8 en caso de que nos de errores
    //$cadena = ($cadena);

    //Ahora reemplazamos las letras
    $cadena = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $cadena
    );

    $cadena = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $cadena
    );

    $cadena = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $cadena
    );

    $cadena = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $cadena
    );

    $cadena = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $cadena
    );

    $cadena = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C'),
        $cadena
    );

    return $cadena;
}
function next_key($array,$search)
{
  $encontrada = $search; $anterior = "";
  foreach($array as $key=>$d)
  {
    if($anterior!="") return $key;
    if($key==$search) $anterior=$key;
  }
  return $encontrada;
}
function indexar_array($array)
{
    if (count($array)>0) {
        $new_array = array();
        foreach ($array as $valor) {
            $new_array[ $valor ] = $valor;
        }
        return $new_array;
    }
    return array();
}

function indexar_array_vacio($array)
{
    if (count($array)>0) {
        $new_array = array();
        foreach ($array as $valor) {
            $new_array[ $valor ] = "";
        }
        return $new_array;
    }
    return array();
}

function indexar_array_por_campo($array, $campo)
{
    if (count($array)>0) {
        $new_array = array();
        foreach ($array as $datos) {
            $new_array[ $datos[ $campo ] ] = $datos;
        }
        return $new_array;
    }
    return array();
}

function indexar_array_por_campo_simple($array, $campo1, $campo2)
{
    if (count($array)>0) {
        $new_array = array();
        foreach ($array as $datos) {
            $new_array[ $datos[ $campo1 ] ] = $datos[ $campo2 ];
        }
        return $new_array;
    }
    return array();
}

function encodeItem($input)
{
    return md5($input);
}
function newPassword(){
  $caracteres='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  $longpalabra=8;
  for($pass='', $n=strlen($caracteres)-1; strlen($pass) < $longpalabra ; ) {
    $x = rand(0,$n);
    $pass.= $caracteres[$x];
  }
  return $pass;
}
function cambiar_formato($fecha)
{
    if ($fecha!="") {
        $tmp = explode("-", $fecha);
        return $tmp[2]."-".$tmp[1]."-".$tmp[0];
    }
    return "";
}

function sumar_dias($fecha, $dias)
{
    $fecha_ini = strtotime($fecha);
    $un_dia = 24*60*60;
    $dias_sumados = 0;
    while ($dias_sumados<$dias) {
        $fecha_ini += $un_dia;
        $dias_sumados++;
    }
    return date("Y-m-d", $fecha_ini);
}

function sumar_dias_laborables($fecha, $dias)
{
    $fecha_ini = strtotime($fecha);
    $un_dia = 24*60*60;
    if ($dias<0) {
        $un_dia = -$un_dia;
        $dias = -$dias;
    }
    $dias_sumados = 0;
    while ($dias_sumados<$dias) {
        $fecha_ini += $un_dia;
        if (date("w", $fecha_ini)!=6 and date("w", $fecha_ini)!=0) {
            $dias_sumados++;
        }
    }
    return date("Y-m-d", $fecha_ini);
}

function restar_dias_laborables($fecha, $dias)
{
    $fecha_ini = strtotime($fecha);
    $un_dia = 24*60*60;
    $dias_sumados = 0;
    while ($dias_sumados<$dias) {
        $fecha_ini -= $un_dia;
        if (date("w", $fecha_ini)!=6 and date("w", $fecha_ini)!=0) {
            $dias_sumados++;
        }
    }
    return date("Y-m-d", $fecha_ini);
}

function calcular_dias($fecha1, $fecha2)
{
    $fecha_ini = strtotime($fecha1);
    $fecha_fin = strtotime($fecha2);
    $final = 1;
    $resto = abs($fecha_ini - $fecha_fin);
    //echo "Fini ".$fecha_ini." y ".$fecha_fin;
    $un_dia = 24*60*60;
    if ($fecha_ini==$fecha_fin or $resto<$un_dia) {
        return 0;
    }
    if ($fecha_ini>$fecha_fin) {
        $final = -1;
        $fecha_ini = strtotime($fecha2);
        $fecha_fin = strtotime($fecha1);
    }
    $dias_sumados = 0;
    while ($fecha_ini<$fecha_fin) {
        $fecha_ini += $un_dia;
        $dias_sumados++;
    }
    return $dias_sumados*$final;
}

function calcular_dias_formato_restraso($fecha1, $fecha2)
{
    $resto = calcular_dias($fecha1, $fecha2);

    $mensaje = "retraso de ";
    if ($resto<0) {
        $resto = $resto*-1;
        $mensaje = "dentro de ";
    }
    //echo "<br>Tengo ".$resto;
    $anios = floor($resto/365);
    if ($anios>0) {
        $resto = $resto - (365*$anios);
    }
    //echo "Tengo ".$resto." y anios ".$anios."<br><br>";
    $meses = floor($resto/30);
    if ($meses>0) {
        $resto = $resto - (30*$meses);
    }
    $semanas = floor($resto/7);
    if ($semanas>0) {
        $resto = $resto - (7*$semanas);
    }
    $dias = $resto;

    $y = " y ";
    if ($anios==1) {
        $mensaje .= $anios." año";
    } elseif ($anios>0) {
        $mensaje .= $anios." años";
    } else {
        $y = " ";
    }
    $y_mes = " y ";
    if ($meses==1) {
        $mensaje .= $y.$meses." mes";
    } elseif ($meses>0) {
        $mensaje .= $y.$meses." meses";
    } else {
        $y_mes = " ";
    }

    if ($y!=" " and $y_mes!=" ") {
        return $mensaje;
    }

    $y = " y ";
    if ($semanas==1) {
        $mensaje .= $y_mes.$semanas." semana";
    } elseif ($semanas>0) {
        $mensaje .= $y_mes.$semanas." semanas";
    } else {
        $y = " ";
    }
    if ($y!=" " and $y_mes!=" ") {
        return $mensaje;
    }

    if ($dias==1) {
        $mensaje .= $y.$dias." día";
    } elseif ($dias>0) {
        $mensaje .= $y.$dias." días";
    }
    return $mensaje;
}

function calcular_dias_formato($fecha1, $fecha2)
{
    $resto = calcular_dias($fecha1, $fecha2);
    if ($resto==0) {
        return "hoy";
    }
    $mensaje = "hace ";
    if ($resto<0) {
        $resto = $resto*-1;
        $mensaje = "dentro de ";
    }
    //echo "<br>Tengo ".$resto;
    $anios = floor($resto/365);
    if ($anios>0) {
        $resto = $resto - (365*$anios);
    }
    //echo "Tengo ".$resto." y anios ".$anios."<br><br>";
    $meses = floor($resto/30);
    if ($meses>0) {
        $resto = $resto - (30*$meses);
    }
    $semanas = floor($resto/7);
    if ($semanas>0) {
        $resto = $resto - (7*$semanas);
    }
    $dias = $resto;

    $y = " y ";
    if ($anios==1) {
        $mensaje .= $anios." año";
    } elseif ($anios>0) {
        $mensaje .= $anios." años";
    } else {
        $y = " ";
    }
    $y_mes = " y ";
    if ($meses==1) {
        $mensaje .= $y.$meses." mes";
    } elseif ($meses>0) {
        $mensaje .= $y.$meses." meses";
    } else {
        $y_mes = " ";
    }

    if ($y!=" " and $y_mes!=" ") {
        return $mensaje;
    }

    $y = " y ";
    if ($semanas==1) {
        $mensaje .= $y_mes.$semanas." semana";
    } elseif ($semanas>0) {
        $mensaje .= $y_mes.$semanas." semanas";
    } else {
        $y = " ";
    }
    if ($y!=" " and $y_mes!=" ") {
        return $mensaje;
    }

    if ($dias==1) {
        $mensaje .= $y.$dias." día";
    } elseif ($dias>0) {
        $mensaje .= $y.$dias." días";
    }
    return $mensaje;
}

function calcular_dias_laborables($fecha1, $fecha2)
{
    $fecha_ini = strtotime($fecha1);
    $fecha_fin = strtotime($fecha2);
    $final = 1;
    //echo "Fini ".$fecha_ini." y ".$fecha_fin;
    if ($fecha_ini==$fecha_fin) {
        return 0;
    }
    if ($fecha_ini>$fecha_fin) {
        $final = -1;
        $fecha_ini = strtotime($fecha2);
        $fecha_fin = strtotime($fecha1);
    }
    $un_dia = 24*60*60;
    $dias_sumados = 0;
    while ($fecha_ini<$fecha_fin) {
        $fecha_ini += $un_dia;
        if (date("w", $fecha_ini)!=6 and date("w", $fecha_ini)!=0) {
            $dias_sumados++;
        }
    }
    return $dias_sumados*$final;
}

function fecha_ultimo_dia_mes($fecha=null)
{
    $fecha = ($fecha==null ? date("Y-m-d") : $fecha);
    $fecha = new DateTime($fecha);
    $fecha->modify('last day of this month');
    return $fecha->format('Y-m-d');
}

function fecha_primer_dia_mes($fecha=null)
{
    $fecha = ($fecha==null ? date("Y-m-d") : $fecha);
    $fecha = new DateTime($fecha);
    $fecha->modify('first day of this month');
    return $fecha->format('Y-m-d');
}

function ultimo_dia_mes($fecha)
{
    $fecha = new DateTime($fecha);
    $fecha->modify('last day of this month');
    return $fecha->format('j');
}

function primer_dia_mes($fecha)
{
    $fecha = new DateTime($fecha);
    $fecha->modify('first day of this month');
    return $fecha->format('j');
}

function fechaCorta($fecha)
{
    $var = strtotime($fecha);
    return date("j", $var).' '.mesCortoES(date("n", $var))." ".date("Y", $var)." a las ".date("H:i", $var);
}

function fechaCortaTime($fecha)
{
    $var = $fecha;
    return date("j", $var).' '.mesCortoES(date("n", $var))." ".date("Y", $var)." a las ".date("H:i", $var);
}

function mesCortoES($valor)
{
    $meses=array('','Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
    return $meses[$valor];
}

function mesES($valor)
{
    $meses=array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
    return $meses[$valor];
}

function calcular_bicswift($iban)
{
    return "BIC";
}

function iban_ok($iban)
{
    $iban = strtolower(str_replace(' ', '', $iban));
    $Countries = array('al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24);
    $Chars = array('a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35);

    if (isset($Countries[substr($iban, 0, 2)]) and strlen($iban) == $Countries[substr($iban, 0, 2)]) {
        $MovedChar = substr($iban, 4).substr($iban, 0, 4);
        $MovedCharArray = str_split($MovedChar);
        $NewString = "";

        foreach ($MovedCharArray as $key => $value) {
            if (!is_numeric($MovedCharArray[$key])) {
                $MovedCharArray[$key] = $Chars[$MovedCharArray[$key]];
            }
            $NewString .= $MovedCharArray[$key];
        }

        if (bcmod($NewString, '97') == 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function rellenaIzquierda($texto, $caracter, $longitud, $cortar=true)
{
    if ($cortar) {
        $texto=substr($texto, 0, $longitud);
    }

    for ($i=strlen($texto);$i<$longitud;$i++) {
        $texto=$caracter.$texto;
    }

    return $texto;
}

function toNumberSEPA($dest)
{
    if ($dest) {
        if (ord($dest)<65) {
            return intval($dest);
        } else {
            return ord($dest) -55;
        }
    } else {
        return 0;
    }
}

function getPresentador($cif, $codPais="ES")
{
    $codigo = strtoupper($cif.$codPais."00");
    //echo $codigo."<br>";
    $codigoletras = "";
    for ($i=0;$i<strlen($codigo);$i++) {
        $codigoletras .= toNumberSEPA($codigo[$i]);
    }
    //echo $codigoletras."<br>";
    $resto = $codigoletras % 97;
    return sprintf("%02d", 98 - $resto);
}

function formatear_numero($numero, $decimales)
{
    return number_format($numero, $decimales, ".", "");
}

function formatear_numero_factura($numero, $decimales=2)
{
    return number_format($numero, $decimales, ",", ".");
}

function getDatosCuenta($iban)
{
    $respuesta = array();
    $respuesta['entidad'] = substr($iban, 4, 4);
    $respuesta['oficina'] = substr($iban, 8, 4);
    return $respuesta;
}

function converto_to_iso20022($string)
{
    $caracteres_aceptados = "ABCDEFGHIJKLMNOPQRSTUVWXYZ abcdefghijklmnopqrstuvwxyz0123456789/-?:().,‘+";
    $no_aceptados = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
    $aceptados = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
    //$cadena = utf8_decode($cadena);
    $string = strtr($string, utf8_decode($no_aceptados), $aceptados);


    $mi_cadena = str_split($string, 1);
    foreach ($mi_cadena as $index=>$valor) {
        if ($valor!="") {
            if (strpos($caracteres_aceptados, $valor)===false) {
                $string =  str_replace($valor, "", $string);
            }
        }
    }
    return $string;
}

function proxima_factura($diaFactura, $iniciar, $meses)
{
    //echo "<br>Tengo ".$iniciar."<br>";
    $mis_meses = indexar_array_vacio($meses);
    //Calculamos la proxima factura.
    $fecha_inicial = date("Y-m-".$diaFactura);
    $proxima_factura= "";
    $i = 1;
    $hoy = date("Y-m-d");

    //Si el dia que se inicia es mayor que el dia factura, hay que ir al mes siguiente.
    if (date("j", strtotime($iniciar))>$diaFactura) {
        $iniciar = date("Y-m-".$diaFactura, strtotime($iniciar."+1 month"));
    } else {
        $iniciar = date("Y-m-".$diaFactura, strtotime($iniciar));
    }

    //Si hoy ya es mayor que la fecha inicial, pasamos al mes siguiente.
    if (strtotime($hoy)>strtotime($fecha_inicial)) {
        $fecha_inicial = date("Y-m-d", strtotime($fecha_inicial."+1 month"));
    }

    //echo "Llego con ".$iniciar." y ".$fecha_inicial;
    //Si la fecha de iniciar es mayor que la fecha inicial, nos quedamos con la de iniciar.
    if (strtotime($iniciar)>strtotime($fecha_inicial)) {
        $fecha_inicial = $iniciar;
    }


    while ($proxima_factura=="") {
        $mes_actual = date("n", strtotime($fecha_inicial));
        if (isset($mis_meses[ $mes_actual ])) {
            $proxima_factura =  $fecha_inicial;
        } else {
            $fecha_inicial = date("Y-m-d", strtotime($fecha_inicial."+1 month"));
        }
    }
    return cambiar_formato($proxima_factura);
}

function create_zip($listado_facturas, $pdf, $excel, $destination)
{


    //if we have good files...
    if (count($listado_facturas)>0) {
        $destination = "attachments/fventas/mis_facturas.zip";
        //create the archive
        $zip = new ZipArchive();
        $overwrite = file_exists($destination);
        if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            echo -1;
        }
        //add emitiras
        if (count($listado_facturas)) {
            foreach ($listado_facturas as $idfactura=>$file) {
                $file_destino ="attachments/fventas/".$file;
                $zip->addFile($file_destino, $file);
            }
        }
        if ($pdf!="") {
            $zip->addFile("attachments/fventas/".$pdf, $pdf);
        }
        if ($excel!="") {
            $zip->addFile("attachments/fventas/".$excel, $excel);
        }
        //debug
        //echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

        //close the zip -- done!
        $zip->close();

        //check to make sure the file exists
        return file_exists($destination);
    } else {
        echo -2;
    }
}

function create_modelo347_zip($listado_modelo347, $destination)
{


    //if we have good files...
    if (count($listado_modelo347)>0) {


        $zip = new ZipArchive();
        $overwrite = file_exists($destination);
        if ($zip->open($destination, $overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
            echo -1;
        }
        //add emitiras
        if (count($listado_modelo347)) {
            foreach ($listado_modelo347 as $idcliente=>$file) {
                $file_destino ="attachments/fmodelo347/".$file;

                $zip->addFile($file_destino, $file);
            }
        }

        //close the zip -- done!
        $zip->close();

        //check to make sure the file exists
        return file_exists($destination);
    } else {
        echo -2;
    }
}

function getDiasHabiles($fechainicio, $fechafin = array())
{
    // Convirtiendo en timestamp las fechas
    $fechainicio = strtotime($fechainicio);
    $fechafin = strtotime($fechafin);

    // Incremento en 1 dia
    $diainc = 24*60*60;

    // Arreglo de dias habiles, inicianlizacion
    $diashabiles = array();

    // Se recorre desde la fecha de inicio a la fecha fin, incrementando en 1 dia
    for ($midia = $fechainicio; $midia <= $fechafin; $midia += $diainc) {
        // Si el dia indicado, no es sabado o domingo es habil
        if (!in_array(date('N', $midia), array(6,7))) { // DOC: http://www.php.net/manual/es/function.date.php
            array_push($diashabiles, date('Y-m-d', $midia));
        }
    }

    return $diashabiles;
}
function pintarCampoForm($tipo,$columnas,$label,$valor,$atributos=array(),$clases=array(),$extra_select=""){

  //debug($extra_select);

  $CI =& get_instance();

  if (!in_array($tipo, array('hidden', 'checkbox', 'radio'))){
    $bootstrap  = $CI->config->item('bootstrap');
    $contenedor = "<div class='form-group col-md-".$columnas." ".$bootstrap['col-'.$columnas]."'>";
    $campo      = (!empty($label))? "<label><strong>".$label."</strong></label>": "";
  }
  else{
    $contenedor = "";
    $campo      = "";
  }

  if (!empty($atributos['name'])){

    if($tipo=="text" or $tipo=="password" or $tipo=="email"){

      // Estos son los atributos y las clases por defecto que siempre tendrán estos tres tipos de campos
      $atributos_defecto = array('placeholder'=>$label);
      $class_defecto     = array('form-control', 'input-border-value');

      //Se mezclan los arrays pero prevalece $atributos, si existe en $atributos_defecto lo machaca
      $atributos_fin     = array_merge($atributos_defecto, $atributos);
      $class_fin         = array_merge($class_defecto, $clases);

      $campo .= "<input type='" . $tipo . "' value='" . $valor . "' ";
      $campo .= listar_array($atributos_fin, "key");
      $campo .= "class='" . listar_array($class_fin, "") ."'>";

      $contenedor .= $campo."</div>";

    }

    if($tipo=="hidden"){

      $campo .= "<input type='" . $tipo . "' value='" . $valor . "' " . listar_array($atributos, "key") . ">";

      $contenedor .= $campo;

    }

    if($tipo=="checkbox"){
      // Estos son los atributos y las clases por defecto que siempre tendrán estos tres tipos de campos
      $atributos_defecto = array();
      $class_defecto     = array('btn_chk');

      //Se mezclan los arrays pero prevalece $atributos, si existe en $atributos_defecto lo machaca
      $atributos_fin     = array_merge($atributos_defecto, $atributos);
      $class_fin         = array_merge($class_defecto, $clases);

      $campo .= "<input type='" . $tipo . "' value='" . $valor . "' ";
      $campo .= listar_array($atributos_fin, "key");
      $campo .= "class='" . listar_array($class_fin, "") ."'>";


      $contenedor .= $campo;

    }

    if($tipo=="radio"){
      // Estos son los atributos y las clases por defecto que siempre tendrán estos tres tipos de campos
      $atributos_defecto = array();
      $class_defecto     = array('btn_chk');

      //Se mezclan los arrays pero prevalece $atributos, si existe en $atributos_defecto lo machaca
      $atributos_fin     = array_merge($atributos_defecto, $atributos);
      $class_fin         = array_merge($class_defecto, $clases);

      $campo .= "<input type='" . $tipo . "' value='" . $valor . "' ";
      $campo .= listar_array($atributos_fin, "key");
      $campo .= "class='" . listar_array($class_fin, "") ."'>";


      $contenedor .= $campo;

    }


    if($tipo=="textarea"){

      // Estos son los atributos y las clases por defecto que siempre tendrán estos tres tipos de campos
      $atributos_defecto = array('placeholder'=>$label);
      $class_defecto     = array('form-control', 'input-border-value');

      //Se mezclan los arrays pero prevalece $atributos, si existe en $atributos_defecto lo machaca
      $atributos_fin     = array_merge($atributos_defecto, $atributos);
      $class_fin         = array_merge($class_defecto, $clases);

      $campo .= "<textarea ";
      $campo .= listar_array($atributos_fin, "key");
      $campo .= "class='" . listar_array($class_fin, "") ."'>";
      $campo .= $valor . "</textarea>";

      $contenedor .= $campo."</div>";

    }

    if($tipo=="select" or $tipo=="select multiple"){
      // Estos son los atributos y las clases por defecto que siempre tendrá el select
      $atributos_defecto = array('data-style'=>'btn-inverse btn-sm',
                                 'data-width'=>'100%',
                                 'tabindex'  =>'-1',
                                 'title'     =>'-- Seleccione --');
      $class_defecto     = array('selectpicker');

      //Se mezclan los arrays pero prevalece $atributos, si existe en $atributos_defecto lo machaca
      $atributos_fin     = array_merge($atributos_defecto, $atributos);
      $class_fin         = array_merge($class_defecto, $clases);

      $campo .= "<". $tipo . "  ";
      $campo .= listar_array($atributos_fin, "key");
      $campo .= "class='" . listar_array($class_fin, "") ."'>";

      if (is_array($extra_select)){
        $campo .= lista_option_select($tipo, $extra_select, $valor);
        $campo .= "</select></div>";
      }

      $contenedor .= $campo;

    }

  }
  else{
    $campo .= "En los atributos debes introducir al menos el 'name' del campo</div>";
    $contenedor .= $campo;
  }

  return $contenedor;

}

function finCampoSelect(){

  return "</select></div>";
}

function lista_option_select($tipo, $extra_select, $valor){
/*
  $valor es un array cuando $tipo es 'select multiple'
  $valor es una variable cuando $tipo es 'select'
  array(
    'option-value'   => "provincia_id",           // Nombre del campo de la tabla que va en el value. Normalmente la llave primaria de la tabla
    'option-show'    => "provincia",              // Nombre del campo de la tabla que se va a mostrar.
    'option-data'    => $provincias,              // Array con los datos a mostrar, al menos con los dos campos anteriores
    'option-default' => 'Seleccione provincias',  // Texto que se pondrá por defecto al principio - Campo opcional
  )
*/

  $option_select = '' . PHP_EOL;

  //Aquí se pone la primera opción por defecto.
  if(isset($extra_select['option-default']) && $extra_select['option-default'] != ''){
    $option_select .= "<option value=''>".$extra_select['option-default']."</option>" . PHP_EOL;
  }

  foreach($extra_select['option-data'] as $key => $data){
    if ($tipo == "select")
      $option_select .= "<option ". (($data[$extra_select['option-value']] == $valor)?"selected ":" ") . "value='".$data[$extra_select['option-value']]."'>".ucwords($data[$extra_select['option-show']])."</option>" . PHP_EOL;
    else
      $option_select .= "<option ". (in_array($data[$extra_select['option-value']],$valor)?"selected ":" ") . "value='".$data[$extra_select['option-value']]."'>".ucwords($data[$extra_select['option-show']])."</option>" . PHP_EOL;

  }

  return $option_select;

}
function listar_array($a, $tipo){
  $lista = '';

  foreach ($a as $key => $valor) {
    if ($tipo == "key")
      $lista.=  $key . "='" . $valor . "' ";
    else
      $lista.= $valor . " ";
  }

  return trim($lista);

}

function delBackslashIni($rutaFile){
    $primerCararcter = substr($rutaFile,0,1);
    if($primerCararcter==='/') return substr($rutaFile, 1);
    return $rutaFile;
    
}

function reposicionarArray($array, $posActual, $posNueva){
    $p1 = array_splice($array, $posActual, 1);
    $p2 = array_splice($array, 0, $posNueva);
    $array = array_merge($p2, $p1, $array);
    return $array;
}

function cobroEnvioCkeckoput($vendedor, $muni, $tipoenvio){
    if(isset($_SESSION['dataCobro'])){
        $encontro=0;
        foreach($_SESSION['dataCobro'] as $key => $data):
            if($data['v']===$vendedor && $data['t']===$tipoenvio){  
                $encontro++;

                $estaCiudad=0;
                foreach($muni as $m){
                    if(in_array($m, $data['m'])) $estaCiudad++;
                }

                $_SESSION['dataCobro'][$key]['m'] = array_merge($data['m'], $muni);
                $_SESSION['dataCobro'][$key]['m'] = array_unique($_SESSION['dataCobro'][$key]['m']);

                return ($estaCiudad>0)?false:true;               
            }
        endforeach;

        if($encontro===0){
            $_SESSION['dataCobro'][]=[
                'v' => $vendedor,
                't' => $tipoenvio,
                'm' => $muni
            ];
            return true;
        }
    }else{
        $_SESSION['dataCobro']=[];
        $_SESSION['dataCobro'][]=[
            'v' => $vendedor,
            't' => $tipoenvio,
            'm' => $muni
        ];
        return true;
    }    
}

function pixelMetaConversion($evento, $datosExt){
    $PIXEL_ID = '558712582247474';
    $ACCESS_TOKEN = 'EAAJNZCDpjuLIBAKVDdDkMUKfft84CQXJcodL5QZADg8eptDtn3WoWtXkjACA4IOUpBABZBDo76ELOtX1BXl829Tbbr5RAkiNkqZAqtIZCxUfVghNnUJq8xZBsTO7uDkW1A4nETtAIRhndGiZBzYIAALNuhgbQj4eShBdOnOEaPG4HKaCbQcVbKP4TUCobGHFLMZD';
    $EMAIL_PIXEL = 'roldanfelipezuluaga@gmail.com';
    $TEST_CODE = 'TEST53886';

    if($evento==='addToCart'){
        $CI =& get_instance();
        $CI->load->model("productos_model");
        $datoProducto = $CI->productos_model->single($datosExt['productos_id']);
        $urlSingle = base_url('tienda/single/'.$datosExt['productos_id'].'/'.limpiarUri($datoProducto['productos_titulo']));
        $precio = 

        $addToCartEvent = [
            'event_name' => 'AddToCart',
            'event_time' => time(),
            'user_data' => [
                'client_ip_address' => $_SERVER['REMOTE_ADDR'],
                'client_user_agent' => $_SERVER['HTTP_USER_AGENT'],
                'em' => hash('sha256', $EMAIL_PIXEL)
            ],
            'custom_data' => [
                'currency' => 'COP',
                'value' => $datoProducto['productos_precio'].'.00', 
                'external_event_id' => uniqid(), 
                'content_type' => 'product'
            ],
            'event_source_url' => $urlSingle,
            'event_id' => uniqid(),
            'action_source' => 'website',
        ];
        $event=$addToCartEvent;
    }

    $data = [
        'data' => [$event],
        'test_event_code' => $TEST_CODE,
    ];

    $url = 'https://graph.facebook.com/v16.0/' . $PIXEL_ID . '/events?access_token=' . $ACCESS_TOKEN;
    $payload = json_encode($data);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

    $response = curl_exec($ch);

    if ($response === false) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $response_data = json_decode($response, true);
        if (isset($response_data['error'])) {
            echo 'Error: ' . $response_data['error']['message'];
        } else {
            //echo 'Success: ' . $response;
        }
    }

        curl_close($ch);

}


function getCalculoRedimensionar($src, $ancho_forzado){
    if (file_exists($src)) {
        list($width, $height, $type, $attr)= getimagesize($src);
        if ($ancho_forzado > $width) {
            $max_width = $width;
        } else {
            $max_width = $ancho_forzado;
        }
        $proporcion = $width / $max_width;
        if ($proporcion == 0) {
            return -1;
        }
        $height_dyn = $height / $proporcion;
    } else {
        return -1;
    }
   return array($max_width, $height_dyn);
}


function getRedimencionImage($ruta, $newWidth, $newHeight){
    $parts = explode('/', $ruta);
    $nameImage = end($parts);
    $extension = pathinfo( $ruta, PATHINFO_EXTENSION );

    //print $extension;
    if(in_array($extension, ['webp', 'WEBP'])){
        if (exif_imagetype($ruta) === IMAGETYPE_WEBP) {
            $img_original = imagecreatefromwebp($ruta);
        }else{
            $img_original = imagecreatefromjpeg($ruta);
        }
        //$img_original = imagecreatefromwebp($ruta);
    }else if(in_array($extension, ['jpg', 'jpeg', 'JPG', 'JPEG', 'jfif', 'JFIF'])){
        $img_original = imagecreatefromjpeg($ruta);
    }else if(in_array($extension, ['png', 'PNG'])){
        $img_original = imagecreatefrompng($ruta);
    }else if(in_array($extension, ['gif', 'GIF'])){
        $img_original = imagecreatefromgif($ruta);
    }
    
    list($ancho,$alto)=getimagesize($ruta);

    $tmp=imagecreatetruecolor($newWidth, $newHeight);       
    imagecopyresampled($tmp, $img_original, 0,0,0,0, $newWidth, $newHeight, $ancho, $alto);
    //imagedestroy($img_original);
    $calidad=95;
    imagejpeg($tmp, $ruta, $calidad);
    //imagejpeg($tmp, "assets/uploads/01_miniatura/".$nameImage, $calidad);
    return true;
}


function getMiniaturaProduct($idProd){
    $CI =& get_instance();
    $carpetaMiniatura='assets/uploads/01_miniatura/';

    $anchoForzado=260;

    $dataMedio = $CI->db->where('medios_id', $idProd)->get('medios')->result_array();
    if(count($dataMedio)>0){
        $dataMedio = $dataMedio[0];

        //Obtenemos Carpeta Original de la imagen
        $rutaOriginal = delBackslashIni($dataMedio['medios_url']);
        $rutaMiniatura = delBackslashIni($dataMedio['medios_enlace_miniatura']);

        $parteRutas = explode('/', $rutaOriginal);
        $nombreArchivo = end($parteRutas);
        $rutaImagenOriginal = '';
        foreach($parteRutas as $carpeta):
            if($carpeta !== $nombreArchivo) $rutaImagenOriginal.=$carpeta."/";
        endforeach;

        //Caso 1: si ruta original y ruta miniatura es la misma
        if($dataMedio['medios_url'] === $dataMedio['medios_enlace_miniatura'] || $dataMedio['medios_enlace_miniatura']===''){
            if(file_exists($rutaOriginal)){
                $rutaDestino = $rutaImagenOriginal.'miniatura_'.$nombreArchivo;
                
                if (copy($rutaOriginal, $rutaDestino)) {
                    list($ancho, $alto) = getCalculoRedimensionar($rutaDestino, $anchoForzado);
                    getRedimencionImage($rutaDestino, $ancho, $alto);
                    $CI->db->where('medios_id', $idProd)->set(['medios_enlace_miniatura'=>$rutaDestino])->update('medios');
                }  
            }     
        }

        //Caso 2: si imagen miniatura tiene mayor ancho al sugerido
        if($rutaMiniatura!=='' && file_exists($rutaMiniatura)){
            list($width, $height, $type, $attr) = getimagesize($rutaMiniatura);
            if(intval($width) > intval($anchoForzado)){
                list($ancho, $alto) = getCalculoRedimensionar($rutaMiniatura, $anchoForzado);
                getRedimencionImage($rutaMiniatura, $ancho, $alto);
                //$CI->db->where('medios_id', $idProd)->set(['medios_enlace_miniatura'=>$rutaDestino])->update('medios');
            }            
        }

        //Caso 3: Si no existe miniatura y existe Original
        if(!file_exists($rutaMiniatura)){
            $rutaDestino = $rutaImagenOriginal.'miniatura_'.$nombreArchivo;
            if(file_exists($rutaOriginal)){
                if (copy($rutaOriginal, $rutaDestino)) {
                    list($ancho, $alto) = getCalculoRedimensionar($rutaDestino, $anchoForzado);
                    getRedimencionImage($rutaDestino, $ancho, $alto);
                    $CI->db->where('medios_id', $idProd)->set(['medios_enlace_miniatura'=>$rutaDestino])->update('medios');
                }
            }
        }
    }
    
    
}