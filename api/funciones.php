<?php


function ejecutar_db($table, $data, $action, $parameters) {
    
    reset($data);
    global  $conexion;
    if($action == 'insertar'){
      
      $query = 'INSERT INTO ' . $table . ' (';
      while(list($columns, ) = each($data)){
        
        $query .= $columns . ', ';

      }
      
      $query = substr($query, 0, -2) . ') values (';
      reset($data);
      while(list(, $value) = each($data)){

        switch ((string)$value) {
          case 'now()':
            $query .= 'now(), ';
            break;
          case 'null':
            $query .= 'null, ';
            break;
          default:
            $query .= '\'' . addslashes($value) . '\', ';
            break;
        }

      }

      $query = substr($query, 0, -2) . ')';
      
    } elseif ($action == 'actualizar'){

      $query = 'UPDATE ' . $table . ' SET ';
      while(list($columns, $value) = each($data)){

        switch ((string)$value) {
          case 'now()':
            $query .= $columns . ' = now(), ';
            break;
          case 'null':
            $query .= $columns .= ' = null, ';
            break;
          default:
            $query .= $columns . ' = \'' . addslashes($value) . '\', ';
            break;
        }
      }
      $query = substr($query, 0, -2) . ' WHERE ' . $parameters;

    } elseif ($action == 'eliminar'){
      
      $query = 'DELETE FROM ' . $table . ' WHERE ' . $parameters;

    }
        //    echo $query;
    return $result =  $conexion -> query($query) or die("fallo, conexión: <br>" . $conexion . "query: <br>" . $query);

}



// --- Ejemplo de ejecuta db ---
/*
unset($sql_data_array);
$sql_data_array = [
  'bitacora_usuario' => 1,
];

echo '<pre>';
print_r($sql_data_array);
echo '</pre>';
$accion = 'insertar';
ejecutar_db('bitacora', $sql_data_array, $accion);*/



/////////////////////////////////////////////////////////////////////////////////////////
//Configuración del algoritmo de encriptación

$clave_en  = '38365119959639461c19a0b8dc84b61e';

//Metodo de encriptación
$method = 'aes-256-cbc';

// Puede generar una diferente usando la funcion $getIV()
$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

 /*
 Encripta el contenido de la variable, enviada como parametro.
  */
 $encriptar = function ($valor) use ($method, $clave_en, $iv) {
     return openssl_encrypt ($valor, $method, $clave_en, false, $iv);
 };

 /*
 Desencripta el texto recibido
 */
 $desencriptar = function ($valor) use ($method, $clave_en, $iv) {
     $encrypted_data = base64_decode($valor);
     return openssl_decrypt($valor, $method, $clave_en, false, $iv);
 };

 /*
 Genera un valor para IV
 */
 $getIV = function () use ($method) {
     return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
 };

 $validaAcceso = function () use ($num_funcion) {
 /* global $usuario_id;
  $preg0 = "SELECT po_numero, estado_permiso FROM b64_permisos_otorgados WHERE po_usuario = '".$usuario_id."'";
  $matr0 = $conexion->query($preg0) or die ('Error al consultar permisos '.$preg0);
  $p = $matr0->fetch_array();*/

  /*$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'OVH'";
  $consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);*/

  /*$preg1 = "SELECT permiso_modulo, permiso_numero FROM b64_permisos WHERE permiso_id = '".$p['po_numero']."'";
  $matr1 = $conexion->query($preg1) or die ('Error al consultar permisos 2 '.$preg1);
  $acc = $matr1->fetch_array();

  $f_permiso = $acc['permiso_modulo'].'-'.$acc['permiso_numero'];
  $e_permiso = $p['estado_permiso'];
      
  if($e_permiso == '1' && $f_permiso = $num_funcion) {
    $acceso = 1;
  } else {
    $acceso = 0;
  }
  return $acceso;*/
  $acceso = $num_funcion;
  return $acceso;
  };

/* function validaAcceso($num_funcion) {
 /* global $usuario_id;
  $preg0 = "SELECT po_numero, estado_permiso FROM b64_permisos_otorgados WHERE po_usuario = '".$usuario_id."'";
  $matr0 = $conexion->query($preg0) or die ('Error al consultar permisos '.$preg0);
  $p = $matr0->fetch_array();*/

  /*$query_num_ovh = "SELECT activa_instancia FROM b64_instancias WHERE servidor_instancia = 'OVH'";
  $consulta_num_ovh = $conexion->query($query_num_ovh) or die ("Falló num de instancias OVH " . $query_num_ovh);*/

  /*$preg1 = "SELECT permiso_modulo, permiso_numero FROM b64_permisos WHERE permiso_id = '".$p['po_numero']."'";
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
  $acceso = $num_funcion;
  return $acceso;
  }*/


?>

