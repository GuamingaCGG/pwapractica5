<?php
$host = "sql302.infinityfree.com";               
$user = "if0_41883543";                          
$pass = "gcchristR17";         
$db   = "if0_41883543_gestiondetareas";           

$conexion = mysqli_connect($host, $user, $pass, $db);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>