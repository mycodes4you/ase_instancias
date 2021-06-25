<?php
//include('conexion.php');
function validaAcceso($funnum, $usr) {/*
  global $conexion;
  $parte = explode('-', $funnum);
  $modulo = $parte[0];
  $numero = $parte[1];

  $query_permisos = "SELECT * FROM b64_permisos WHERE permiso_modulo = '".$modulo."' AND permiso_numero = '".$numero."' ";
  $consulta_permisos = $conexion->query($query_permisos) or die ("Falló listado de usuarios" . $query_permisos);
  $p = $consulta_permisos->fetch_array();

  $query_po = "SELECT * FROM b64_permisos_otorgados WHERE po_usuario = '".$usr."' AND po_numero = '".$p['permiso_id']."' ";
  $consulta_po = $conexion->query($query_po) or die ("Falló listado de usuarios" . $query_po);
  $po = $consulta_po->fetch_array();

  $permiso = $p['permiso_modulo'].'-'.$p['permiso_numero'];

  if($permiso == $funnum && $po['estado_permiso'] == 1){
    $res = TRUE;
  }
  else{
    $res = FALSE;
  }
  return $res;*/
}

function mostrarTexto($texto) {
  return $texto;
}

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

?>

