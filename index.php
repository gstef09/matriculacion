<?php
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;


// route the request internally
$uri = $_SERVER['REQUEST_URI'];

if ($uri == '/') {
	header("Location: accounts/login.php");
}
if ($uri == '/estudiantes') {
	header("Location: consulta_estudiantes/estudiantes.php");
}
if ($uri == '/estudiantes/actualizar-datos/') {
	header("Location: matricula/estudiantes/actualizar_datos.php");
}
if ($uri == '/reportes') {
	header("Location: report");
}