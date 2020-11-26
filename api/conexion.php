<?php

//error_reporting(0);
$conexion = new mysqli('mycodes4you.com', 'kumo_user', 'Pv0@zr04', 'admin_kumo');
//$conexion = new mysqli('localhost','aeropuerto','Myx9ln.23','aisl_documentos');
$tildes = $conexion->query("SET NAMES 'utf8'");

if($conexion->connect_errno){ // --- si hay un error en la conexión ---
    die("La conexion no pudo establecerse");
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


?>
