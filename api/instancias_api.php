<?php
include 'conexion.php';
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
header("Content-type: application/json");

if($accion === "listado"){
	
	// --- Consulta para listado de carreras
	$query_instancias = "SELECT * FROM b64_instancias";
	$consulta_instancias = $conexion->query($query_instancias) or die ("Falló listado de instancias" . $query_instancias);
	$listado_instancias = [];
	while($lista_instancias = $consulta_instancias->fetch_assoc()){
		
			/*if($lista_instancias['servidor_instancia'] == '1'){
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
		}*/
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario=701&clave=Rjf6ge.Fa';
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia']
				);
		array_push($listado_instancias, $arreglo); 
	}

	$query_num_on = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_num_on = $conexion->query($query_num_on) or die ("Falló num de instancias " . $query_num_on);
	$num_ins_on = $consulta_num_on->num_rows;

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '2'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '1'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '3'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);


	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_codero = $consulta_num_ovh->num_rows;
	$num_ins_ovh = $consulta_num_codero->num_rows;
	$num_ins = $consulta_instancias->num_rows;
	$num_ins_off = $num_ins - $num_ins_on;

	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;


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

elseif($accion =="listado_codero"){
	
	// --- Consulta para listado de carreras
	$query_instancias = "SELECT * FROM b64_instancias WHERE servidor_instancia = '1'";
	$consulta_instancias = $conexion->query($query_instancias) or die ("Falló listado de instancias" . $query_instancias);
	$listado_instancias = [];
	while($lista_instancias = $consulta_instancias->fetch_assoc()){
		
		if($lista_instancias['servidor_instancia'] == '1'){
			$servidor = 'Codero';
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

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '2'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '1'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '3'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias " . $query_num);
	$num_ins = $consulta_num->num_rows;


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

elseif($accion =="listado_ovh"){
	
	// --- Consulta para listado de carreras
	$query_instancias = "SELECT * FROM b64_instancias WHERE servidor_instancia = '2'";
	$consulta_instancias = $conexion->query($query_instancias) or die ("Falló listado de instancias" . $query_instancias);
	$listado_instancias = [];
	while($lista_instancias = $consulta_instancias->fetch_assoc()){
		
		if($lista_instancias['servidor_instancia'] == '2'){
			$servidor = 'OVH';
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

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '2'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '1'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '3'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias " . $query_num);
	$num_ins = $consulta_num->num_rows;


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

elseif($accion =="listado_jupiter"){
	
	// --- Consulta para listado de carreras
	$query_instancias = "SELECT * FROM b64_instancias WHERE servidor_instancia = '3'";
	$consulta_instancias = $conexion->query($query_instancias) or die ("Falló listado de instancias" . $query_instancias);
	$listado_instancias = [];
	while($lista_instancias = $consulta_instancias->fetch_assoc()){
		
		if($lista_instancias['servidor_instancia'] == '3'){
			$servidor = 'Jupiter';
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

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '2'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '1'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = '3'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias " . $query_num);
	$num_ins = $consulta_num->num_rows;


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


		$result = $conexion->query("UPDATE b64_instancias SET nombre_instancia = '$instancia_nombre', servidor_instancia = '$instancia_servidor', fecha_ssl_instancia = '$instancia_ssl' WHERE id_instancia ='$instancia_id'");
		//$desc_bit = 'Se edito alumno: ' . $alumno_matricula . ' con el alumno_email: ' . $alumno_email . ' y el telefono: ' . $alumno_telefono;
		//$bitacora = $conn->query("INSERT INTO bitacora (desc_bit) VALUES('$desc_bit')");

		if ($result) {
			$res['message'] = 'Exito! se actualizo la instancia ' .$instancia_nombre;
			//$res['message2'] = $desc_bit;
		} else {
			$res['error']   = true;
			$res['message'] = 'Error al actualizar instancia!.';
		}
}
else{
	echo 'error 404';
}


echo json_encode($res);