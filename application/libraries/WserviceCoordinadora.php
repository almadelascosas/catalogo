<?php
defined('BASEPATH') or exit('No direct script access allowed');

class WserviceCoordinadora
{
	
	function getGenerarGuia($idPedido, $prodArray){
		$CI = &get_instance();
		$CI->load->model([
			'pedidos_model' => 'mdPed',
			'productos_model' => 'mdProd',
			'usuarios_model' => 'mdUsu'
		]);
		$CI->load->library('session');

		$pedido = $CI->mdPed->singleNew($idPedido);
		$valorDeclarado=0;
		$contenidoDetalle=[];

		$obsArticulos=[];

		foreach($prodArray as $key => $prod):
			foreach($pedido['pedidos_productos'] as $prodPed){
				if($prodPed['pedidos_detalle_producto']===$prod){
					$cantidad = $prodPed['pedidos_detalle_producto_cantidad'];
					$vendedor = $CI->mdUsu->getUserById($prodPed['pedidos_detalle_vendedor'])[0];
				}
			}

			$producto = $CI->mdProd->single($prod);
			$obsArticulos[] = eliminar_tildes($producto['productos_titulo']);
			
			list($nd, $largo, $ancho, $alto, $nd2) = explode('/,/', $producto['productos_dimensiones']);
			$valorDeclarado += $producto['productos_precio']*$cantidad;

			$detalleItem = [
						'ubl' => '0',
						'alto' => $alto,
						'ancho' => $ancho,
						'largo' => $largo,
						'peso' => $producto['productos_peso'],
						'unidades' => $cantidad,
						'nombre_empaque' => ''
					];
			$xmlDoc = new DOMDocument();
			foreach ($detalleItem as $key => $item) :
				$xmlDoc->appendChild($xmlDoc->createElement($key, $item));
			endforeach;

			header("Content-Type: text/plain");
			$xmlDoc->formatOutput = true;
			$file_name2 = 'coordinadoraXML/detalle.xml';
			$xmlDoc->save($file_name2);	

			ob_start();
			include $file_name2;
			$contenidoDetalle[] = '<item>'.ob_get_clean().'</item>';
			unlink($file_name2);
		endforeach;

		$CI->session->set_userdata('dataXML', [
				'fecha' => '',
				'nit_remitente' => '', //($vendedor['dni']!=='' && $vendedor['dni']!==null) ? $vendedor['dni'] : $vendedor['usuarios_id'],
				'nombre_remitente' => $vendedor['name'].' '.$vendedor['lastname'],
				'direccion_remitente' => $vendedor['direccion'],
				'telefono_remitente' => $vendedor['phone'],
				'ciudad_remitente' => '05001000', //$vendedor['usuarios_departamento'].$vendedor['usuarios_municipio'].'0', //'05001000'

				'nit_destinatario' => $pedido['pedido']['pedidos_identificacion'],
				'nombre_destinatario' => $pedido['pedido']['pedidos_nombre'],
				'direccion_destinatario' => $pedido['pedido']['pedidos_direccion'],				
				'telefono_destinatario' => $pedido['pedido']['pedidos_telefono'],
				'ciudad_destinatario' => '73001000', //$pedido['pedido']['pedidos_departamento'].$pedido['pedido']['pedidos_municipio'].'0', //'73001000'
				
				'valor_declarado' => $valorDeclarado,
				'codigo_producto' => 0,
				'nivel_servicio' => 1,
				'contenido' => 'ARTICULO DE HOGAR',
				'observaciones' => implode(', ', $obsArticulos),
				'estado' => 'IMPRESO'
			]);

		ob_start();
		include "assets/templateCoordinadora/generarGuia.xml";
		$nombreArchivo = 'Registro.xml';
		$xmlBase = ob_get_clean();

		//----------------------------------------------------------------------
		//Parte Principal
		$xmlDoc = new DOMDocument();
		foreach ($CI->session->userdata('dataXML') as $key => $item) :
			$xmlDoc->appendChild($xmlDoc->createElement($key, $item));
		endforeach;

		header("Content-Type: text/plain");
		$xmlDoc->formatOutput = true;
		$file_name1 = 'coordinadoraXML/principal.xml';
		$xmlDoc->save($file_name1);

		ob_start();
		include $file_name1;
		$contenidoPrincipal = ob_get_clean();
		unlink($file_name1);	

		//----------------------------------------------------------------------
		//Parte Detalle
		$contenidoDetalleUnificado='';
		foreach($contenidoDetalle as $key => $xmlItem):
			$contenidoDetalle[$key] = str_replace('<?xml version="1.0"?>', '', $xmlItem);
		endforeach;
		$contenidoDetalleUnificado = implode('',$contenidoDetalle);
		//----------------------------------------------------------------------

		$dataWS = $this->getUrlWS();
		if ($dataWS['est'] === 'ERROR') {
			return json_encode(array(
				'rpta' => 'error',
				'msj' => '!Lo Sentimos, no fue posible contactar la WS de la RNDC',
				'wsestado' => 0,
				'wsmsj' => ''
			));
			exit();
		}

		/*
		Al archivo base, se le remplaza los textos segun los parametros
		*/
		$file_name1 = str_replace('<?xml version="1.0"?>', '', $contenidoPrincipal);
		$file_name2 = str_replace('<?xml version="1.0"?>', '', $contenidoDetalleUnificado);
		$nuevoTexto = $xmlBase;

		$nuevoTexto =  str_replace('XX-PRINCIPAL-XX', $file_name1, $nuevoTexto);
		$nuevoTexto =  str_replace('XX-DETALLE-XX', $file_name2, $nuevoTexto);

		$nuevoTexto =  str_replace('XX-IDCLIENTE-XX', $dataWS['idcliente'], $nuevoTexto);
		$nuevoTexto =  str_replace('XX-USERNAME-XX', $dataWS['api_username'], $nuevoTexto);
		$nuevoTexto =  str_replace('XX-CLAVE-XX', $dataWS['api_clave'], $nuevoTexto);

		$nuevoTexto = str_replace('<?xml version="1.0"?>', '', $nuevoTexto);

		$nombreArchivo = 'coordinadoraXML/generarGuia_'.$idPedido.'_'.date('dmY_His').'.xml';
		$xmlDoc = new DOMDocument();
		$f = $xmlDoc->createDocumentFragment();
		$f->appendXML($nuevoTexto);
		$xmlDoc->appendChild($f);
		header("Content-Type: text/plain");
		$xmlDoc->formatOutput = true;
		$xmlDoc->save($nombreArchivo);

		ob_start();
		include $nombreArchivo;
		$xmlArchivo = ob_get_clean();

		$xmlArchivo = str_replace('<?xml version="1.0"?>', '', $xmlArchivo);

		//-----------------------------------------------------------------------------
		//Consumo WebService
		
		/*
		$options = array(
			'login' => '',
			'password' => '',
		);
		*/
		$soapclient = new SoapClient($dataWS['url1']);
		$parametros = array('Request' => $xmlArchivo);
		$response = $soapclient->__soapCall('Guias_generarGuia', $parametros);

		print $response;
		exit();

		if (is_soap_fault($response)) {
			return json_encode(array(
				'rpta' => 'error',
				'msj' => '',
				'wsestado' => 0,
				'wsmsj' => $response->faultcode . ' | ' . $response->faultstring
			));
		} else {
			$xml = simplexml_load_string($response);
			$json  = json_encode($xml);
			$respuesta_array = json_decode($json, true);
		}
		
	}

	/**
	 * Consumo WebService Coordinadora
	 *
	 * This method is used to retrieve the account corresponding
	 * to a given login. <b>Note:</b> it is not required that
	 * the user be currently logged in.
	 *
	 * @access public
	 * @param string $user user name of the account
	 * @return Account
	 */

	

	function getUrlWS()
	{
		$CI = &get_instance();
		switch ($CI->db->database) {
			case 'pexijfub_codealma': //PRODUCCION
				$url1 = '';
				$url2 = '';
				$api_idcliente = '';
				$api_username = "";
				$api_password = "";
				$api_key = "";
				$api_clave = "";
				break;
			default:
				//PRUEBAS
				$url1 = 'https://sandbox.coordinadora.com/agw/ws/guias/1.6/server.php?wsdl';
				$url2 = 'http://sandbox.coordinadora.com/ags/1.5/server.php?wsdl';
				$api_idcliente = '41139';
				$api_username = "almadecolombia.ws";
				$api_password = "wO4yW7fA5iQ3jR8x";
				$api_key = "429f771a-b478-4e16-81c3-23cb8d0402df";
				$api_clave = "8d9a02d7d9badbd493e0bb4fdf4dc3e9d85db02af1e0716c2a14e6fe3daacf95";
				break;
		}
		
		$file_headers = @get_headers($url1);
		if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found') {
			$estado = 0;
		} else {
			$estado = 1;
			return [
				'est' => 'OK', 
				'url1' => $url1, 
				'url2' => $url2, 
				'idcliente'=> $api_idcliente,
				'api_username' => $api_username, 
				'api_password' => $api_password,
				'api_key' => $api_key,
				'api_clave' => $api_clave
			];
		}

		if ($estado === 0) return ['est' => 'ERROR'];
	}
}
