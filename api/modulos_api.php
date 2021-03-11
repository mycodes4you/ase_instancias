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


}

elseif ($accion == 'actualizar') { 

	if(!empty($modulo_id)){
		$accion = 'actualizar';
		$parametros = "modulo_id = '$modulo_id'"; 
		unset($sql_data_array);
		$sql_data_array = ['modulo_descripcion' => $modulo_descripcion,
											 'modulo_numero' => $modulo_numero,
											 'modulo_estado' => $modulo_estado
											];		
		$bd = ejecutar_db('b64_modulos', $sql_data_array, $accion, $parametros);

		if($bd != ''){
			//echo $db;
			$res['message'] = '1 -> Exito! se actualizo el Modulo ' .$modulo_id;
		}
		else{
			$res['error']   = true;
			$res['message'] = 'Error al actualizar Modulo!.';
		}
	}
		
}
elseif ($accion == 'agregar') {

	if(!empty($modulo_descripcion_add)){

		$accion = 'insertar';
		unset($sql_data_array);
		$sql_data_array = ['modulo_descripcion' => $modulo_descripcion_add,
											 'modulo_numero' => $modulo_numero_add,
											 'modulo_estado' => $modulo_estado_add
											];		
		$bd = ejecutar_db('b64_modulos', $sql_data_array, $accion, $parametros);

		if($bd != ''){
			//echo $db;
			$res['message'] = '1 -> Exito! se agrego el modulo';
		}
		else{
			$res['error']   = true;
			$res['message'] = 'Error al crear modulo!.';
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