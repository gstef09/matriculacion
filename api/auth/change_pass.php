<?php
include_once '../../conf/__bd.php';

$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
$request = json_decode(file_get_contents("php://input"));

$query =  "SELECT * FROM reset_pass_requests where sha1(usuario) = ? AND token = ? and valido_hasta > NOW() and utilizado=false";
$stmt = $conn->prepare($query);
$stmt->bindParam(1, $request->auth->user);
$stmt->bindParam(2, $request->auth->token);
$stmt->execute();
$valid = $stmt->rowCount();

if ($valid > 0) {
    if ($request->data->new_pass === $request->data->pass_confirm) {
        $st = $conn->prepare("SELECT clave FROM  _bp_usuarios  where sha1(usuario) = ?");
        $st->bindParam(1, $request->auth->user);
        $st->execute();
        $row = $st->fetch(PDO::FETCH_ASSOC);
        if($row['clave'] !== sha1($request->data->new_pass)){
            $stmt1 = $conn->prepare("UPDATE _bp_usuarios set clave = SHA1(?), pass_updated = NOW() where sha1(usuario) = ?");
            $stmt1->bindParam(1, $request->data->new_pass);
            $stmt1->bindParam(2, $request->auth->user);
            $stmt1->execute();
            $updated = $stmt1->rowCount();
            if($updated > 0){
                $stmt2 = $conn->prepare("UPDATE reset_pass_requests SET utilizado = true, usado_en  = NOW() where sha1(usuario) = ? and token =?");
                $stmt2->bindParam(1, $request->auth->user);
                $stmt2->bindParam(2, $request->auth->token);
                $stmt2->execute();
                echo json_encode(array ('message' => 'El cambio de contarseña de su cuenta se realizó exito. <a href="login.php">Iniciar Sesión</>',
               'valid' => true));
            }else{
                echo json_encode(array ('message' => 'Su contrasñe ya fue cambiada <a href="login.php">Iniciar Sesión</>',
               'valid' => true));
            }
        }else{
            echo json_encode(array ('message' => 'La nueva contraseña no puede ser una contreseña anterior',
               'valid' => false));
        }
       
       
    } else {
       echo json_encode( array ('message' => 'Las contraseñas no counciden',
        'valid' => false));
    }
} else {
    echo json_encode( array('message'=>'Su solicitud de cambio de contrseña ha caducado. <a href="pass_reset.php">Solicitar nuevo cambio de contraseña</>',
    'valid' => false));
}