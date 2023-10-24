<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<base href="<?=base_url('');?>">
		<meta name="description" content="Alma de las cosas - CRM Interno" />
		<meta name="author" content="Alma de las cosas - Developer Senior PHP - Programmer" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<title>Catalogo Alma de las cosas - Accesos</title>
		<link rel="icon" type="image/x-icon" href="img/icon.jpg"/>
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/general-style.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/authentication/form-1.css" rel="stylesheet" type="text/css" />
		<!-- END GLOBAL MANDATORY STYLES -->
	</head>
<body>
	<div class="col-12" style="background-color: black; margin-bottom: 100px;">
		<div class="row">
			<div class="col-12">&nbsp;</div>
			<div class="col-6 text-center" style="height:120px; position: relative; margin: 0 auto;">
				<img width="100" class="logo" src="assets/img/logo.png" alt="Logo">
			</div>
			<div class="col-6 text-center"><h1 style="color:white; font-weight: 600;">Portal Asesores</h1></div>
		</div>
	</div>

	<div class="col-12 d-table h-100">
		<div class=" align-middle">
			
			<div class="caja-login">
				<?php if (isset($_SESSION['message_tipo'])) { ?>
				<div class="alert alert-<?=($_SESSION['message_tipo']==="success")?'success':'danger'?>" role="alert">
					<?=$_SESSION['message']?>
				</div>
				<?php
					unset($_SESSION['message_tipo']);
					unset($_SESSION['message']);
				}
				?>
				<div class="controls col-12">
					<a class="active" href="#form-ingresar">Ingresar</a>
					<a class="" href="#form-registro">Registrarse</a>
				</div>
				<form id="form-ingresar" method="post" class="form-login col-12 my-5" action="auth/authenticate">
					<div class="form-group">
						<label for="email">CORREO ELECTRÓNICO <sup class="color-red">*</sup></label>
						<input id="email" name="email" type="text" class="form-control" placeholder="">
					</div>
					<div class="form-group">
						<label for="pass">CONTRASEÑA <sup class="color-red">*</sup></label>
						<input id="pass" name="pass" type="password" class="form-control" placeholder="">
					</div>
					<div class="form-group text-center">
						<button type="submit" class="btn btn-default">ACCEDER</button>
					</div>
					<p class="text-center"><a class="primary-color" href="#">¿Olvidaste la contraseña?</a></p>

				</form>
				<form id="form-registro" method="post" class="form-login d-none col-12 my-5" action="home/registro">
					<input type="hidden" name="tipo_accesos" id="tipo_accesos" value="6">
					<div class="form-group">
						<label for="username">NOMBRE DE USUARIO <sup class="color-red">*</sup></label>
						<input required id="username" name="username" type="text" class="form-control" placeholder="">
					</div>
					<div class="form-group">
						<label for="email">CORREO ELECTRÓNICO <sup class="color-red">*</sup></label>
						<input required id="email" name="email" type="text" class="form-control" placeholder="">
					</div>
					<div class="form-group">
						<label for="name">NOMBRE</label>
						<input id="name" name="name" type="text" class="form-control" placeholder="">
					</div>
					<div class="form-group">
						<label for="lastname">APELLIDO</label>
						<input id="lastname" name="lastname" type="text" class="form-control" placeholder="">
					</div>
					<div class="form-group">
						<label for="password">CONTRASEÑA <sup class="color-red">*</sup></label>
						<input required id="password" name="password" type="password" class="form-control" placeholder="">
					</div>
					
					<div class="form-group text-center">
						<button type="submit" class="btn btn-default">REGISTRAR</button>
					</div>
					
				</form>

			</div>
		</div>
	</div>


    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/bootstrap/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/popper.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <script src="assets/js/authentication/form-1.js?v=<?=rand(9,9999)?>"></script>
    <script src="assets/js/jquery.redirect.js?<?=rand()?>"></script>
</body>
</html>
