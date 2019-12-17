<?php

require "../vendor/autoload.php";

use \Firebase\JWT\JWT;

require_once '.inc.php';


$jwt = isset($_COOKIE['jwt']) ? $_COOKIE['jwt'] : "";

if ($jwt != "") {
  try {

    $decoded = JWT::decode($jwt, $secret_key, array('HS256'));
  } catch (Exception $e) {
    error_log("Ha ocurrido un error: " . $e->getMessage() . " - " . date('d-m-Y H:i:s') . "\r\n", 3, "../api/auth/logs/my-errors.log");
    header('location:error.php');
  }
} else {
  header('location:/');
}
