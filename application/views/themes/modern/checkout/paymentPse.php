<!DOCTYPE html>
<html class="h-100" lang="es">
	<head>

		<meta charset="utf-8">
		<meta name="description" content="Alma de las cosas" />
		<meta name="author" content="Alma de las cosas - Signin" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<title>Alma de las cosas - Iniciar</title>
		<link rel="icon" type="image/x-icon" href="<?=base_url()?>/assets/img/icon.jpg"/>
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="<?=base_url()?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="<?=base_url()?>assets/css/fuentes.css" rel="stylesheet" type="text/css" />
		<link href="<?=base_url()?>assets/css/estilos.css?<?=rand()?>" rel="stylesheet" type="text/css" />
		<!-- END GLOBAL MANDATORY STYLES -->
		<style type="text/css">
			.h-30{
				height: 30% !important;
			}
		</style>

	</head>
<body class="body-login h-100">
	<div class="col-12 h-100">
		<div class="row h-30">
			<div class="col-md-2 col-sm-1"></div>
			<div class="col-md-8 col-12 h-30">
				
				<div class="col-12 text-center">
					<span class="d-md-inline"><b>Pago PSE</b></span>
				</div>
				<div class="col-12 h-30"> </div>
				<div class="caja-login row">
					<div class="controls col-12">
						<b>Datos del Pedido</b>	
					</div>
					<div class="col-6">
						<label>Número del pedido</label>
					</div>
					<div class="col-6 text-right">
						<h5>#<?=$pedido['pedidos_id']?></h5>
					</div>
					<div class="col-6">
						<label>Fecha</label>
					</div>
					<div class="col-6 text-right">
						<h5><?=$pedido['pedidos_fecha_larga']?></h5>
					</div>
					<div class="col-6">
						<label>Total</label>
					</div>
					<div class="col-6 text-right">
						<h5>$ <?=number_format($pedido['vlrtotal'], 0, ',', '.')?></h5>
					</div>
					<div class="col-6">
						<label>Metodo Pago</label>
					</div>
					<div class="col-6 text-right">
						<h5>PSE</h5>
					</div>

					<div class="col-12 text-justify">
						Ha seleccionado el metodo de pago PSE, para ir a pagar haga clic en le boton Continuar
					</div>
					<div class="col-12 text-center">
						<img height="70" alt="logo pse" src="<?=base_url('assets/img/methods/logo_pse.png')?>">
					</div>

					<div class="col-12 text-center">
						<?php
						$vad_trans_date = gmdate("YmdHis");
	                    $vad_trans_id = date("his");
	                    $vads = array(
	                        "vads_action_mode" => "INTERACTIVE",
	                        "vads_amount" => $pedido['pedidos_precio_total']."00",
	                        //"vads_ctx_mode" => "PRODUCTION",
	                        //"vads_ctx_mode" => "TEST",
	                        "vads_ctx_mode" => $modo,
	                        "vads_currency" => "170",
	                        "vads_cust_country" => "CO",
	                        "vads_cust_email" => $pedido['pedidos_correo'],
	                        "vads_cust_first_name" => $pedido['pedidos_nombre'],
	                        "vads_cust_last_name" => "",
	                        "vads_cust_phone" => $pedido['pedidos_telefono'],
	                        "vads_page_action" => "PAYMENT",
	                        "vads_payment_cards" => "PSE",
	                        "vads_payment_config" => "SINGLE",
	                        "vads_site_id" => "80379999",
	                        "vads_trans_date" => $vad_trans_date,
	                        "vads_trans_id" => $vad_trans_id,
	                        "vads_url_success" => base_url('checkout/thanks/exitoso/pedido-'.$pedido['pedidos_id']),                        
	                        "vads_url_refused" => base_url('checkout/thanks/rechazado/pedido-'.$pedido['pedidos_id']),
	                        "vads_url_cancel" => base_url('checkout/thanks/cancelado/pedido-'.$pedido['pedidos_id']),
	                        "vads_url_error" => base_url('checkout/thanks/error/pedido-'.$pedido['pedidos_id']),
	                        "vads_version" => "V2",
	                    );
	                    
	                    $signature = getSignature($vads, $codSignature);
	                    
	                    //$signature = getSignature($vads,"OEOBrffArdHWSSWg"); // PRUEBA
                    	//$signature = getSignature($vads,"WZhiqh8mYRheu8uV"); // PRODUCCIÓN
						?>
						<form method="post" class="form-login col-12 my-5" action="https://secure.payzen.lat/vads-payment/">
							<?php $nropedido = $pedido['pedidos_id'].rand(9,9999); ?>
							<input type="hidden" name="vads_action_mode" value="INTERACTIVE" /> 
	                        <input type="hidden" name="vads_amount" value="<?=$pedido['pedidos_precio_total']."00"?>" /> 
	                        <!-- <input type="hidden" name="vads_ctx_mode" value="PRODUCTION" />  -->
	                        <!-- <input type="hidden" name="vads_ctx_mode" value="TEST" />  -->
	                        <input type="hidden" name="vads_ctx_mode" value="<?=$modo?>" /> 
	                        <input type="hidden" name="vads_currency" value="170" />
	                        <input type="hidden" name="vads_cust_country" value="CO" />
	                        <input type="hidden" name="vads_cust_email" value="<?=$pedido['pedidos_correo']?>" />
	                        <input type="hidden" name="vads_cust_first_name" value="<?=$pedido['pedidos_nombre']?>" />
	                        <input type="hidden" name="vads_cust_last_name" value="" />
	                        <input type="hidden" name="vads_cust_phone" value="<?=$pedido['pedidos_telefono']?>" />
	                        <input type="hidden" name="vads_page_action" value="PAYMENT" />
	                        <input type="hidden" name="vads_payment_cards" value="PSE" /> 
	                        <input type="hidden" name="vads_payment_config" value="SINGLE" /> 
	                        <input type="hidden" name="vads_site_id" value="80379999" /> 
	                        <input type="hidden" name="vads_trans_date" value="<?=$vad_trans_date?>" /> 
	                        <input type="hidden" name="vads_trans_id" value="<?=$vad_trans_id?>" /> 
	                        <input type="hidden" name="vads_url_success" value="<?=base_url('checkout/thanks/exitoso/pedido-'.$pedido['pedidos_id'])?>" />
                        	<input type="hidden" name="vads_url_refused" value="<?=base_url('checkout/thanks/rechazado/pedido-'.$pedido['pedidos_id'])?>" />
                        	<input type="hidden" name="vads_url_cancel" value="<?=base_url('checkout/thanks/cancelado/pedido-'.$pedido['pedidos_id'])?>" />
                        	<input type="hidden" name="vads_url_error" value="<?=base_url('checkout/thanks/error/pedido-'.$pedido['pedidos_id'])?>" />
	                        <input type="hidden" name="vads_version" value="V2" />
	                        <input type="hidden" name="signature" value="<?=$signature?>"/>

							<button class="btn btn-success">Continuar a PSE</button>
						</form>
					</div>
				</div>
						
				
					
			</div>

		</div>
	</div>
	

    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="<?=base_url()?>/assets/bootstrap/js/jquery.min.js"></script>
    <script src="<?=base_url()?>/assets/bootstrap/js/popper.min.js"></script>
    <script src="<?=base_url()?>/assets/bootstrap/js/bootstrap.min.js"></script>
	
	
</body>
</html>



