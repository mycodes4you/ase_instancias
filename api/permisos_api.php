<?php
include 'conexion.php';
include 'funciones.php';
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
header("Content-type: application/json");
session_start();
if(!isset($_SESSION['usr'])){
	header("location:../login.php?accion=entrar"); // --- redirigir a login si no hay sesión ---
}
if($accion === "listado"){
	
	// --- Consulta para listado de carreras
	$query_modulos = "SELECT * FROM b64_modulos";
	$consulta_modulos = $conexion->query($query_modulos) or die ("Falló listado de modulos" . $query_modulos);
	$listado_modulos = [];
	while($lista_modulos = $consulta_modulos->fetch_assoc()){

		$arreglo = array('modulo_id' => $lista_modulos['modulo_id'],
										 'modulo_descripcion' => $lista_modulos['modulo_descripcion'],
										 'modulo_numero' => $lista_modulos['modulo_numero'],
										 'modulo_estado' => $lista_modulos['modulo_estado']
				);
		array_push($listado_modulos, $arreglo); 
	}

	
	$res['listado_modulos'] = $listado_modulos;

	$query_permisos = "SELECT * FROM b64_permisos ORDER BY permiso_modulo, permiso_numero ASC";
	$consulta_permisos = $conexion->query($query_permisos) or die ("Falló listado de permisos" . $query_permisos);
	$listado_permisos = [];
	while($lista_permisos = $consulta_permisos->fetch_assoc()){

		$arreglo = array('permiso_id' => $lista_permisos['permiso_id'],
										 'permiso_descripcion' => $lista_permisos['permiso_descripcion'],
										 'permiso_modulo' => $lista_permisos['permiso_modulo'],
										 'permiso_numero' => $lista_permisos['permiso_numero']
				);
		array_push($listado_permisos, $arreglo); 
	}
	$res['listado_permisos'] = $listado_permisos;

}

elseif ($accion == 'actualizar') { 

	if(!empty($permiso_id)){
		$accion = 'actualizar';
		$parametros = "permiso_id = '$permiso_id'"; 
		unset($sql_data_array);
		$sql_data_array = ['permiso_descripcion' => $permiso_descripcion,
											 'permiso_modulo' => $permiso_modulo,
											 'permiso_numero' => $permiso_numero
											];		
		$bd = ejecutar_db('b64_permisos', $sql_data_array, $accion, $parametros);

		if($bd != ''){
			//echo $db;
			$res['message'] = '1 -> Exito! se actualizo el Permiso ' .$permiso_id;
		}
		else{
			$res['error']   = true;
			$res['message'] = 'Error al actualizar Usuario!.';
		}
	}
		
}
elseif ($accion == 'agregar') {

	if(!empty($permiso_descripcion_add)){

		$accion = 'insertar';
		unset($sql_data_array);
		$sql_data_array = ['permiso_descripcion' => $permiso_descripcion_add,
											 'permiso_modulo' => $permiso_modulo_add,
											 'permiso_numero' => $permiso_numero_add
											];		
		$bd = ejecutar_db('b64_permisos', $sql_data_array, $accion, $parametros);

		if($bd != ''){
			//echo $db;
			$res['message'] = '1 -> Exito! se agrego el permiso';
		}
		else{
			$res['error']   = true;
			$res['message'] = 'Error al crear permiso!.';
		}
	}


/*




	if (empty($usuario_nombre1_add) OR empty($usuario_usuario_add) OR empty($usuario_apellido1_add) OR empty($usuario_psswrd_add) OR empty($usuario_pass_ase_add)){
		$res['error']   = true;
		$mensaje1 = 'Faltan los datos: ';
		if(empty($usuario_nombre1_add)) { $mensaje = ' Primer Nombre|' . $mensaje; }
		if(empty($usuario_usuario_add)) { $mensaje = ' Usuario|' . $mensaje; }
		if(empty($usuario_apellido1_add)) { $mensaje = ' Primer Apellido|' . $mensaje; }
		if(empty($usuario_psswrd_add)) { $mensaje = ' Contraseña|' . $mensaje; }
		if(empty($usuario_pass_ase_add)) { $mensaje = ' Contraseña ASE|' . $mensaje; }
		
		$res['message'] = $mensaje1 . ' ' .$mensaje;
	}
	else{
		$foto = 'dist/img/usuario.png';
		$activo = '1';
		$passMd5 = md5($usuario_psswrd_add);
		$passASEencriptada = $encriptar($usuario_pass_ase_add);

		$sql_verifica_usr = "SELECT usuario_usuario FROM permisos WHERE usuario_usuario = '$usuario_usuario_add'";
		$consulta_vu = $conexion->query($sql_verifica_usr);
		$existe = $consulta_vu->num_rows;
		if($exite !== 0){
			$sql_add_usr = $conexion->query("INSERT INTO usuarios (usuario_nombre1, usuario_nombre2, usuario_apellido1, usuario_apellido2, usuario_usuario, usuario_psswrd, usuario_activo, usuario_foto, usuario_pass_ase) VALUES('$usuario_nombre1_add', '$usuario_nombre2_add', '$usuario_apellido1_add', '$usuario_apellido2_add', '$usuario_usuario_add', '$passMd5', '$activo', '$foto', '$passASEencriptada')");

			if ($sql_add_usr) {
				$mensaje = 'Se agrego correctamente el Usuario: '.$usuario_usuario_add;
				$res['message'] = $mensaje;
			} 
			else {
				$res['error']   = true;
				$mensaje = 'Error agregar el usuario: ' . $usuario_usuario_add.' ==> ' .$sql_add_usr;
				$res['message'] = $mensaje;
			}
		}
		else{
			$res['error']   = true;
			$mensaje = 'El usuario: ' .$usuario_usuario_add .' Ya existe!!!';
			$res['message'] = $mensaje;
		}

		
	}*/
}


echo json_encode($res);