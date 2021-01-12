<?php

//error_reporting(0);
$conexion = new mysqli('mycodes4you.com', 'ins_usr123', 'Pv0@zr04-0L@37mxl', 'admin_instancias');
//$conexion = new mysqli('localhost', 'root', '', 'ase_instancias');
//$conexion = new mysqli('localhost','aeropuerto','Myx9ln.23','aisl_documentos');
$tildes = $conexion->query("SET NAMES 'utf8'");

if($conexion->connect_errno){ // --- si hay un error en la conexión ---
    die("La conexion no pudo establecerse");
}


?>
