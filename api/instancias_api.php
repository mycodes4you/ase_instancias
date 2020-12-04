<?php
include 'conexion.php';
foreach($_POST as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
foreach($_GET as $k => $v){$$k=$v;} // echo $k.' -> '.$v.' | ';
header("Content-type: application/json");
session_start();
if(!isset($_SESSION['usr'])){
	header("location:../login.php?accion=entrar"); // --- redirigir a login si no hay sesión ---
}
if ($accion == 'actualizar') {


		$result = $conexion->query("UPDATE b64_instancias SET nombre_instancia = '$instancia_nombre', servidor_instancia = '$instancia_servidor', fecha_ssl_instancia = '$instancia_ssl', prefijo_instancias = '$instancia_prefijo' WHERE id_instancia ='$instancia_id'");
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

if(isset($_GET['accion'])){
	$accion=$_GET['accion'];
}

if($accion=='mostrar'){
	$sql = "SELECT * FROM b64_instancias ORDER BY nombre_instancia ASC";
	$query_todas = $conexion->query($sql);
	$listado_instancias = array();

	while($lista_instancias = $query_todas->fetch_array()){
		
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario='.$_SESSION['usuario_usuario'].'&clave='.$_SESSION['usuario_pass_ase'];
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia'],
										 'instancia_prefijo' => $lista_instancias['prefijo_instancias']
				);
		array_push($listado_instancias, $arreglo); 
		
	}

	$query_num_on = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_num_on = $conexion->query($query_num_on) or die ("Falló num de instancias " . $query_num_on);
	$num_ins_on = $consulta_num_on->num_rows;

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'OVH'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Codero'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Jupiter'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);


	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_codero = $consulta_num_codero->num_rows;
	$num_ins_ovh = $consulta_num_ovh->num_rows;
	$num_ins = $query_todas->num_rows;
	$num_ins_off = $num_ins - $num_ins_on;

	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;
	$res['titulo'] = '';
}

if($accion=='buscar'){
	$keyword=$_POST['keyword'];
	$sql="select * from b64_instancias where nombre_instancia like '%$keyword%' or servidor_instancia like '%$keyword%'";
	$query = $conexion->query($sql);
	$listado_instancias = array();

	while($lista_instancias = $query->fetch_array()){
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario='.$_SESSION['usuario_usuario'].'&clave='.$_SESSION['usuario_pass_ase'];
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia'],
										 'instancia_prefijo' => $lista_instancias['prefijo_instancias']
				);
		array_push($listado_instancias, $arreglo); 
	}

	$res['listado_instancias'] = $listado_instancias;
	$res['titulo'] = '';
}

if($accion=='activas'){
	$sql = "SELECT * FROM b64_instancias WHERE activa_instancia = '1' ORDER BY nombre_instancia ASC";
	$query_activas = $conexion->query($sql);
	$listado_instancias = array();

	while($lista_instancias = $query_activas->fetch_array()){
		
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario='.$_SESSION['usuario_usuario'].'&clave='.$_SESSION['usuario_pass_ase'];
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia'],
										 'instancia_prefijo' => $lista_instancias['prefijo_instancias']
				);
		array_push($listado_instancias, $arreglo); 
		
	}

	$query_num_off = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '0'";
	$consulta_num_off = $conexion->query($query_num_off) or die ("Falló num de instancias " . $query_num_off);
	$num_ins_off = $consulta_num_off->num_rows;

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'OVH'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Codero'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Jupiter'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);


	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_codero = $consulta_num_codero->num_rows;
	$num_ins_ovh = $consulta_num_ovh->num_rows;
	$num_ins_on = $query_activas->num_rows;
	$num_ins = $num_ins_on + $num_ins_off;

	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;
	$res['titulo'] = 'Activas';
}

if($accion=='inactivas'){
	$sql = "SELECT * FROM b64_instancias WHERE activa_instancia = '0' ORDER BY nombre_instancia ASC";
	$query_inactivas = $conexion->query($sql);
	$listado_instancias = array();

	while($lista_instancias = $query_inactivas->fetch_array()){
		
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario='.$_SESSION['usuario_usuario'].'&clave='.$_SESSION['usuario_pass_ase'];
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia'],
										 'instancia_prefijo' => $lista_instancias['prefijo_instancias']
				);
		array_push($listado_instancias, $arreglo); 
		
	}

	$query_num_on = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_num_on = $conexion->query($query_num_on) or die ("Falló num de instancias " . $query_num_on);
	

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'OVH'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Codero'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias Codero " . $query_num_codero);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Jupiter'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);

	$num_ins_on = $consulta_num_on->num_rows;
	$num_ins_off = $query_inactivas->num_rows;
	$num_ins = $num_ins_off + $num_ins_on;
	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_codero = $consulta_num_codero->num_rows;
	$num_ins_ovh = $consulta_num_ovh->num_rows;


	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;
	$res['titulo'] = 'Inactivas';
}

if($accion=='codero'){
	$sql_codero = "SELECT * FROM b64_instancias WHERE servidor_instancia = 'Codero' ORDER BY nombre_instancia ASC";
	$consulta_codero = $conexion->query($sql_codero);
	$listado_instancias = array();

	while($lista_instancias = $consulta_codero->fetch_array()){
		
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario='.$_SESSION['usuario_usuario'].'&clave='.$_SESSION['usuario_pass_ase'];
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia'],
										 'instancia_prefijo' => $lista_instancias['prefijo_instancias']
				);
		array_push($listado_instancias, $arreglo); 
		
	}

	$query_num_on = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_num_on = $conexion->query($query_num_on) or die ("Falló num de instancias " . $query_num_on);
	

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'OVH'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias Codero " . $query_num);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Jupiter'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);

	$num_ins_codero = $consulta_codero->num_rows;
	$num_ins_ovh = $consulta_num_ovh->num_rows;
	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_on = $consulta_num_on->num_rows;
	$num_ins = $consulta_num->num_rows;
	$num_ins_off = $num_ins - $num_ins_on;


	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;
	$res['titulo'] = 'en Servidor Codero';
}

if($accion=='ovh'){
	$sql_ovh = "SELECT * FROM b64_instancias WHERE servidor_instancia = 'OVH' ORDER BY nombre_instancia ASC";
	$consulta_ovh = $conexion->query($sql_ovh);
	$listado_instancias = array();

	while($lista_instancias = $consulta_ovh->fetch_array()){
		
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario='.$_SESSION['usuario_usuario'].'&clave='.$_SESSION['usuario_pass_ase'];
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia'],
										 'instancia_prefijo' => $lista_instancias['prefijo_instancias']
				);
		array_push($listado_instancias, $arreglo); 
		
	}

	$query_num_on = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_num_on = $conexion->query($query_num_on) or die ("Falló num de instancias " . $query_num_on);
	

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Codero'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias OVH " . $query_num_codero);

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias Codero " . $query_num);

	$query_num_jup = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Jupiter'";
	$consulta_num_jup = $conexion->query($query_num_jup) or die ("Falló num de instancias Jupiter " . $query_num_jup);

	$num_ins_codero = $consulta_num_codero->num_rows;
	$num_ins_ovh = $consulta_ovh->num_rows;
	$num_ins_jup = $consulta_num_jup->num_rows;
	$num_ins_on = $consulta_num_on->num_rows;
	$num_ins = $consulta_num->num_rows;
	$num_ins_off = $num_ins - $num_ins_on;


	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;
	$res['titulo'] = 'en Servidor OVH';
}

if($accion=='jupiter'){
	$sql_jup = "SELECT * FROM b64_instancias WHERE servidor_instancia = 'Jupiter' ORDER BY nombre_instancia ASC";
	$consulta_jup = $conexion->query($sql_jup);
	$listado_instancias = array();

	while($lista_instancias = $consulta_jup->fetch_array()){
		
		$servidor = $lista_instancias['servidor_instancia'];
		$url = 'https://'.$lista_instancias['subdominio_instancia'].'.autoshop-easy.com/usuarios.php?accion=ingresar&usuario='.$_SESSION['usuario_usuario'].'&clave='.$_SESSION['usuario_pass_ase'];
		$arreglo = array('instancia_id' => $lista_instancias['id_instancia'],
										 'instancia_nombre' => $lista_instancias['nombre_instancia'],
										 'instancia_url' => $url,
										 'instancia_servidor' => $servidor,
										 'instancia_img' => $lista_instancias['img_instancia'],
										 'instancia_estado' => $lista_instancias['activa_instancia'],
										 'instancia_ssl' => $lista_instancias['fecha_ssl_instancia'],
										 'instancia_prefijo' => $lista_instancias['prefijo_instancias']
				);
		array_push($listado_instancias, $arreglo); 
		
	}

	$query_num_on = "SELECT activa_instancia FROM b64_instancias WHERE activa_instancia = '1'";
	$consulta_num_on = $conexion->query($query_num_on) or die ("Falló num de instancias " . $query_num_on);
	

	$query_num_codero = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'Codero'";
	$consulta_num_codero = $conexion->query($query_num_codero) or die ("Falló num de instancias OVH " . $query_num_codero);

	$query_num = "SELECT activa_instancia FROM b64_instancias";
	$consulta_num = $conexion->query($query_num) or die ("Falló num de instancias Codero " . $query_num);

	$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'OVH'";
	$consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias Jupiter " . $query_num_ovh);

	$num_ins_codero = $consulta_num_codero->num_rows;
	$num_ins_ovh = $consulta_num_ovh->num_rows;
	$num_ins_jup = $consulta_jup->num_rows;
	$num_ins_on = $consulta_num_on->num_rows;
	$num_ins = $consulta_num->num_rows;
	$num_ins_off = $num_ins - $num_ins_on;


	$res['num_ins_jup'] = $num_ins_jup;
	$res['num_ins_codero'] = $num_ins_codero;
	$res['num_ins_ovh'] = $num_ins_ovh;
	$res['num_ins_off'] = $num_ins_off;
	$res['num_ins'] = $num_ins;
	$res['num_ins_on'] = $num_ins_on;
	$res['listado_instancias'] = $listado_instancias;
	$res['titulo'] = 'en servidor Jupiter';
}


echo json_encode($res);