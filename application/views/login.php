<!DOCTYPE html>
<html lang="es">
	<head>
		<meta charset="utf-8">
		<base href="<?=base_url();?>">
		<meta name="description" content="Alma de las cosas - CRM Interno" />
		<meta name="author" content="Alma de las cosas - Developer Senior PHP - Programmer" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
		<title>Alma de las cosas - Accesos</title>
		<link rel="icon" type="image/x-icon" href="img/icon.jpg"/>
		<!-- BEGIN GLOBAL MANDATORY STYLES -->
		<link href="assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/general-style.css" rel="stylesheet" type="text/css" />
		<link href="assets/css/authentication/form-1.css" rel="stylesheet" type="text/css" />
		<!-- END GLOBAL MANDATORY STYLES -->
	</head>
<body class="form">
	<div class="col-12 d-table h-100">
		<div class="d-table-cell align-middle">
			<div class="col-12 text-center">
				<div class="caja-login bg-transparent position-relative">
				<a class="btn btn-back float-left mt-3" href="javascript:history.back();"><span class="arrow-back">&lt;</span></a>
					<img width="100" class="logo" src="assets/img/logo.png" alt="Logo">
				</div>
			</div>
			<div class="caja-login">
				<?php
				if (isset($_SESSION['message_tipo'])) {
					if ($_SESSION['message_tipo']==="success") { ?>
						<div class="alert alert-success" role="alert">
							<?=$_SESSION['message']?>
						</div>
					<?php }else{ ?>
						<div class="alert alert-danger" role="alert">
							<?=$_SESSION['message']?>
						</div>
				<?php
					}
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

					<p class="text-center"><a class="primary-color" href="">Si deseas, puedes iniciar session con las siguientes redes</a></p>
					<div class="form-group text-center">
						<a class="btn btn-outline-dark" href="#" role="button" style="text-transform:none" id="btnLoginG">
							<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
							Login con Google
						</a>
					</div>
					<div class="form-group text-center">
						<a class="btn btn-outline-dark" href="#" role="button" style="text-transform:none" id="btnLoginF">
							<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/640px-Facebook_Logo_%282019%29.png" />
							Login con Facebook
						</a>
					</div>
					<div class="form-group text-center">
						<a class="btn btn-outline-dark" href="#" role="button" style="text-transform:none" id="btnLoginAp">
							<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Apple sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/488px-Apple_logo_black.svg.png" />
							Login con Apple ID
						</a>
					</div>
					<p class="text-center"><a class="primary-color" href="">Ir a sitio web</a></p>
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
					
					<p class="text-center"><a class="primary-color" href="">Si deseas, puedes registrarte con las siguientes redes</a></p>
					<div class="form-group text-center">
						<a class="btn btn-outline-dark" href="#" role="button" style="text-transform:none" id="btnRegistrerG">
							<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />
							Login con Google
						</a>
					</div>
					<div class="form-group text-center">
						<a class="btn btn-outline-dark" href="#" role="button" style="text-transform:none" id="btnRegistrerF">
							<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Facebook_Logo_%282019%29.png/640px-Facebook_Logo_%282019%29.png" />
							Login con Facebook
						</a>
					</div>
					<div class="form-group text-center">
						<a class="btn btn-outline-dark" href="#" role="button" style="text-transform:none" id="btnRegistrerAp">
							<img width="20px" style="margin-bottom:3px; margin-right:5px" alt="Apple sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/fa/Apple_logo_black.svg/488px-Apple_logo_black.svg.png" />
							Login con Apple ID
						</a>
					</div>



					<p class="text-center"><a class="primary-color" href="">Ir a sitio web</a></p>
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

    <script type="module">
    	function myFunction(data, token){
	  		$.redirect("auth/registerSocialMedia", {
				data: data,
				token: token
			},
			"POST", "");
	  	}

	  	function accesoLogin(data, token){
	  		$.redirect("auth/loginSocialMedia", {
				data: data,
				token: token
			},
			"POST", "");
	  	}

		import { initializeApp } from "https://www.gstatic.com/firebasejs/9.15.0/firebase-app.js";
		import { getAuth, signInWithPopup, GoogleAuthProvider, FacebookAuthProvider, OAuthProvider } from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-auth.js'

	  	const firebaseConfig = {
		    apiKey: "AIzaSyDPzCLMEXq1FKS2i-lqjs9o5WX-vVbk88w",
		    authDomain: "alma-de-las-cosas-3ca68.firebaseapp.com",
		    projectId: "alma-de-las-cosas-3ca68",
		    storageBucket: "alma-de-las-cosas-3ca68.appspot.com",
		    messagingSenderId: "696490602739",
		    appId: "1:696490602739:web:f4abbf6e65c005f6ec0786",
		    measurementId: "G-GD5WXZWFL6"
	  	};

	  	// Initialize Firebase
		const firebase = initializeApp(firebaseConfig);
	  	const auth = getAuth();

	  	/* FUNCION REGISTRO */
	  	// Google
	  	document.getElementById('btnRegistrerG').addEventListener('click', function(){
	  		const provider = new GoogleAuthProvider();
			signInWithPopup(auth, provider)
			  .then((result) => {
			    const credential = GoogleAuthProvider.credentialFromResult(result);
			    const token = credential.accessToken;
			    const user = result.user;
			  	myFunction(user.providerData[0], token)		  
			  }).catch((error) => {
			    const errorCode = error.code;
			    const errorMessage = error.message;
			    const credential = GoogleAuthProvider.credentialFromError(error);
			    console.log(credential)
			  });						  	
	  	})
	  	// Facebook
	  	document.getElementById('btnRegistrerF').addEventListener('click', function(){
			const provider = new FacebookAuthProvider();
	  		signInWithPopup(auth, provider)
			  .then((result) => {
			    const credential = FacebookAuthProvider.credentialFromResult(result);
    			const accessToken = credential.accessToken;
    			const user = result.user;

			    myFunction(user.providerData[0], accessToken)	
			  }).catch((error) => {
			    const errorCode = error.code;
			    const errorMessage = error.message;
			    const credential = FacebookAuthProvider.credentialFromError(error);
			    console.log(credential)
			  });						  	
	  	})
	  	// Apple
	  	document.getElementById('btnRegistrerAp').addEventListener('click', function(){
			const provider = new OAuthProvider('apple.com');
	  		signInWithPopup(auth, provider)
			  .then((result) => {
			    const credential = OAuthProvider.credentialFromResult(result);
    			const accessToken = credential.accessToken;
    			const user = result.user;

			    myFunction(user.providerData[0], accessToken)	
			  }).catch((error) => {
			    const errorCode = error.code;
			    const errorMessage = error.message;
			    const credential = OAuthProvider.credentialFromError(error);
			    console.log(credential)
			  });						  	
	  	})

	  	/* FUNCTION LOGIN */
	  	// Google
	  	document.getElementById('btnLoginG').addEventListener('click', function(){
	  		const provider = new GoogleAuthProvider();
			signInWithPopup(auth, provider)
			  .then((result) => {
			    const credential = GoogleAuthProvider.credentialFromResult(result);
			    const token = credential.accessToken;
			    const user = result.user;
			    //console.log(user.providerData[0], token)
			    accesoLogin(user.providerData[0], token)		  
			  }).catch((error) => {
			    const errorCode = error.code;
			    const errorMessage = error.message;
			    const credential = GoogleAuthProvider.credentialFromError(error);
			    console.log(credential)
			  });						  	
	  	})

	  	document.getElementById('btnLoginF').addEventListener('click', function(){
			const provider = new FacebookAuthProvider();
	  		signInWithPopup(auth, provider)
			  .then((result) => {
			    var credential = result.credential;
			    var user = result.user;
			    var Token = credential.accessToken;
			    
			    accesoLogin(user.providerData[0], token)	
			  }).catch((error) => {
			    const errorCode = error.code;
			    const errorMessage = error.message;
			    const email = error.customData.email;
			    const credential = GoogleAuthProvider.credentialFromError(error);
			    console.log(credential)
			  });						  	
	  	})

	  	document.getElementById('btnLoginAp').addEventListener('click', function(){
			const provider = new OAuthProvider('apple.com');
	  		signInWithPopup(auth, provider)
			  .then((result) => {
			    var credential = OAuthProvider.credentialFromResult(result);
			    var user = result.user;
			    var Token = credential.idToken;
			    accesoLogin(user.providerData[0], token)	
			  }).catch((error) => {
			    const errorCode = error.code;
			    const errorMessage = error.message;
			    /*const email = error.customData.email;*/
			    const credential = GoogleAuthProvider.credentialFromError(error);
			    console.log(credential)
			  });						  	
	  	})


	</script>
</body>
</html>
