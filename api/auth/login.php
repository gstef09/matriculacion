<?php
include_once '../../conf/__bd.php';
require "../../vendor/autoload.php";

use \Firebase\JWT\JWT;

require_once '.inc.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$password = '';

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();



$data = json_decode(file_get_contents("php://input"));

$user = $data->user;
$password = sha1($data->password);

$table_name = 'vista_usuarios';

$query = "SELECT id_persona, nombres, clave, IdTipoUsuario, Activar_Profesor, activar_modificar FROM " . $table_name . " WHERE id_persona = ?  and IdTipoUsuario= 1";

$stmt = $conn->prepare($query);

$stmt->bindParam(1, $user);
$stmt->execute();
$num = $stmt->rowCount();


if ($num > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $id = $row['id_persona'];
    $names = $row['nombres'];
    $password2 = $row['clave'];
    $userType = $row['IdTipoUsuario'];
    $permission1 = $row['Activar_Profesor'];
    $permission2 = $row['activar_modificar'];
    $time_to_update = false;

    if ($password == $password2) {
        $st = $conn->prepare("SELECT pass_updated from _bp_usuarios where usuario = ? and  IdTipoUsuario= 1");
        $st->bindParam(1, $user);
        $st->execute();
        $last_update = $st->fetch(PDO::FETCH_ASSOC);

        if ($last_update['pass_updated'] == null) {
            $time_to_update = true;
        } else {
            $next_update = date("Y-m-d", strtotime("+1 month", strtotime($last_update['pass_updated'])));
            $today = date('Y-m-d');
            if ($today == $next_update) {
                $time_to_update = true;
            }
        }
        $token = array(
            "iss" => $iss,
            "aud" => $aud,
            "iat" => $iat,
            "nbf" => $nbf,
            "data" => array(
                "id" => $id,
                "names" => $names,
                "userType" => $userType,
                "permission1" => $permission1,
                "permission2" => $permission2
            )
        );

        http_response_code(200);

        $jwt = JWT::encode($token, $secret_key);
        echo json_encode(
            array(
                "message" => "Bienvenido(a)" . $names,
                "jwt" => $jwt,
                "last" => $time_to_update
                
            )
        );
    } else {

        http_response_code(401);
        echo json_encode(array("message" => "Usuario o contraseña incorecta"));
    }
} else {

    http_response_code(401);
    echo json_encode(array("message" => "Usuario o contraseña incorecta"));
}
