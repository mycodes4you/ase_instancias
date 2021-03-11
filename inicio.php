<?php
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
session_start(); // --- Validar sesión ---
//error_reporting(0);
include ('api/conexion.php');

// ---- Se establece la zona horarira y el lenguaje
date_default_timezone_set("America/Mexico_City");
setlocale(LC_ALL , 'es_ES.UTF-8');

// ---- Hora actual
$hora_actual = strftime("%A, %e $de %B $de %Y %R");

// ---- Saludo de acuerdo a la hora del día
$today = getdate();
$hora=$today["hours"];
if ($hora<12) {
	$saludo = '<i class="fas fa-sun fa-lg" style="color: #ffef00; text-shadow: 0 0 5px #000;"></i> Buenos días bienvenid@ a Instancias Autoshop-Easy by KUMO';
}elseif($hora<19){
	$saludo = '<i class="fas fa-cloud-sun fa-lg" style="color: #faff50; text-shadow: 0 0 5px #000;"></i> Buenas tardes bienvenid@ a Instancias Autoshop-Easy by KUMO';
}else{ 
	$saludo = '<i class="fas fa-moon fa-lg" style="color: blue; text-shadow: 0 0 5px #000;"></i> Buenas Noches bienvenid@ a Instancias Autoshop-Easy by KUMO'; 
}


$usuario_id = $_SESSION['usuario_id'];

/*$validaAcceso = function ($num_funcion) {
	$preg0 = "SELECT po_numero, estado_permiso FROM b64_permisos_otorgados WHERE po_usuario = '".$usuario_id."'";
	$matr0 = $conexion->query($preg0) or die ('Error al consultar permisos '.$preg0);
	$p = $matr0->fetch_array();

	$preg1 = "SELECT permiso_modulo, permiso_numero FROM b64_permisos WHERE permiso_id = '".$p['po_numero']."'";
	$matr1 = $conexion->query($preg1) or die ('Error al consultar permisos 2 '.$preg1);
	$acc = $matr1->fetch_array();

	$f_permiso = $acc['permiso_modulo'].'-'.$acc['permiso_numero'];
	$e_permiso = $p['estado_permiso'];
			
	if($e_permiso == '1' && $f_permiso = $num_funcion) {
		$acceso = 1;
	} else {
		$acceso = 0;
	}
	return $acceso;
};*/






// --- URL para axios
$url_axios = $_SERVER['HTTP_HOST'];
//$url_axios = "https://atom-rm.com/control/";


if(!isset($_SESSION['usr'])){
	header("location:login.php?accion=entrar"); // --- redirigir a login si no hay sesión ---
}

if($accion == 'dashboard'){
	
	$titulo_pagina = 'Dashboard';
	// ---- Marcar la sección en el menú ---
	$menu_dashboard = 'active';
	// --- BACKEND ----
	include('front/dashboard.php');
	unset($_SESSION['mensajes']);
	
}

elseif($accion == 'cuenta'){
	
	$titulo_pagina = 'Cuenta';
	// ---- Marcar la sección en el menú ---
	$menu_cuenta = 'active';
	// --- BACKEND ----
	include('front/cuenta.php');
	unset($_SESSION['mensajes']);
	
}

elseif($accion == 'instancias'){
	
	$titulo_pagina = 'Instancias';
	// ---- Marcar la sección en el menú ---
	$menu_instancias_abierto = 'menu-open';
	$menu_todas = 'active';
	$menu_instancias = 'active';
	// --- BACKEND ----
	include('front/instancias.php');
	unset($_SESSION['mensajes']);
	
}

elseif($accion == 'instancias_activas'){
	
	$titulo_pagina = 'Instancias Activas';
	// ---- Marcar la sección en el menú ---
	$menu_instancias_activas = 'active';
	$menu_instancias_abierto = 'menu-open';
	$menu_instancias = 'active';
	// --- BACKEND ----
	include('front/instancias_activas.php');
	unset($_SESSION['mensajes']);
	
}

elseif($accion == 'instancias_inactivas'){
	
	$titulo_pagina = 'Instancias Inactivas';
	// ---- Marcar la sección en el menú ---
	$menu_instancias_inactivas = 'active';
	$menu_instancias_abierto = 'menu-open';
	$menu_instancias = 'active';
	// --- BACKEND ----
	include('front/instancias_inactivas.php');
	unset($_SESSION['mensajes']);
	
}

elseif($accion == 'instancias_codero'){
	
	$titulo_pagina = 'Instancias en servidor Codero';
	// ---- Marcar la sección en el menú ---
	$menu_instancias_codero = 'active';
	$menu_instancias_abierto = 'menu-open';
	$menu_instancias = 'active';
	// --- BACKEND ----
	include('front/instancias_codero.php');
	unset($_SESSION['mensajes']);
	
}

elseif($accion == 'instancias_ovh'){
	
	$titulo_pagina = 'Instancias en servidor OVH';
	// ---- Marcar la sección en el menú ---
	$menu_instancias_ovh = 'active';
	$menu_instancias_abierto = 'menu-open';
	$menu_instancias = 'active';
	// --- BACKEND ----
	include('front/instancias_ovh.php');
	unset($_SESSION['mensajes']);
	
}

elseif($accion == 'instancias_jupiter'){
	
	$titulo_pagina = 'Instancias en servidor Jupiter';
	// ---- Marcar la sección en el menú ---
	$menu_instancias_jupiter = 'active';
	$menu_instancias_abierto = 'menu-open';
	$menu_instancias = 'active';
	// --- BACKEND ----
	include('front/instancias_jupiter.php');
	unset($_SESSION['mensajes']);
	
}
elseif($accion == 'usuarios'){
	
	$titulo_pagina = 'Usuarios';
	// ---- Marcar la sección en el menú ---
	$menu_usuarios_t = 'active';
	$menu_usuarios_abierto = 'menu-open';
	$menu_usuarios = 'active';
	// --- BACKEND ----
	include('front/usuarios.php');
	unset($_SESSION['mensajes']);
	
}
else{
	$titulo_pagina = 'Error 404';
	include('front/423.php');
}

?>
