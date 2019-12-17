<?php

$servername = "localhost";#"20.20.20.113";
$username = "mod_matricula";
$password = "66cbb56d8737712315e3ed5995d01d2c3a0365fe"; 
$dbname = "bd_academico_unesum";

// Create connection
$conexion = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conexion) {
    die("FALLA EN LA CONEXION A LA BASE DE DATOS: " . mysqli_connect_error());
}
//mysqli_set_charset($conexion,"utf8");
date_default_timezone_set("America/Guayaquil");    

?>