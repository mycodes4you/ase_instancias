<?php
include 'conexion.php';
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
header("Content-type: application/json");

if($accion === "listado"){
	
	// --- Consulta para listado de carreras
	$query_usuarios = "SELECT * FROM usuarios WHERE usuario_activo = 1";
	$consulta_usuarios = $conexion->query($query_usuarios) or die ("Falló listado de usuarios" . $query_usuarios);
	$listado_usuarios = [];
	while($lista_usuarios = $consulta_usuarios->fetch_assoc()){
		
		$usuario_nombre = $lista_usuarios['usuario_apellido1']. ' ' .$lista_usuarios['usuario_apellido2']. ' ' .$lista_usuarios['usuario_nombre1']. ' ' .$lista_usuarios['usuario_nombre2'];

		$arreglo = array('usuario_id' => $lista_usuarios['usuario_id'],
										 'usuario_usuario' => $lista_usuarios['usuario_usuario'],
										 'usuario_nombre' => $usuario_nombre,
										 'usuario_foto' => $lista_usuarios['usuario_foto'],
										 'usuario_nombre1' => $lista_usuarios['usuario_nombre1'],
										 'usuario_nombre2' => $lista_usuarios['usuario_nombre2'],
										 'usuario_apellido1' => $lista_usuarios['usuario_apellido1'],
										 'usuario_apellido2' => $lista_usuarios['usuario_apellido2']
				);
		array_push($listado_usuarios, $arreglo); 
	}
	$query_usr = "SELECT usuario_id FROM usuarios";
	$consulta_usr = $conexion->query($query_usr) or die ("Falló num de usuarios " . $query_usr);
	$num_usr = $consulta_usr->num_rows;

	$query_usr_on = "SELECT usuario_id FROM usuarios WHERE usuario_activo = '1'";
	$consulta_usr_on = $conexion->query($query_usr_on) or die ("Falló num de usuarios " . $query_usr_on);
	$num_usr_on = $consulta_usr_on->num_rows;


	$num_usr_off = $num_usr - $num_usr_on;

	$res['num_usr_off'] = $num_usr_off;
	$res['num_usr'] = $num_usr;
	$res['num_usr_on'] = $num_usr_on;
	$res['listado_usuarios'] = $listado_usuarios;


}

elseif($accion =="listado_activas"){
	
	// --- Consulta para listado de carreras
	$query_instancias = "SELECT * FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_instancias = $conexion->query($query_instancias) or die ("Falló listado de instancias" . $query_instancias);
	$listado_instancias = [];
	while($lista_instancias = $consulta_instancias->fetch_assoc()){
		
		if($lista_instancias['servidor_instancia'] == '1'){
			$servidor = 'Codero';
		}
		elseif ($lista_instancias['servidor_instancia'] == '2') {
			$servidor = 'OVH';
		}
		elseif ($lista_instancias['servidor_instancia'] == '3') {
			$servidor = 'Jupiter';
		}
		else{
			$servidor = 'Apagado';
		}
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario=701&clave=Rjf6ge.Fa';
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia']
				);
		array_push($listado_instancias, $arreglo); 
	}
	$query_num_on = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_num_on = $conexion->query($query_num_on) or die ("Falló num de instancias " . $query_num_on);
	$num_ins_on = $consulta_num_on->num_rows;

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias " . $query_num);
	$num_ins = $consulta_num->num_rows;

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '2'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '1'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '3'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);


	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_codero = $consulta_num_ovh->num_rows;
	$num_ins_ovh = $consulta_num_codero->num_rows;
	$num_ins_off = $num_ins - $num_ins_on;

	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;


}

elseif($accion =="listado_inactivas"){
	
	// --- Consulta para listado de carreras
	$query_instancias = "SELECT * FROM b64_instancias WHERE activa_instancia = '0'";
	$consulta_instancias = $conexion->query($query_instancias) or die ("Falló listado de instancias" . $query_instancias);
	$listado_instancias = [];
	while($lista_instancias = $consulta_instancias->fetch_assoc()){
		
		if($lista_instancias['servidor_instancia'] == '1'){
			$servidor = 'Codero';
		}
		elseif ($lista_instancias['servidor_instancia'] == '2') {
			$servidor = 'OVH';
		}
		elseif ($lista_instancias['servidor_instancia'] == '3') {
			$servidor = 'Jupiter';
		}
		else{
			$servidor = 'Apagado';
		}
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario=701&clave=Rjf6ge.Fa';
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia']
				);
		array_push($listado_instancias, $arreglo); 
	}

	$num_ins_on = $consulta_instancias->num_rows;

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '2'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias " . $query_num);
	$num_ins = $consulta_num->num_rows;

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '1'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '3'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);


	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_codero = $consulta_num_ovh->num_rows;
	$num_ins_ovh = $consulta_num_codero->num_rows;
	$num_ins_off = $num_ins - $num_ins_on;

	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;


}

elseif ($accion == 'actualizar') {

		$passMd5 = md5($usuario_psswrd);
		$result = $conexion->query("UPDATE usuarios SET usuario_nombre1 = '$usuario_nombre1', usuario_nombre2 = '$usuario_nombre2', usuario_apellido1 = '$usuario_apellido1', usuario_apellido2 = '$usuario_apellido2', usuario_usuario = '$usuario_usuario', usuario_psswrd = '$passMd5' WHERE usuario_id ='$usuario_id'");
		//$desc_bit = 'Se edito alumno: ' . $alumno_matricula . ' con el alumno_email: ' . $alumno_email . ' y el telefono: ' . $alumno_telefono;
		//$bitacora = $conn->query("INSERT INTO bitacora (desc_bit) VALUES('$desc_bit')");

		if ($result) {
			$res['message'] = 'Exito! se actualizo el Usuario ' .$usuario_id;
			//$res['message2'] = $desc_bit;
		} else {
			$res['error']   = true;
			$res['message'] = 'Error al actualizar Usuario!.';
		}
}
/*elseif ($accion == 'agregar') {

	if (!isset($usuario_foto_add)){
		$nombre_archivo =$_FILES['usuario_foto_add']['name'];
    $tipo_archivo = $_FILES['usuario_foto_add']['type'];
    $tamano_archivo = $_FILES['usuario_foto_add']['size'];
    $archivo= $_FILES['usuario_foto_add']['tmp_name'];


 	} else{
 		$nombre_archivo="";
  }

  if ($nombre_archivo!=""){
	  if (!((strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "jpg")))) {
	  	if ($tamano_archivo  < 30000000) {
	  		$res['error']   = true;
				$mensaje = 'El tamaño de los archivos no es correcto. Se permiten archivos de 3 Mb máximo.';
				$res['message'] = $mensaje;
	  	}
	  	$res['error']   = true;
	  	$res1 = explode(".", $nombre_archivo);
      $extension1 = $res1[count($res1)-1];
			$mensaje = 'El tipo de archivo ' . $extension1 . ' no esta permitido. Se permiten archivos .jpg, .jpeg';
			$res['message'] = $mensaje;
	  }
	  else{

	  	$directorio = '/dist/img/';
	  	$tipo = $_FILES['usuario_foto_add']['name'];
			$ext = explode('.',$tipo);
			$extension = array_pop($ext);
			$fecha = new DateTime();
			$archivo = $directorio.$fecha->getTimestamp().'.'.$extension;
			$evento_fecha_creado = date("Y-m-d H:i:s");
			
            if (move_uploaded_file($_FILES['usuario_foto_add']['tmp_name'], $archivo)) {
               $passMd5 = md5($usuario_psswrd_add);
              $sqlFoto = $conexion->query("INSERT INTO usuarios (usuario_nombre1, usuario_nombre2, usuario_apellido1, usuario_foto, usuario_apellido2, usuario_usuario, usuario_psswrd) VALUES('$usuario_nombre1_add', '$usuario_nombre2_add', '$usuario_apellido1_add', '$archivo', $usuario_apellido2_add, $usuario_usuario_add, $passMd5)");

              //$sqlFoto = $conexion->query("UPDATE eventos SET usuario_foto_add = '$archivo' WHERE alumno_id = '$alumno_id'");
							if ($sqlFoto) {
								$mensaje = 'Se agrego correctamente el Usuario: '.$usuario_usuario.'.';
								$res['message'] = $mensaje;
								//$res['message2'] = $desc_bit;
							} else {
								$res['error']   = true;
								$mensaje = 'Error agregar el usuario: ' . $usuario_usuario;
								$res['message'] = $mensaje;
							}
                unlink($dirtemp); //Borrar el fichero temporal
            }
            else
            {
              $res['error']   = true;
							$mensaje = 'Ocurrió algún error al subir el fichero. No pudo guardarse. Nombre:'.$usuario_usuario_add.' - Archivo: '.$archivo.'';
							$res['message'] = $mensaje;
            }			
    }
	}      
}*/

elseif ($accion == 'agregar') {
	$foto = 'dist/img/usuario.png';
	$activo = '1';
	$passMd5 = md5($usuario_psswrd_add);
	$sql_add_usr = $conexion->query("INSERT INTO usuarios (usuario_nombre1, usuario_nombre2, usuario_apellido1, usuario_apellido2, usuario_usuario, usuario_psswrd, usuario_activo, usuario_foto) VALUES('$usuario_nombre1_add', '$usuario_nombre2_add', '$usuario_apellido1_add', '$usuario_apellido2_add', '$usuario_usuario_add', '$passMd5', '$activo', '$foto')");

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
	echo 'error 404';
}


echo json_encode($res);