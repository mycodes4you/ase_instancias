<?php

	session_start(); // --- Validar sesión ---
	error_reporting(0);

	if(!isset($_SESSION['usr'])){
			
		echo 'no hay sesión';
		header("location:login.php?accion=entrar"); // --- redirigir a login si no hay sesión ---
			
	} else{
		
		echo 'hay sesión<br>';
		header("location:inicio.php?accion=dashboard"); // --- redirigir si hay sesión ---
			
	}


?>