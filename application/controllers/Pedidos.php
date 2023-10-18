<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once(APPPATH.'core/MY_LoggedController.php');
ini_set('max_execution_time', 0); 
ini_set('memory_limit','4092M');
class Pedidos extends MY_LoggedController
{
    public function __construct()
    {
        parent::__construct('pedidos');
        $this->load->helper(array('commun'));
        $this->load->model("categorias_model");
        $this->load->model("productos_model");
        $this->load->model("pedidos_model");
        $this->load->model("mailing_model");
        $this->load->model("balance_model");
        $this->load->model("medios_model");
        $this->load->library('email');
    }

    public function index()
    {

      if ($_SESSION['tipo_accesos']==8 || $_SESSION['tipo_accesos']==0 || $_SESSION['tipo_accesos']==1) {
        
        $datos = array();
        $datos['view'] = "pedidos/index_new";
        $datos['css_data'] = array();
        $datos['js_data'] = array(
            'assets/js/pages/pedidos/index.js?'.rand(),
        );

        $page = 1;
        $limit = 30;
        $limite = array();
        if (!isset($_GET['page'])) {
          $limite=array($limit,$page-1);
        }else{
          $page = $_GET['page']-1;
          $paginado = $limit*$page;
          $limite=array($limit,$paginado);
        }
        $filtros = array();
        $datos['pedidos_new'] = (intval($_SESSION['tipo_accesos'])===8) ? $this->pedidos_model->getAllforVendorNew($filtros,$page,$limite) : $this->pedidos_model->getAllNew($filtros,$page,$limite); 
        /*
        if ($_SESSION['tipo_accesos']==8) {
          $datos['pedidos_new'] = $this->pedidos_model->getAllforVendorNew($filtros,$page,$limite);
          //$datos['pedidos'] = $this->pedidos_model->getAllforVendor($filtros,$page,$limite);
        }else{
          $datos['pedidos_new'] = $this->pedidos_model->getAllNew($filtros,$page,$limite);
          //$datos['pedidos'] = $this->pedidos_model->getAll($filtros,$page,$limite);
        }
        */
        $this->load->view('normal_view', $datos);

      }

    }

    public function exportarPedidosNew(){

      $cellKey = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
        'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
      );

      $page = 1;
      $limite=array(1000000,0);
      $filtros = array();
      if (
        isset($_POST['pedidos_fecha_inicio']) 
        && isset($_POST['pedidos_fecha_final'])
        && $_POST['pedidos_fecha_inicio']!=""
        && $_POST['pedidos_fecha_final']!=""
      ) {
        $filtros["where_arr"] = array(
            "fini" => $_POST['pedidos_fecha_inicio'], 
            "ffin" => $_POST['pedidos_fecha_final']
        );
      }
      
      $pedidos = $this->pedidos_model->getAllNew($filtros,$page,$limite);

      $datos = array();

      // recorremos el pedido
      foreach ($pedidos['pedidos'] as $key => $value) {
        array_push($datos,array(
          "pedidos_id" => $value['pedidos_id'],
          "items" => array()
        ));
        // recorremos los productos del pedido
        foreach ($pedidos['pedidos_productos']->result_array() as $key0 => $value0) {
          //$agg = 0;
          // recorremos los productos para comparar los datos y sacar la información necesaria
          foreach ($pedidos['productos']->result_array() as $key1 => $value1) {
            
            if ($value0['pedidos_detalle_pedidos_id']==$value['pedidos_id'] 
            && $value0['pedidos_detalle_producto']==$value1['productos_id']) {

              $precio_solo = floatval($value0['pedidos_detalle_producto_precio']);
              $cantidad = floatval($value0['pedidos_detalle_producto_cantidad']);
              $precio_total = $precio_solo*$cantidad;
              $precio_envio = 0;
              $metodo_pago = "";
              $estatus = "";
              
              if ($value['pedidos_metodo_pago']==0) {
                $metodo_pago = "Transferencia Bancaria";
              }elseif ($value['pedidos_metodo_pago']==1) {
                  $metodo_pago = "Payzen";
              }elseif ($value['pedidos_metodo_pago']==2) {
                  $metodo_pago = "PayU Latam";
              }else{
                  $metodo_pago = $value['pedidos_metodo_pago'];
              }

              if ($cantidad > 0) {
                if ($value0['pedidos_detalle_producto_envio_local']==1) {
                  $ubicado = 0;
                  $ubicaciones = explode("/",$value0['pedidos_detalle_productos_ubicaciones_envio']);
                  
                  for ($i=0; $i < count($ubicaciones); $i++) {
                    $ubi = explode(",",$ubicaciones[$i]);
                    if (isset($ubi[1]) && $ubi[0]==$value['pedidos_departamento'] && $ubi[1]==$value['pedidos_municipio']) {
                      $ubicado=1;                          
                      $precio_envio=$value0['pedidos_detalle_productos_valor_envio_local'];
                    }
                  }

                  if ($ubicado==0) {
                    $precio_envio=$value0['pedidos_detalle_productos_valor_envio_nacional'];
                  }
                }else{
                  $precio_envio=$value0['pedidos_detalle_productos_valor_envio_nacional'];
                }
              }
              
              if($value['pedidos_estatus']==6){ 
                $estatus = "Rechazado"; 
              }elseif ($value['pedidos_estatus']==5) {
                  $estatus = "Rechazado"; 
              }elseif ($value['pedidos_estatus']==4) {
                  $estatus = "En Preparación"; 
              }elseif ($value['pedidos_estatus']==1) {
                  $estatus = "Esperando confirmación de pago"; 
              }elseif ($value['pedidos_estatus']=="1") {
                  $estatus = "Esperando confirmación de pago"; 
              }else {
                  switch ($value['pedidos_estatus']) {
                      case 'Enviado':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 'En preparación':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 'Esperando confirmación de pago':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 'Esperando confirmación de Pago':
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 1:
                          $estatus = $value['pedidos_estatus'];
                          break;
                      case 1:
                          $estatus = "Esperando confirmación de Pago";
                          break;
                      case "1":
                          $estatus = "Esperando confirmación de Pago";
                          break;
                      case 'Cancelado':
                              $estatus = $value['pedidos_estatus'];
                          break;
                      default:
                          $estatus = $value['pedidos_estatus'];
                          break;
                  }
              }

              for ($i=0; $i < count($datos); $i++) {
                if ($datos[$i]['pedidos_id']==$value['pedidos_id']) {
                  $agg = 0;
                  for ($i2=0; $i2 < count($datos[$i]['items']); $i2++) { 
                    // comparamos para ver si existe el vendedor
                    if (isset($datos[$i]['items'][$i2]['vendedor']) 
                    && $datos[$i]['items'][$i2]['vendedor']==$value1['productos_vendedor'] ) {
                      $agg = 1;
                      array_push($datos[$i]['items'][$i2]['productos'],array(
                        "fecha_pedido" => $value['pedidos_fecha_creacion'],
                        "nro_pedido" => $value['pedidos_id'],
                        "nombre_cliente" => $value['pedidos_nombre'],
                        "direccion_pedido" => $value['pedidos_direccion'],
                        "departamento_pedido" => $value['departamento'],
                        "municipio_pedido" => $value['municipio'],
                        "articulo" => $value1['productos_titulo'],
                        "cantidad" => $cantidad,
                        "precio_solo" => $precio_solo,
                        "precio_total" => $precio_total,
                        "precio_envio" => $precio_envio,
                        "vendedor" => $value1['productos_vendedor'],
                        "vendedor_nombre" => $value1['name'],
                        "estatus" => $estatus,
                        "metodo_pago" => $metodo_pago,
                      ));
                      array_push($datos[$i]['items'][$i2]['envios'],$precio_envio);
                    }  
                  }
                  // Si no éxiste el vendedor, lo agregamos
                  if ($agg==0) {

                    array_push($datos[$i]['items'], array(
                      "vendedor" => $value1['productos_vendedor'],
                      "productos" => array(
                        array(
                          "fecha_pedido" => $value['pedidos_fecha_creacion'],
                          "nro_pedido" => $value['pedidos_id'],
                          "nombre_cliente" => $value['pedidos_nombre'],
                          "direccion_pedido" => $value['pedidos_direccion'],
                          "departamento_pedido" => $value['departamento'],
                          "municipio_pedido" => $value['municipio'],
                          "articulo" => $value1['productos_titulo'],
                          "cantidad" => $cantidad,
                          "precio_solo" => $precio_solo,
                          "precio_total" => $precio_total,
                          "precio_envio" => $precio_envio,
                          "vendedor" => $value1['productos_vendedor'],
                          "vendedor_nombre" => $value1['name'],
                          "estatus" => $estatus,
                          "metodo_pago" => $metodo_pago,
                        )
                      ),
                      "envios" => array($precio_envio)
                    ));
                  }
                  
                }
                
              }

            }

          }
        }

      }

      $conteo = 1;
      $conteo2 = 0;
      

      $this->load->library('excel');
      //Asumiendo que ya hayamos solicitado la libreria iniciamos la primera hoja
      $this->excel->setActiveSheetIndex(0);

      //Le colocamos el nombre a la primera hoja o pestaña
      $this->excel->getActiveSheet()->setTitle('Pedidos');

      //Ingresamo el X's texto en la celda A1

      $this->excel->getActiveSheet()->setCellValue('A1', "Fecha");
      $this->excel->getActiveSheet()->setCellValue('B1', "No. Pedido");
      $this->excel->getActiveSheet()->setCellValue('C1', "Nombre Cliente");
      $this->excel->getActiveSheet()->setCellValue('D1', "Dirección");
      $this->excel->getActiveSheet()->setCellValue('E1', "Departamento");
      $this->excel->getActiveSheet()->setCellValue('F1', "Municipio");
      $this->excel->getActiveSheet()->setCellValue('G1', "Artículo");
      $this->excel->getActiveSheet()->setCellValue('H1', "Cantidad");
      $this->excel->getActiveSheet()->setCellValue('I1', "Valor artículo");
      $this->excel->getActiveSheet()->setCellValue('J1', "Valor Envío");
      $this->excel->getActiveSheet()->setCellValue('K1', "Valor Total");
      $this->excel->getActiveSheet()->setCellValue('L1', "Vendedor");
      $this->excel->getActiveSheet()->setCellValue('M1', "Estado del Pedido");
      $this->excel->getActiveSheet()->setCellValue('N1', "Método de Pago");

      $contTotal = 1;
      foreach ($datos as $key => $value) {
        foreach ($value['items'] as $key2 => $value2) {
          $cont = 0;
          for ($i=0; $i < count($value2['productos']); $i++) {
            $cont++;
            $contTotal++;
            $this->excel->getActiveSheet()->setCellValue('A'.$contTotal, $value2['productos'][$i]['fecha_pedido']);
            $this->excel->getActiveSheet()->setCellValue('B'.$contTotal, $value2['productos'][$i]['nro_pedido']);
            $this->excel->getActiveSheet()->setCellValue('C'.$contTotal, $value2['productos'][$i]['nombre_cliente']);
            $this->excel->getActiveSheet()->setCellValue('D'.$contTotal, $value2['productos'][$i]['direccion_pedido']);
            $this->excel->getActiveSheet()->setCellValue('E'.$contTotal, $value2['productos'][$i]['departamento_pedido']);
            $this->excel->getActiveSheet()->setCellValue('F'.$contTotal, $value2['productos'][$i]['municipio_pedido']);
            $this->excel->getActiveSheet()->setCellValue('G'.$contTotal, $value2['productos'][$i]['articulo']);
            $this->excel->getActiveSheet()->setCellValue('H'.$contTotal, strval($value2['productos'][$i]['cantidad']));
            $this->excel->getActiveSheet()->setCellValue('I'.$contTotal, strval($value2['productos'][$i]['precio_solo']));
            
            if ($cont==count($value2['productos'])) {
              $this->excel->getActiveSheet()->setCellValue('J'.$contTotal, strval(max($value2['envios'])));
              $this->excel->getActiveSheet()->setCellValue('K'.$contTotal, strval($value2['productos'][$i]['precio_total']+floatval(max($value2['envios']))));
            }else{
              $this->excel->getActiveSheet()->setCellValue('J'.$contTotal, "");
              $this->excel->getActiveSheet()->setCellValue('K'.$contTotal, strval($value2['productos'][$i]['precio_total']));
            }
            
            $this->excel->getActiveSheet()->setCellValue('L'.$contTotal, $value2['productos'][$i]['vendedor_nombre']);
            $this->excel->getActiveSheet()->setCellValue('M'.$contTotal, $value2['productos'][$i]['estatus']);
            $this->excel->getActiveSheet()->setCellValue('N'.$contTotal, $value2['productos'][$i]['metodo_pago']);

          }

        }
      }
      $sheet = $this->excel->getActiveSheet();
      $sheet->getColumnDimension('A')->setWidth(20);
      $sheet->getColumnDimension('B')->setWidth(15);
      $sheet->getColumnDimension('C')->setWidth(40);
      $sheet->getColumnDimension('D')->setWidth(40);
      $sheet->getColumnDimension('E')->setWidth(20);
      $sheet->getColumnDimension('G')->setWidth(40);
      $sheet->getColumnDimension('H')->setWidth(20);
      $sheet->getColumnDimension('I')->setWidth(20);
      $sheet->getColumnDimension('J')->setWidth(20);
      $sheet->getColumnDimension('K')->setWidth(30);
      $sheet->getColumnDimension('L')->setWidth(30);
      $sheet->getColumnDimension('M')->setWidth(20);

      //Aca le asignamos el nombre al archivo
      $filename='pedidos_listado_'.date('Y-m-d_h-i').'.xls';

      //Seteamos el mime
      header('Content-Type: application/vnd.ms-excel');

      //Le enviamos al navegador el nombre del archivo para su respectiva descarga
      header('Content-Disposition: attachment;filename="'.$filename.'"');

      //Le indicamos que no deje en cache nada
      header('Cache-Control: max-age=0');
                  
      //Se genera la mágia, y se construye TODO
      $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  

      //forzamos la entrega del archivo a nuestro navegador (Descarga pes...)
      $objWriter->save('php://output');
    }

    public function exportarPedidosMejorado(){
      /**
       * Exportar Estado Pedidos
       *
       * Exportar a archivo excel, los estados de los pedidos segun el rango de fechas seleccionado
       * en caso de no tener seleccionado fechas... se tomara el mes actual como rango
       *
       * @access public
       * @param string $pedidos_fecha_inicio fecha inicial formato AAAA-MM-DD
       * @param string $pedidos_fecha_final fecha fonal formato AAAA-MM-DD
       * @return file exporta archivo excel
       */

      if(!isset($_POST['pedidos_fecha_inicio'])  && !isset($_POST['pedidos_fecha_final']) ){
        $_POST['pedidos_fecha_inicio'] = date('Y-m-01');
        $ultimoDiaMes = date("Y-m-t", strtotime($_POST['pedidos_fecha_inicio']));
        $mod_date = strtotime($ultimoDiaMes."+ 1 days");
        $_POST['pedidos_fecha_final'] = date("Y-m-d",$mod_date);
      }

      $sql="SELECT ped.pedidos_fecha_creacion 'pedidos_fecha', ped.pedidos_id, CONCAT(usu.name, ' ', usu.lastname) AS 'pedidos_usuario', ped.pedidos_nombre 'pedidos_a_nombre', ped.pedidos_direccion,
                   CASE ped.pedidos_metodo_pago
                          WHEN 0 THEN 'Transferencia Bancaria'
                          WHEN 1 THEN 'Payzen'
                          WHEN 2 THEN 'PayU Latam'
                          WHEN 3 THEN 'Mercado Pago'
                          ELSE CONCAT('Desconocido (', ped.pedidos_metodo_pago, ')')
                   END AS pedidos_metodo_pago,
                   CASE ped.pedidos_estatus
                          WHEN '1' THEN 'Esperando confirmación de pago (1)'
                          WHEN '4' THEN 'En Preparación (4)'
                          WHEN '5' THEN 'Rechazado (5)'
                          WHEN '6' THEN 'Rechazado (6)'
                          ELSE ped.pedidos_estatus
                   END AS pedidos_estado,
                   dpto.id_departamento, dpto.departamento, ciu.id_municipio, ciu.municipio,
                   det.pedidos_detalle_id 'detalle_id', det.pedidos_detalle_producto_cantidad 'detalle_cantidad', det.pedidos_detalle_producto_precio 'detalle_precio', 
                   (det.pedidos_detalle_producto_precio * det.pedidos_detalle_producto_cantidad) 'detalle_valor_total',
                   det.pedidos_detalle_productos_valor_envio_local 'detalle_valor_envio_local', det.pedidos_detalle_productos_valor_envio_nacional 'detalle_valor_envio_nal', 
                   det.pedidos_detalle_productos_ubicaciones_envio 'detalle_ubicaciones',
                   prod.productos_titulo producto_nombre,
                   vend.name 'vendedorNombre', vend.lastname 'vendedorApellido', CONCAT(vend.name, ' ', vend.lastname) 'vendedor', vend.usuarios_comision
            FROM alma_pedidos AS ped
            INNER JOIN alma_pedidos_detalle det ON det.pedidos_detalle_pedidos_id = ped.pedidos_id
            LEFT JOIN usuarios usu ON usu.usuarios_id = ped.pedidos_usuarios_id
            INNER JOIN departamentos dpto ON dpto.id_departamento = ped.pedidos_departamento
            INNER JOIN municipios ciu ON ciu.id_municipio = ped.pedidos_municipio
            INNER JOIN productos prod ON prod.productos_id = det.pedidos_detalle_producto
            INNER JOIN usuarios vend ON vend.usuarios_id = det.pedidos_detalle_vendedor
            WHERE ped.pedidos_fecha_creacion BETWEEN '".$_POST['pedidos_fecha_inicio']."' AND '".$_POST['pedidos_fecha_final']."'
            ORDER BY ped.pedidos_id DESC";
      $query = $this->db->query($sql)->result_array();
      $titulos=['FECHA', 'No PEDIDO', 'ESTADO PEDIDO', 'METODO PAGO', 'USUARIO', 'PEDIDO A NOMBRE', 'DIRECCION', 'DPTO', 'MUNI', 'ARTICULO', 'CANTIDAD', '$ PRECIO UND', '% COMISION', '$ COMISION', '$ TOTAL', 'TIPO ENVIO', '$ ENVIO', '$ TOTAL+ENVIO', 'VENDEDOR'];

      $salida = "";
      $salida .= "<table>";
      $salida .= "<thead>";
      foreach($titulos as $tit) $salida .= "<th>".$tit."</th>";
      $salida .= "</thead>";

      foreach($query as $key => $campo):
        //Si Dpto-Muni esta entre las ubicaciones, cuenta como LOCAL... sino.... en envio NAL
        $ubicaciones = explode("/", $campo['detalle_ubicaciones']);
        $esta=0;
        foreach($ubicaciones as $key => $ubic):
          $sitio = explode(",", $ubic);
          if($sitio[0]===$campo['id_departamento'] && $sitio[1]===$campo['id_municipio']) $esta++;
        endforeach;

        $tipoEnvio=($esta>0)?'LOCAL':'NAL';
        $valorEnvio=($esta>0)?$campo['detalle_valor_envio_local']:$campo['detalle_valor_envio_nal'];
        $valorCompleto = $campo['detalle_valor_total'] + $valorEnvio;
        $nombreVendedor = ($campo['vendedorNombre'] === $campo['vendedorApellido']) ? $campo['vendedorNombre'] : $campo['vendedor'];
        $valcomision=0;
        if($campo['usuarios_comision']!==''){
          $precioTodo = $campo['detalle_precio']*$campo['detalle_cantidad'];
          $valcomision=($precioTodo * $campo['usuarios_comision']) / 100;
        }
        $valcomision = ($valcomision!==0)?'$ '.number_format($valcomision, 0, ',', '.'):'';
        $salida .= "<tr> 
                      <td>".$campo['pedidos_fecha']."</td> 
                      <td>".$campo['pedidos_id']."</td>
                      <td>".utf8_decode($campo['pedidos_estado'])."</td>
                      <td>".$campo['pedidos_metodo_pago']."</td>
                      <td>".utf8_decode($campo['pedidos_usuario'])."</td>
                      <td>".utf8_decode($campo['pedidos_a_nombre'])."</td>
                      <td>".utf8_decode($campo['pedidos_direccion'])."</td>
                      <td>".utf8_decode($campo['departamento'])."</td>
                      <td>".utf8_decode($campo['municipio'])."</td>
                      <td>".utf8_decode($campo['producto_nombre'])."</td>
                      <td>".$campo['detalle_cantidad']."</td>
                      <td>$ ".number_format($campo['detalle_precio'], 0, ',', '.')."</td>
                      <td>".$campo['usuarios_comision']."</td>
                      <td>".$valcomision."</td>
                      <td>$ ".number_format($campo['detalle_valor_total'], 0, ',', '.')."</td>
                      <td>".$tipoEnvio."</td>
                      <td>$ ".number_format($valorEnvio, 0, ',', '.')."</td>
                      <td>$ ".number_format($valorCompleto, 0, ',', '.')."</td>
                      <td>".utf8_decode($nombreVendedor)."</td>
                    </tr>";
      endforeach;
      $salida .= "</table>";

      header("Content-type: application/vnd.ms-excel");
      header("Content-Disposition: attachment; filename=pedidos_".date('dmY')."-".time().".xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      echo $salida;
    }

    public function exportarPedidos(){

      $cellKey = array(
        'A','B','C','D','E','F','G','H','I','J','K','L','M',
        'N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
        'AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM',
        'AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ'
      );

      $page = 1;
      $limite=array(1000000,0);
      $filtros = array();
      
      $pedidos = $this->pedidos_model->getAll($filtros,$page,$limite);

      $datos = array();

      // recorremos el pedido
      foreach ($pedidos['pedidos'] as $key => $value) {
        array_push($datos,array(
          "pedidos_id" => $value['pedidos_id'],
          "items" => array()
        ));
        // recorremos los productos del pedido
        foreach ($pedidos['pedidos_productos']->result_array() as $key0 => $value0) {
          //$agg = 0;
          // recorremos los productos para comparar los datos y sacar la información necesaria
          foreach ($pedidos['productos']->result_array() as $key1 => $value1) {
            
            if ($value0['pedidos_productos_pedido_id']==$value['pedidos_id'] 
            && $value0['pedidos_productos_producto_id']==$value1['productos_id']) {

              $precio_solo = floatval($value0['pedidos_productos_precio']);
              $cantidad = floatval($value0['pedidos_productos_cantidad']);
              $precio_total = $precio_solo*$cantidad;
              $precio_envio = 0;
              $metodo_pago = "";
              $estatus = "";
              
              $metodo_pago = $value['pedidos_metodo_pago'];

              if ($cantidad > 0) {
                if ($value1['productos_envio_local']==1) {
                  $ubicado = 0;
                  $ubicaciones = explode("/",$value1['productos_ubicaciones_envio']);
                  
                  for ($i=0; $i < count($ubicaciones); $i++) {
                    $ubi = explode(",",$ubicaciones[$i]);
                    if (isset($ubi[1]) && $ubi[0]==$value['pedidos_departamento'] && $ubi[1]==$value['pedidos_localidad']) {
                      $ubicado=1;                          
                      $precio_envio=$value1['productos_valor_envio_local'];
                    }
                  }

                  if ($ubicado==0) {
                    $precio_envio=$value1['productos_valor_envio_nacional'];
                  }
                }else{
                  $precio_envio=$value1['productos_valor_envio_nacional'];
                }
              }
              if($value['pedidos_estatus']==6){
                $estatus = "Rechazado";
              }elseif ($value['pedidos_estatus']==4) {
                $estatus = "En Preparación";  
              }else {
                switch ($value['pedidos_estatus']) {
                  case 'Enviado':
                    $estatus = $value['pedidos_estatus'];  
                    break;
                  case 'En preparación':
                      $estatus = $value['pedidos_estatus'];  
                    break;
                  case 'Esperando confirmación de pago':
                    $estatus = $value['pedidos_estatus'];
                    break;  
                  case 1:
                    $estatus = "En Preparación";
                    break;  
                  case 'Esperando confirmación de Pago':
                    $estatus = $value['pedidos_estatus'];
                    break;  
                  case 'Cancelado':
                    $estatus = $value['pedidos_estatus'];
                    break;
                  default:
                    $estatus = $value['pedidos_estatus'];
                    break;          
                }
              }

              for ($i=0; $i < count($datos); $i++) {
                if ($datos[$i]['pedidos_id']==$value['pedidos_id']) {
                  $agg = 0;
                  for ($i2=0; $i2 < count($datos[$i]['items']); $i2++) { 
                    // comparamos para ver si existe el vendedor
                    if (isset($datos[$i]['items'][$i2]['vendedor']) 
                    && $datos[$i]['items'][$i2]['vendedor']==$value1['productos_vendedor'] ) {
                      $agg = 1;
                      array_push($datos[$i]['items'][$i2]['productos'],array(
                        "fecha_pedido" => $value['pedidos_fecha'],
                        "nro_pedido" => $value['pedidos_id'],
                        "nombre_cliente" => $value['pedidos_nombre'],
                        "direccion_pedido" => $value['pedidos_direccion'],
                        "departamento_pedido" => $value['departamento'],
                        "municipio_pedido" => $value['municipio'],
                        "articulo" => $value1['productos_titulo'],
                        "cantidad" => $cantidad,
                        "precio_solo" => $precio_solo,
                        "precio_total" => $precio_total,
                        "precio_envio" => $precio_envio,
                        "vendedor" => $value1['productos_vendedor'],
                        "vendedor_nombre" => $value1['name'],
                        "estatus" => $estatus,
                        "metodo_pago" => $metodo_pago,
                      ));
                      array_push($datos[$i]['items'][$i2]['envios'],$precio_envio);
                    }  
                  }
                  // Si no éxiste el vendedor, lo agregamos
                  if ($agg==0) {

                    array_push($datos[$i]['items'], array(
                      "vendedor" => $value1['productos_vendedor'],
                      "productos" => array(
                        array(
                          "fecha_pedido" => $value['pedidos_fecha'],
                          "nro_pedido" => $value['pedidos_id'],
                          "nombre_cliente" => $value['pedidos_nombre'],
                          "direccion_pedido" => $value['pedidos_direccion'],
                          "departamento_pedido" => $value['departamento'],
                          "municipio_pedido" => $value['municipio'],
                          "articulo" => $value1['productos_titulo'],
                          "cantidad" => $cantidad,
                          "precio_solo" => $precio_solo,
                          "precio_total" => $precio_total,
                          "precio_envio" => $precio_envio,
                          "vendedor" => $value1['productos_vendedor'],
                          "vendedor_nombre" => $value1['name'],
                          "estatus" => $estatus,
                          "metodo_pago" => $metodo_pago,
                        )
                      ),
                      "envios" => array($precio_envio)
                    ));
                  }
                  
                }
                
              }

            }

          }
        }

      }

      $conteo = 1;
      $conteo2 = 0;
      

      $this->load->library('excel');
      //Asumiendo que ya hayamos solicitado la libreria iniciamos la primera hoja
      $this->excel->setActiveSheetIndex(0);

      //Le colocamos el nombre a la primera hoja o pestaña
      $this->excel->getActiveSheet()->setTitle('Pedidos');

      //Ingresamo el X's texto en la celda A1

      $this->excel->getActiveSheet()->setCellValue('A1', "Fecha");
      $this->excel->getActiveSheet()->setCellValue('B1', "No. Pedido");
      $this->excel->getActiveSheet()->setCellValue('C1', "Nombre Cliente");
      $this->excel->getActiveSheet()->setCellValue('D1', "Dirección");
      $this->excel->getActiveSheet()->setCellValue('E1', "Departamento");
      $this->excel->getActiveSheet()->setCellValue('F1', "Municipio");
      $this->excel->getActiveSheet()->setCellValue('G1', "Artículo");
      $this->excel->getActiveSheet()->setCellValue('H1', "Cantidad");
      $this->excel->getActiveSheet()->setCellValue('I1', "Valor artículo");
      $this->excel->getActiveSheet()->setCellValue('J1', "Valor Envío");
      $this->excel->getActiveSheet()->setCellValue('K1', "Valor Total");
      $this->excel->getActiveSheet()->setCellValue('L1', "Vendedor");
      $this->excel->getActiveSheet()->setCellValue('M1', "Estado del Pedido");
      $this->excel->getActiveSheet()->setCellValue('N1', "Método de Pago");

      $contTotal = 1;
      foreach ($datos as $key => $value) {
        foreach ($value['items'] as $key2 => $value2) {
          $cont = 0;
          for ($i=0; $i < count($value2['productos']); $i++) {
            $cont++;
            $contTotal++;
            $this->excel->getActiveSheet()->setCellValue('A'.$contTotal, $value2['productos'][$i]['fecha_pedido']);
            $this->excel->getActiveSheet()->setCellValue('B'.$contTotal, $value2['productos'][$i]['nro_pedido']);
            $this->excel->getActiveSheet()->setCellValue('C'.$contTotal, $value2['productos'][$i]['nombre_cliente']);
            $this->excel->getActiveSheet()->setCellValue('D'.$contTotal, $value2['productos'][$i]['direccion_pedido']);
            $this->excel->getActiveSheet()->setCellValue('E'.$contTotal, $value2['productos'][$i]['departamento_pedido']);
            $this->excel->getActiveSheet()->setCellValue('F'.$contTotal, $value2['productos'][$i]['municipio_pedido']);
            $this->excel->getActiveSheet()->setCellValue('G'.$contTotal, $value2['productos'][$i]['articulo']);
            $this->excel->getActiveSheet()->setCellValue('H'.$contTotal, strval($value2['productos'][$i]['cantidad']));
            $this->excel->getActiveSheet()->setCellValue('I'.$contTotal, strval($value2['productos'][$i]['precio_solo']));
            
            if ($cont==count($value2['productos'])) {
              $this->excel->getActiveSheet()->setCellValue('J'.$contTotal, strval(max($value2['envios'])));
              $this->excel->getActiveSheet()->setCellValue('K'.$contTotal, strval($value2['productos'][$i]['precio_total']+floatval(max($value2['envios']))));
            }else{
              $this->excel->getActiveSheet()->setCellValue('J'.$contTotal, "");
              $this->excel->getActiveSheet()->setCellValue('K'.$contTotal, strval($value2['productos'][$i]['precio_total']));
            }
            
            $this->excel->getActiveSheet()->setCellValue('L'.$contTotal, $value2['productos'][$i]['vendedor_nombre']);
            $this->excel->getActiveSheet()->setCellValue('M'.$contTotal, $value2['productos'][$i]['estatus']);
            $this->excel->getActiveSheet()->setCellValue('N'.$contTotal, $value2['productos'][$i]['metodo_pago']);

          }

        }
      }
      $sheet = $this->excel->getActiveSheet();
      $sheet->getColumnDimension('A')->setWidth(20);
      $sheet->getColumnDimension('B')->setWidth(15);
      $sheet->getColumnDimension('C')->setWidth(40);
      $sheet->getColumnDimension('D')->setWidth(40);
      $sheet->getColumnDimension('E')->setWidth(20);
      $sheet->getColumnDimension('G')->setWidth(40);
      $sheet->getColumnDimension('H')->setWidth(20);
      $sheet->getColumnDimension('I')->setWidth(20);
      $sheet->getColumnDimension('J')->setWidth(20);
      $sheet->getColumnDimension('K')->setWidth(30);
      $sheet->getColumnDimension('L')->setWidth(30);
      $sheet->getColumnDimension('M')->setWidth(20);

      //Aca le asignamos el nombre al archivo
      $filename='pedidos_listado_'.date('Y-m-d_h-i').'.xls';

      //Seteamos el mime
      header('Content-Type: application/vnd.ms-excel');

      //Le enviamos al navegador el nombre del archivo para su respectiva descarga
      header('Content-Disposition: attachment;filename="'.$filename.'"');

      //Le indicamos que no deje en cache nada
      header('Cache-Control: max-age=0');
                  
      //Se genera la mágia, y se construye TODO
      $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  

      //forzamos la entrega del archivo a nuestro navegador (Descarga pes...)
      $objWriter->save('php://output');
    
    }

    public function agregar()
    {

    }

    public function editar($id=0,$nombre="")
    {
      $datos = array();
      $datos['view'] = "pedidos/add";
      $datos['css_data'] = array(
        '/assets/plugins/dropify/dropify.min.css',
      );
      $datos['js_data'] = array(
        'assets/plugins/dropify/dropify.min.js',
        'assets/js/pages/pedidos/add.js?v='.rand(99,9999),
      );
      $datos['pedido'] = $this->pedidos_model->single($id);
      
      $this->load->view('normal_view', $datos);  

    }

    public function editar_new($id=0, $nombre="")
    {
      $datos = [
        'view' => 'pedidos/add_new',
        'css_data' => [ 
          'assets/plugins/dropify/dropify.min.css' 
        ],
        'js_data' => [
            'assets/plugins/dropify/dropify.min.js',
            'assets/js/pages/pedidos/add.js?'.rand()
          ],
        'notas_internas' => $this->pedidos_model->notas_internas($id),
        'pedido' => $this->pedidos_model->singleNew($id)
      ];
      $this->load->view('normal_view', $datos);  
    }

    public function guardar(){
      $datos = array();
      $ingresar = $this->pedidos_model->edit();
      if ($ingresar['data']) {
        
        if ($ingresar['estatus']['pedidos_estatus']=="Enviado") {
          $sumaSaldo = $this->balance_model->agregarSaldo('credito',$ingresar['estatus']['pedidos_id'],$ingresar['estatus']['pedidos_productos']);
        }
        
        $this->session->set_userdata('message_tipo', "success");
        $this->session->set_userdata('message', "Guardado Éxitoso");
        redirect(base_url("pedidos/editar/".$_POST['pedidos_id']), $datos);

      }else {
          $this->session->set_userdata('message_tipo', "error");
          $this->session->set_userdata('message', "Error al registrar.");
          redirect(base_url("pedidos/editar/".$_POST['pedidos_id']), $datos);
      }
    }
    
    public function guardar_new(){
      $datos = array();      
      $ingresar = $this->pedidos_model->edit_new();

      if ($ingresar['data']) {
        $this->session->set_userdata('message_tipo', "success");
        $this->session->set_userdata('message', "Guardado Éxitoso");
      }else {
        $this->session->set_userdata('message_tipo', "error");
        $this->session->set_userdata('message', "Error al registrar.");
      }

      //-------------------------------------------------------------------------------------------------------
      /*
      ** Author: Yeisson Patarroyo Guapacho - Y3p4gu
      ** Fecha Modificacion: 26-01-2023
      ** Descuento Inventario
      */
      $estadosAnteriores=['1', 'Esperando confirmación de Pago', 1];
      if ($_POST['pedidos_estatus']==="En preparación" && in_array($_POST['pedidos_estatus_anterior'], $estadosAnteriores)){
        $prodPedido = $this->db->where("pedidos_detalle_pedidos_id", $_POST['pedidos_id'])->get('alma_pedidos_detalle')->result_array();

        foreach($prodPedido as $key => $prod):
          $producto = $this->db->where("productos_id", $prod['pedidos_detalle_producto'])->get('productos')->result_array();
          $producto = $producto[0];
          if(intval($producto['productos_gestion_inv'])===1){
            $saldoActual = intval($producto['productos_stock']) - intval($prod['pedidos_detalle_producto_cantidad']);
            if($saldoActual<0){
              $saldoActual=0;
              $data['productos_estado_inv']=2; // Estado Agotado
              $data['productos_estatus']=0; // Producto 'En Borrador'
            }
            $data['productos_stock']=$saldoActual; //Realizamos descuento de Inventario
            $this->db->where("productos_id", $producto['productos_id'])->update('productos', $data);
          }
        endforeach;

      }
      //-------------------------------------------------------------------------------------------------------

      
      if(isset($_POST['estatus_productos_producto_id'])){
        $cantProd=0;
        $cantEnviados=0;

        foreach($_POST['pedidos_productos_estatus'] as $llave => $prod):
          $cantProd++;
          if($prod==='Enviado') $cantEnviados++;
        endforeach;

        if($cantEnviados === $cantProd){
          $estadoPedido=['En preparación', 'Enviado'];
          if(in_array($_POST['pedidos_estatus_anterior'], $estadoPedido)){
            $data=['pedidos_estatus'=>'Enviado'];
            $this->db->where('pedidos_id', $_POST['pedidos_id'])->update('alma_pedidos', $data);
          } 
        }
      }

      redirect(base_url('pedidos/editar_new/'.$_POST['pedidos_id']), $datos);
    }
    
    public function delete()
    {
      $datos = array();
      $id=$this->input->post("id");
      $query = $this->db->delete('pedidos', array('pedidos_id' => $id));
      if ($query) {
        $datos['mensaje']="Eliminado con éxito";
        $datos['result']=1;
      }else {
        $datos['mensaje']="Hubo un error, intente de nuevo!";
        $datos['result']=0;
      }
      echo json_encode($datos);
    }

    public function delete_new()
    {
      $datos = array();
      $id=$this->input->post("id");
      $query = $this->db->delete('alma_pedidos', array('pedidos_id' => $id));
      if ($query) {
        $datos['mensaje']="Eliminado con éxito";
        $datos['result']=1;
      }else {
        $datos['mensaje']="Hubo un error, intente de nuevo!";
        $datos['result']=0;
      }
      echo json_encode($datos);
    }

    public function subirvideo()
    {
      $datos = array();
      $nombre_fichero =  $_SERVER['DOCUMENT_ROOT']."/assets/uploads/videospedidos/".limpiarUri(date("m-Y"));

      if (file_exists($nombre_fichero)) {
        if (move_uploaded_file($_FILES["pedidos_video"]["tmp_name"], $nombre_fichero."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['pedidos_video']['name']))) {
              $datos["medios_url"] = "assets/uploads/videospedidos/".limpiarUri(date("m-Y"))."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['pedidos_video']['name']);
              $nombreEx = explode(".",$_FILES["pedidos_video"]["name"]);
              $datos["medios_titulo"] = "";
              for ($i=0; $i < count($nombreEx); $i++) {
                  if ($i!=count($nombreEx)-1) {
                      $datos["medios_titulo"] .= $nombreEx[$i];
                  }
              }
              $datos["medios_user"] = $_SESSION['usuarios_id'];
              $datos["success"] = 1;

          } else {
              $datos["success"] = 0;
          }
      } else {
          mkdir($_SERVER['DOCUMENT_ROOT']."/assets/uploads/videospedidos/".limpiarUri(date("m-Y")), 0777);
          if (move_uploaded_file($_FILES["pedidos_video"]["tmp_name"], $nombre_fichero."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['pedidos_video']['name']))) {
              $datos["medios_url"] = "assets/uploads/videospedidos/".limpiarUri(date("m-Y"))."/".encodeItem(date("d-m-Y h:i:s")).limpiarConPunto($_FILES['pedidos_video']['name']);
              $nombreEx = explode(".",$_FILES["pedidos_video"]["name"]);
              $datos["medios_titulo"] = "";
              for ($i=0; $i < count($nombreEx); $i++) {
                  if ($i!=count($nombreEx)-1) {
                      $datos["medios_titulo"] .= $nombreEx[$i];
                  }
              }
              $datos["medios_user"] = $_SESSION['usuarios_id'];
              $datos["success"] = 1;
          } else {
              $datos["success"] = 0;
          }
      }
      return $datos;
    }

    public function pruebaCorreos(){
      $this->mailing_model->mailNuevaVenta($this->pedidos_model->singleNew(3380));
    }

    public function addNota(){
      
      if ($_POST['notas_pedidos_tipo']=="undefined") {
        $_POST['notas_pedidos_tipo'] = 2;
      }

      $fecha = date("Y-m-d h:s:i");
      
      $data = array(
        "notas_pedidos_usuarios_id" => $_SESSION['usuarios_id'],
        "notas_pedidos_fecha_emitido" => $fecha,
        "notas_pedidos_pedido_id" => $_POST['notas_pedidos_pedido_id'],
        "notas_pedidos_tipo" => $_POST['notas_pedidos_tipo'],
        "notas_pedidos_mensaje" => $_POST['notas_pedidos_mensaje'],
        "notas_pedidos_usuario_dirigido" => $_POST['notas_pedidos_usuario_dirigido'],
      );

      $this->db->insert('notas_pedidos', $data);

      $datos = array();
      $datos['autor'] = $_SESSION['name'];
      $datos['mensaje'] = $_POST['notas_pedidos_mensaje'];
      $datos['fecha'] = $fecha;
      $datos['error'] = 0;
      echo json_encode($datos, JSON_UNESCAPED_UNICODE);

    }

    public function obtenerPedidosMercadoPago(){
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/search?sort=date_created&criteria=desc',
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Authorization: Bearer APP_USR-737037781546252-072613-81cac146ff52e9ee8c693a407ab495b3-1166475224'
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
    }

    public function pruebaMercadoPago(){
      $datos = array();
      $datos['view'] = "pedidos/pruebaMercadoPago";
      $datos['css_data'] = array();
      $datos['js_data'] = array(
          'assets/js/pages/pedidos/testMercadoPago.js?v='.rand(99,9999),
      );
      $this->load->view('normal_view', $datos);
    }

    public function verPedidosMercadoPago(){
      /*
      ** Author: Yeisson Patarroyo Guapacho - Y3p4gu
      ** Fecha Modificacion: 26-01-2023
      ** Actualizacion de Estados de los pagos MercadoPago
      */
      
      $pedidos=[];
      if(isset($_POST['pedidos']) && is_array($_POST['pedidos'])){
        foreach($_POST['pedidos'] as $key => $ped):
          $pedidos[] = str_replace("Pedido Nro #","", $ped);
        endforeach;
      }
      
      $estatus = $_POST['estatus'];
      $pedidos_not_modify = array();

      $this->db->select("pedidos_id, pedidos_estatus");
      $this->db->where_in('pedidos_id', $pedidos);
      $cons = $this->db->get("alma_pedidos")->result_array();

      foreach ($cons as $key => $value) {
        for ($i=0; $i < count($pedidos); $i++) {
          if ($pedidos[$i]==$value['pedidos_id'] && ($value['pedidos_estatus']=="Enviado" || $value['pedidos_estatus']==4 || $value['pedidos_estatus']=="En preparación")
          ) {
            array_push($pedidos_not_modify,$value['pedidos_id']);
          }
        }
      }

      $data = array();
      
      for ($i=0; $i < count($estatus); $i++) {
        if (!in_array($pedidos[$i],$pedidos_not_modify)) {
          $this_estatus = "Pendiente";
          if ($estatus[$i]=="approved") $this_estatus = "En preparación";
          if ($estatus[$i]=="rejected") $this_estatus = "Rechazado";
          if ($estatus[$i]=="pending")  $this_estatus = "Pendiente";
          array_push($data,array(
            "pedidos_id" => $pedidos[$i],
            "pedidos_estatus" => $this_estatus,
          ));
        }
      }
      
      if(count($data)>0) $this->db->update_batch('alma_pedidos', $data, 'pedidos_id');
    }


    public function calffin(){
      $fecha_ini = $_REQUEST['fini'];
      $fecha_fin  = date("Y-m-d", strtotime($fecha_ini."+ 1 month")); 
      print json_encode(['resp'=>'ok' , 'ffin'=>$fecha_fin]);
    }

}
