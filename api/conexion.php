<?php

error_reporting(0);
$conexion = new mysqli('kumo.click', 'uNh7uH', 'Rjf6ge.Fa', 'kumov1');
//$conexion = new mysqli('localhost', 'root', '', 'ase_instancias');
//$conexion = new mysqli('localhost','aeropuerto','Myx9ln.23','aisl_documentos');
$tildes = $conexion->query("SET NAMES 'utf8'");

if($conexion->connect_errno){ // --- si hay un error en la conexión ---
    die("La conexion no pudo establecerse");
}


?>
