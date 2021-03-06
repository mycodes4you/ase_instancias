<?php
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
session_start(); // --- Validar sesión ---

if($accion == 'salir'){

	session_destroy();
	header("location:index.php");

}

if(isset($_SESSION['usr'])){ // ---- sesión iniciada, redirigir a index ----

	header("location: index.php");

}

if($accion == 'entrar' && !isset($_SESSION['usr'])){ // --- Iniciar sesión si es redirigido y la sessi´on no existe ---
	
	// ---- Include del front ----
	include('front/login.php');
	
}

elseif($accion == 'validar'){ // --- Validar datos ---
		
	include 'api/conexion.php';
	//include 'api/funciones.php';

	/////////////////////////////////////////////////////////////////////////////////////////
	//Configuración del algoritmo de encriptación

	$clave  = '38365119959639461c19a0b8dc84b61e';

	//Metodo de encriptación
	$method = 'aes-256-cbc';

	// Puede generar una diferente usando la funcion $getIV()
	$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

	 /*
	 Encripta el contenido de la variable, enviada como parametro.
	  */
	 $encriptar = function ($valor) use ($method, $clave, $iv) {
	     return openssl_encrypt ($valor, $method, $clave, false, $iv);
	 };

	 /*
	 Desencripta el texto recibido
	 */
	 $desencriptar = function ($valor) use ($method, $clave, $iv) {
	     $encrypted_data = base64_decode($valor);
	     return openssl_decrypt($valor, $method, $clave, false, $iv);
	 };

	 /*
	 Genera un valor para IV
	 */
	 $getIV = function () use ($method) {
	     return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
	 };

	// --- Limpiar variables ---
	$email = $conexion->real_escape_string(htmlentities($email));
	$pass = $conexion->real_escape_string(htmlentities($pass));

	$passEncriptada = md5($pass);

	// --- Consultar el correo ---
	$query = "SELECT * FROM usuarios WHERE usuario_usuario = '" . $email . "' AND usuario_psswrd = '" . $passEncriptada . "'";
	$consulta = $conexion->query($query) or die ("Falló " . $query);
	//echo $query . '<br>';

	if($datos = $consulta->fetch_assoc()){ // -- Si la consulta es exitosa ---


		session_unset();
		session_destroy();
		//session_id($instan.$array_log['usuario_id']);
		session_start();

		$_SESSION['usr'] = [];
		$_SESSION['usuario_id'] = $datos['usuario_id'];
		
		// ---- Consultar información extra del alumno, administrador o profesor ---
			$pass_ase = $datos['usuario_pass_ase'];
			$pass_ase_desencriptada = $desencriptar($pass_ase);
			//$_SESSION['usuario_id'] = $datos['alumno_id'];
			$_SESSION['usuario_usuario'] = $datos['usuario_usuario'];
			$_SESSION['usuario_nombre1'] = $datos['usuario_nombre1'];
			$_SESSION['usuario_nombre2'] = $datos['usuario_nombre2'];
			$_SESSION['usuario_apellido1'] = $datos['usuario_apellido1'];
			$_SESSION['usuario_apellido2'] = $datos['usuario_apellido2'];
			$_SESSION['usuario_nombre_corto'] = $datos['usuario_apellido1'] . ' ' . $datos['usuario_nombre1'];
			$_SESSION['usuario_nombre_completo'] = $datos['usuario_apellido1'] . ' ' . $datos['usuario_apellido2'] . ' ' . $datos['usuario_nombre1'] . ' ' . $datos['usuario_nombre2'];
			$_SESSION['usuario_activo'] = $datos['usuario_activo'];
			$_SESSION['usuario_foto'] = $datos['usuario_foto'];
			$_SESSION['config_navbar'] = $datos['config_navbar'];
			$_SESSION['config_accent'] = $datos['config_accent'];
			$_SESSION['config_sidebar'] = $datos['config_sidebar'];
			$_SESSION['config_brand'] = $datos['config_brand'];
			$_SESSION['usuario_pass_ase'] = $pass_ase_desencriptada;

			
			$_SESSION['Login'] = 'True';
			$_SESSION['Time'] = time();


			


		//print_r($_SESSION);

		// --- Verificar si el usaurio tiene acceso ---
		$acceso = ($_SESSION['usuario_activo'] == 1) ? 1 : 0;
		// --- conceder acceso o denegar ---
		echo ($acceso == 1) ? 'Entrando...' : 'Tu usuario se encuentra inactivo' . session_destroy();


	} else{ 
		echo 'Se produjo un error al iniciar sesión, verifica tus credenciales e intenta de nuevo.';
	}

}
